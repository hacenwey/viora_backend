
<?php

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class NotificationService
{
    static function sendNotification($token, $message)
    {
        

        $SERVER_API_KEY = "";
        $data = [
            "registration_ids" => [
                $token
            ],
            "notification" => [
                "title" => $title,
                "body" => $message,
                "sound" => "default" // required for sound on ios
            ],
        ];

        $url = "https://fcm.googleapis.com/fcm/send";
        $request = Http::withHeaders([
            'Authorization' => 'key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ])->withOptions(
            [
                'verify' => false
            ]
        )->post($url, $data);
        Log::info($request);
        return $request->status();
    }
}


