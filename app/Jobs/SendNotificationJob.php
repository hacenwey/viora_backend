<?php

namespace App\Jobs;

use App\Services\FirebaseNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationJob implements ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $title;
    public $message;
    public $photo;
    public $productId;
    public $tokens;

    public function __construct($title, $message, $photo, $productId, $tokens)
    {
        $this->title = $title;
        $this->message = $message;
        $this->photo = $photo;
        $this->productId = $productId;
        $this->tokens = $tokens;
    }

    public function handle()
    {
        $chunks = collect(array_chunk($this->tokens, 500));
        foreach ($chunks as $chunk) {
            $payload = [
                'title' => $this->title,
                'message' => $this->message,
                'photo' => $this->photo,
                'productId' => $this->productId,
                'tokens' => $chunk,
            ];
            \Log::info('Sending the next chunk of notifications => time: ' . now());
            \Log::info('Sending the next chunk of notifications => token count: ' . count($chunk));

            try {
                FirebaseNotificationService::sendNotificationFirebase(
                    $payload['title'],
                    $payload['message'],
                    $payload['photo'],
                    $payload['productId'],
                    $payload['tokens']
                );
                \Log::info('Scheduled notification sent successfully AT: ' . now());
            } catch (\Exception $e) {
                \Log::error('Error sending scheduled notification: ' . $e->getMessage());
            }

            // Wait for 30 secondes before sending the next chunk of notifications
            sleep(30);
        }
    }

    public function chunkTokens($tokens)
    {
        return collect(array_chunk($tokens, 500));
    }
}
