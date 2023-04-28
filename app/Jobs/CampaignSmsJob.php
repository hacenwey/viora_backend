<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\SendSmsJob;

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
    public function __construct($to_be_notified,$message)
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
                'message' => $this->message
            ];
    
            try {
                SendSmsJob::dispatch($payload);
                \Log::info('Scheduled SMS sent successfully');
            } catch (\Exception $e) {
                \Log::error('Error sending scheduled SMS: ' . $e->getMessage());
            }
    
            // Wait for 10 minutes before sending the next chunk of SMS
            sleep(600);
        }
    }


   static  function chunkPhoneNumbers($phone_numbers)
    {
        $chunks = array_chunk($phone_numbers, 100);
        return collect($chunks);
    }
}
