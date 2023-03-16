<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\BankilyToken;
use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;
use App\Models\Order;

class BankilyService {
    const ACCESS_TOKEN_CACHE_KEY = 'bankily_access_token';
    const ACCESS_TOKEN_CACHE_EXPIRATION_TIME = 30;
    const CONTENT_TYPE = 'application/json';
    /**
 * Process payment transaction.
 *
 * @param  array  $requestPayload
 * @return \Illuminate\Http\Response
 */
static function processPayment($requestPayload): \Illuminate\Http\Response
{
    try {
        // dd(self::getBankilyAccessToken());
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.self::getBankilyAccessToken(),
            'Content-Type' => self::CONTENT_TYPE,
        ])->post(env('BANKILY_BASE_URL').'payment', $requestPayload);

        self::saveTransactionDetails($requestPayload, json_decode($response->body()));

        return response([
            'message' => 'payment success',
            'data' => json_decode($response->body()),
        ], 200);
    } catch (\Exception $e) {
        Log::error("Error in payment transaction: {$e->getMessage()}");
        return response([
            'message' => 'An error occurred while processing the payment.',
            'data' => null,

        ], 500);
    }
}

/**
 * Check transaction status.
 *
 * @param  string  $operationID
 * @return \Illuminate\Http\Response
 */
static function checkTransaction($operationID): \Illuminate\Http\Response
{
    try {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.self::getBankilyAccessToken(),
            'Content-Type' => self::CONTENT_TYPE,
        ])->post(env('BANKILY_BASE_URL').'checkTransaction', $operationID);

        return response([
            'message' => 'check Transaction success.',
            'data' => json_decode($response->body()),
        ], 200);
    } catch (\Exception $e) {
        Log::error("Error in checking transaction: {$e->getMessage()}");
        return response([
            'message' => 'An error occurred while processing the check Transaction.',
            'data' => null,
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
    // $accessToken = Cache::get(self::ACCESS_TOKEN_CACHE_KEY);
    // if ($accessToken !== null) {
    //     return $accessToken;
    // }

    try {
        $bankilyToken =  BankilyToken::findOrFail(1);

        $accessToken = $bankilyToken->acces_token;
        Cache::put(self::ACCESS_TOKEN_CACHE_KEY, $accessToken, self::ACCESS_TOKEN_CACHE_EXPIRATION_TIME);
        return $accessToken;
    } catch (Throwable $e) {
        Log::error("Bankily token with ID 1 not found: {$e->getMessage()}");
        return '';
    }
}


/**
 * Save transaction details to database.
 *
 * @param  array  $requestPayload
 * @param  object  $responseData
 * @return void
 */
private function saveTransactionDetails(array $requestPayload, object $responseData): void
{
    try {
        $transaction = new Transaction([
            'clientPhone' => $requestPayload['clientPhone'],
            'merchant_reference' => $requestPayload['operationId'],
            'amount' => $requestPayload['amount'],
            'errorCode' => $responseData->errorCode,
            'errorMessage' => $responseData->errorMessage,
            'transactionId' => $responseData->transactionId,
            'order_id' => $requestPayload['order_id'],
        ]);
        
        $transaction->save();
    
        if ($responseData->errorCode === 0) {
            Order::find($requestPayload['order_id'])
                 ->update(['payment_status' => 'paid', 'payment_method' => 'bankily']);
        }
        
    } catch (\Throwable $e) {
        Log::error("Error in saving transaction details: {$e->getMessage()}");
    }
}

 
}