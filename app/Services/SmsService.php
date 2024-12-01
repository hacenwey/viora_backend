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
                'token' => 't4#dkjgKdgjg(YYYYFYY82227',
            ])->post(env('SMS_SERVICE_URL'), $payload);
    
            Log::info('request payload =====> : ' . var_export($payload,1));
            Log::info('status of request send sms =====> : ' . $response->status());              
        } catch (\Exception $e) {
            Log::error('error in service send sms this is the status of request ===> : ' . $response->status());              
            Log::error('error in service send sms this is the payload ======> : ' . var_export($payload,1));              
            Log::error('error in service send sms this is the Exception Message ======> : ' . $e->getMessage());              
        }
    }


    static function sendSmsEdgeGateway($payload)
    { 
        $recipientString = is_array($payload['phone_numbers']) ? implode(',', $payload['phone_numbers']) : $payload['phone_numbers'];

         \Log::Info('recipient string ======> : ' . $recipientString);
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.config('sms_config.api_key'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post(config('sms_config.base_url'), [
            'recipient' => $recipientString,
            'sender_id' => config('sms_config.sender_id'),
            'type' => config('sms_config.type'),
            'message' => $payload['message'],
        ]);
        \Log::Info('response ======> : ' . var_export($response->body(),1));
        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'data' => $response->json(),
            ] , $response->status());
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send SMS',
                'error' => $response->body(),
            ], $response->status());
        }
    }
}

