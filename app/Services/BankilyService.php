<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\BankilyToken;
use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;

class BankilyService {
    const ACCESS_TOKEN_CACHE_KEY = 'bankily_access_token';
    const ACCESS_TOKEN_CACHE_EXPIRATION_TIME = 60;
    const CONTENT_TYPE = 'application/json';
    /**
 * Process payment transaction.
 *
 * @param  array  $requestPayload
 * @return \Illuminate\Http\Response
 */
static function processPayment($requestPayload)
{
    try {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.self::getBankilyAccessToken(),
            'Content-Type' => self::CONTENT_TYPE,
        ])->post(env('BANKILY_BASE_URL').'payment', $requestPayload);

        self::saveTransactionDetails($requestPayload, $response->body());

        return response([
            'status' => true,
            'data' => json_decode($response->body()),
        ], 200);
    } catch (\Exception $e) {
        Log::error("Error in payment transaction: {$e->getMessage()}");
        return response([
            'status' => false,
            'message' => 'An error occurred while processing the payment.',
        ], 500);
    }
}

/**
 * Check transaction status.
 *
 * @param  string  $operationID
 * @return \Illuminate\Http\Response
 */
static function checkTransaction($operationID)
{
    try {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.self::getBankilyAccessToken(),
            'Content-Type' => self::CONTENT_TYPE,
        ])->post(env('BANKILY_BASE_URL').'checkTransaction', $operationID);

        return response([
            'status' => true,
            'data' => json_decode($response->body()),
        ], 200);
    } catch (\Exception $e) {
        Log::error("Error in checking transaction: {$e->getMessage()}");
        return response([
            'status' => false,
            'message' => 'An error occurred while processing the check Transaction.',
        ], 500);
    }
}

/**
 * Get access token from cache or database.
 *
 * @return string
 */
private static function getBankilyAccessToken(): string
{
    return Cache::remember(self::ACCESS_TOKEN_CACHE_KEY, self::ACCESS_TOKEN_CACHE_EXPIRATION_TIME, function () {
        try {
            $bankilyToken = BankilyToken::findOrFail(1);
        return $bankilyToken->acces_token;
        } catch (\Exception $e) {
            Log::error("Bankily token with ID 1 not found: {$e->getMessage()}");
            return '';
        }
    });
}


/**
 * Save transaction details to database.
 *
 * @param  array  $requestPayload
 * @param  string  $responseData
 * @return void
 */
private function saveTransactionDetails($requestPayload, $responseData)
{
    try {
        $transaction = new Transaction([
            'clientPhone' => $requestPayload['clientPhone'],
            'operationId' => $requestPayload['operationId'],
            'request_payload' => json_encode($requestPayload),
            'response_data' => $responseData,
            'ip_address' => self::getRequestIpAddress(),
            'user_agent' => self::getRequestUserAgent(),
        ]);
        $transaction->save();
    } catch (\Exception $e) {
        Log::error("Error in saving transaction details: {$e->getMessage()}");
    }
}


private function getRequestIpAddress(): string
{
    return request()->ip();
}

private function getRequestUserAgent(): string
{
    return request()->header('user-agent');
}

 
}