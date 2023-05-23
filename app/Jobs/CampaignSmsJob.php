<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CampaignSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $to_be_notified;
    public $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($to_be_notified, $message)
    {
        $this->to_be_notified = $to_be_notified;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $chunks = self::chunkPhoneNumbers($this->to_be_notified);

        foreach ($chunks as $chunk) {
            $payload = [
                'phone_numbers' => $chunk,
                'message' => $this->message,
            ];
            \Log::info('sending the next chunk of SMS => time  : ' . Carbon::now());
            \Log::info('sending the next chunk of SMS => phone number count : ' . count($chunk) . ' phone number liste => :   ' . var_export($chunk, 1));

            try {
                SendSmsJob::dispatch($payload);
                \Log::info('Scheduled SMS sent successfully AT : ' . Carbon::now());
            } catch (\Exception $e) {
                \Log::error('Error sending scheduled SMS: ' . $e->getMessage());
            }

            // Wait for 10 minutes before sending the next chunk of SMS
            sleep(600);
        }
    }

    public static function chunkPhoneNumbers($phone_numbers)
    {
        $chunks = array_chunk($phone_numbers, 100);
        return collect($chunks);
    }
}
