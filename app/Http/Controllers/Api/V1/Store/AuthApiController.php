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
use DB;
use Mail;
use Illuminate\Support\Str;
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









    public function ForgetPassword(Request $request)
      {

        try {
          $request->validate([
              'email' => 'required|email|exists:users',
          ]);

          $user = User::firstWhere('email', $request->email);
          if (!$user) {
              return response(['message' => 'non'], 404);
          }

        $token = rand(100000, 999999);

          DB::table('password_resets')->insert([
              'email' => $request->email,
              'token' => $token,
              'created_at' => Carbon::now()
            ]);

          Mail::send('emails.forgetPassword', ['token' => $token], function($message) use($request){
              $message->to($request->email);
              $message->subject('Reset Password');
          });

        } catch (Exception $ex) {
                    Log::info("Problème lors  d'envoi code rset password"  . json_encode($data));
                    Log::error($ex->getMessage());
                    return response(['errors' => $ex->getMessage()], 500);
                }

                return response(['message' => 'Nous avons envoyé votre code de réinitialisation de mot de passe par e-mail '], 200);
      }


    
      public function ResetPassword(Request $request)
      {

            $request->validate([
                "token" => 'required',
                "email" => 'required',
                "password" => 'required'
            ]);

            $updatePassword = DB::table('password_resets')
                                ->where([
                                  'email' => $request->email,
                                  'token' => $request->token
                                ])
                                ->first();

            if(!$updatePassword){
                return response(['message' => 'code n\existe pas'], 404);
            }

            $user = User::where('email', $request->email)
                        ->update(['password' => Hash::make($request->password)]);

            DB::table('password_resets')->where(['email'=> $request->email])->delete();

            return response(['message' => 'password updated with success'], 201);
        }
      }




