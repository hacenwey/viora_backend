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
use App\Jobs\SendSmsJob;

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
            } else {
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
                'name' => 'required',
                // 'last_name' => 'required',
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
            if ($user) {
                $token = $user->createToken($request->device_name)->plainTextToken;

                $response = [
                    'success' => true,
                    'user' => $user,
                    'token' => $token,
                ];

            } else {

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
            $validator = Validator::make($request->all(), [
                'fcm_token' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors(),
                ], 401);
            }
            $user = User::findOrFail($request->user_id)->update($request->all());

            $response = [
                'success' => true,
            ];

            return response($response);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }

    }


    public function destroy(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            $response = [
                'success' => true,
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
                'phone_number' => 'required',
            ]);

            $user = User::firstWhere('phone_number', $request->phone_number);
            if (!$user) {
                return response(['message' => 'l utilisateur n existe pas '], 404);
            }

            $token = rand(1000, 9999);

            DB::table('password_resets')->insert([
                'email' => $request->phone_number,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
            $message = "عزيزي المستخدم،
بناءً على طلبك لإعادة تعيين كلمة المرور، إليك رمز التحقق الخاص بك: " . $token . "
يرجى استخدام هذا الرمز لاستكمال عملية إعادة التعيين. إذا لم تقم بطلب هذا، يرجى تجاهل هذه الرسالة.

مع خالص التحية،
فريق الدعم

Cher utilisateur,
Suite à votre demande de réinitialisation de mot de passe, voici votre code de vérification :" . $token . "
Veuillez utiliser ce code pour compléter le processus de réinitialisation. Si vous n'avez pas effectué cette demande, veuillez ignorer ce message.

Cordialement,
L'équipe de support";
            $payload = [
                'phone_numbers' => ['222' . $request->phone_number],
                'message' => preg_replace('/\. +/', ".\n", $message)
            ];

            try {
                SendSmsJob::dispatch($payload);
            } catch (\Exception $e) {
                \Log::error('Error sending SMS: ' . $e->getMessage());
            }

        } catch (Exception $ex) {
            Log::info("Problème lors  d'envoi code rset password" . json_encode($data));
            Log::error($ex->getMessage());
            return response(['errors' => $ex->getMessage()], 500);
        }

        return response(['message' => 'Nous avons envoyé votre code de réinitialisation de mot de passe par e-mail '], 200);
    }



    public function ResetPassword(Request $request)
    {
        \Log::info($request->all());

        $request->validate([
            "token" => 'required',
            "phone_number" => 'required',
            "password" => 'required'
        ]);
        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->phone_number,
                'token' => $request->token
            ])
            ->first();

        if (!$updatePassword) {
            return response(['message' => 'code n\existe pas'], 404);
        }

        $user = User::where('phone_number', $request->phone_number)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email' => $request->email])->delete();

        return response(['message' => 'password updated with success'], 200);
    }


    public function chekValidateCode(Request $request)
    {
        $request->validate([
            "token" => 'required',
            "phone_number" => 'required',
        ]);
        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->phone_number,
                'token' => $request->token
            ])
            ->first();

        if (!$updatePassword) {
            return response(['message' => 'code n\existe pas'], 404);
        }



        return response(['message' => 'code valide'], 200);

    }

    public function switchToSeller(Request $request)
    {

        $request->validate([
            "occupation" => 'required',
            "age" => 'required',
            "adress" => 'required',
            'status' => 'required'
        ]);


        $user = $request->user()->update($request->all());


        return response(['message' => 'switch To Seller with success'], 200);
    }
}
