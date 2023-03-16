<?php
namespace App\Services;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;


class SmsService {

    /**
     * Send an SMS message using the specified payload.
     *
     * @param array $payload The payload to send in the request.
     * @return void
     */
    static function sendSms($payload)
    {
        # TODO log sms.
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post(env('SMS_SERVICE_URL'), $payload);
    
            Log::info('request payload =====> : ' . var_export($payload,1));
            Log::info('status of request send sms =====> : ' . $response->status());              
        } catch (\Exception $e) {
            Log::error('error in service send sms this is the status of request ===> : ' . $response->status());              
            Log::error('error in service send sms this is the payload ======> : ' . var_export($payload,1));              
            Log::error('error in service send sms this is the Exception Message ======> : ' . $e->getMessage());              
        }
    }


}

