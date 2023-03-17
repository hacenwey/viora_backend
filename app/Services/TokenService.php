<?php
namespace App\Services;

use Log;
use App\Models\BankilyToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
class TokenService {
    const ACCESS_TOKEN_CACHE_KEY = 'bankily_access_token';
    const ACCESS_TOKEN_CACHE_EXPIRATION_TIME = 30;

    static function initialToken(){
        try {
            $response = Http::asForm()->post(env('BANKILY_BASE_URL') . 'authentification', [
                'grant_type' => env('GRANT_TYPE'),
                'username' => env('USERNAME'),
                'password' => env('PASSWORD'),
                'client_id' => env('CLIENT_ID')
            ], [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ]);
            $data = json_decode($response->body());

            BankilyToken::updateOrCreate(['id' => 1],['acces_token' => $data->access_token,'expires_in' => $data->expires_in,'refresh_token' => $data->refresh_token,'refresh_expires_in' => $data->refresh_expires_in]);
            Cache::put(self::ACCESS_TOKEN_CACHE_KEY, $data->access_token, self::ACCESS_TOKEN_CACHE_EXPIRATION_TIME);


        } catch (\Exception $e) {
            Log::error('Error occurred while getting the token: ' . $e->getMessage());

        }

    }
}