<?php

namespace App\Http\Controllers\Api\V1\Store;

use App\Models\User;
use Carbon\Carbon;
use App\Models\Brand;
use Helper;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Modules\PointFidelite\Enums\eKeyPointConfig;
use App\Modules\PointFidelite\Models\PointsConfig;
use Illuminate\Support\Facades\Validator;
use Hash;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required',
                'password' => 'required',
                'device_name' => 'required'
            ]);

            $user = User::where('email', $request->email)
                ->orWhere('phone_number', $request->email)
                ->first();
            if ($user && Hash::check($request->password, $user->password)) {
                $token = $user->createToken($request->device_name)->plainTextToken;
                $response = [
                    'success' => true,
                    'user' => $user,
                    "solde_point_fidelite" => $user->getPointFideliteSolde(),
                    "points_to_currency" => $user->getPointsToCurrency(),
                    "min_point_fidelite" => PointsConfig::firstWhere('key', eKeyPointConfig::MIN_POINTS)->value,
                    'token' => $token,
                ];
            }else{
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }


            return response($response, 201);
        } catch (\Exception $e) {

            $response = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            return response($response, 422);
        }

    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'phone_number' => 'required|regex:/^[0-9]+$/|min:8|unique:users',
                'email' => 'unique:users',
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors(),
                ], 401);
            }

            $role = Role::where('title', 'Client')->first();
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $createdUser = User::create($input);

            $createdUser->roles()->sync($role);

            $token = $createdUser->createToken($request->device_name)->plainTextToken;

            $user = User::findOrFail($createdUser->id);

            $response = [
                'success' => true,
                'user' => $user,
                'token' => $token,
            ];

            return response($response);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }

    }

    public function social_signin(Request $request)
    {
        try {

            $input = $request->all();

            $user = User::where('email', $request->email)->orWhere('phone_number', $request->phone_number)->orWhere('name', $request->name)->first();
            if($user){
                $token = $user->createToken($request->device_name)->plainTextToken;

                $response = [
                    'success' => true,
                    'user' => $user,
                    'token' => $token,
                ];

            }else{

                $role = Role::where('title', 'Client')->first();
                $input['password'] = bcrypt($input['password']);
                $createdUser = User::create($input);
                $createdUser->roles()->sync($role);

                $token = $createdUser->createToken($request->device_name)->plainTextToken;

                $user = User::findOrFail($createdUser->id);

                $response = [
                    'success' => true,
                    'user' => $user,
                    'token' => $token,
                ];
            }

            return response($response);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }

    }

    public function profile(Request $request)
    {
        try {
            $user = User::findOrFail($request->user_id);
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'phone_number' => 'required|regex:/^[0-9]+$/|min:8|unique:users,phone_number,'.$user->id,
                'email' => 'unique:users,email,'.$user->id,
                'name' => 'unique:users,name'.$user->id,
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors(),
                ], 401);
            }

            $input = $request->all();
            if ($request->password){
                $input['password'] = bcrypt($input['password']);
            }
            $user->update($input);

            $token = $user->createToken($request->device_name)->plainTextToken;

            $response = [
                'success' => true,
                'user' => $user,
                'token' => $token,
            ];

            return response($response);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }

    }

}
