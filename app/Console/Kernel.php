<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Http\Client\ConnectionException;
use Log;
use App\Models\BankilyToken;
use Illuminate\Support\Facades\Http;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\CartNotification::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('cart:notify')->everyMinute();
        try {
            $schedule->call(function () {
                $response = Http::asForm()->post(env('BANKILY_BASE_URL') . 'authentification', [
                    'grant_type' => env('GRANT_TYPE'),
                    'username' => env('USERNAME'),
                    'password' => env('PASSWORD'),
                    'client_id' => env('CLIENT_ID')
                ], [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ]);
                
                $data = json_decode($response->body());
                $token = $data->access_token;
                BankilyToken::updateOrCreate(['id' => 1],['acces_token' => $token]);
            })->hourly();
        } catch (ConnectionException $e) {
            Log::error('error: A problem occurred in the Bankily access token service: ' . $e->getMessage());
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
