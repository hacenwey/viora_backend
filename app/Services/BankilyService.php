<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\BankilyToken;
use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;
use App\Models\Order;
use Illuminate\Support\Facades\Artisan;


class BankilyService {
    const ACCESS_TOKEN_CACHE_KEY = 'bankily_access_token';
    const CONTENT_TYPE = 'application/json';
    /**
 * Process payment transaction.
 *
 * @param  array  $requestPayload
 * @return \Illuminate\Http\Response
 */
static function processPayment($requestPayload)
{
  
    $i = 0;

    try {
        $response = self::contactBnakily($requestPayload);
        
        // Retry the request if the error code is 1
        while ($i <= 3 && $response->errorCode == 1) {
            Artisan::call('initialToken:save');
            $response = self::contactBnakily($requestPayload);
            $i++;
        }
        
        // Payment success
        self::saveTransactionDetails($requestPayload, $response);
        return response([
            'message' => $response->errorMessage,
            'data' => $response,
        ], 200);
    } catch (\Exception $e) {
        Log::error("Error in payment transaction: {$e->getMessage()}");
        Log::error("Error in payment transaction status error code : {$response->errorCode}");
        Log::error("Error in payment transaction request payload : ".var_export($requestPayload,1));

        return response([
            'message' => 'An error occurred while processing the payment.',
            'data' => null,
        ], 500);
    }
}

private static function contactBnakily($requestPayload)
{
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . self::getBankilyAccessToken(),
        'Content-Type' => self::CONTENT_TYPE,
    ])->post(env('BANKILY_BASE_URL') . 'payment', $requestPayload);

    return json_decode($response->body());
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
    $accessToken = Cache::get(self::ACCESS_TOKEN_CACHE_KEY);
    if ($accessToken !== null) {
        return $accessToken;
    }

    try {
        $bankilyToken =  BankilyToken::findOrFail(1);

        $accessToken = $bankilyToken->acces_token;
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