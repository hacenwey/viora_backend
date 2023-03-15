<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Http\Client\ConnectionException;
use Log;
use App\Models\BankilyToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

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

                $token = Cache::get('bankily_token');

                    if(!$token){
                        $response = Http::asForm()->post(env('BANKILY_BASE_URL') . 'authentification', [
                            'grant_type' => env('GRANT_TYPE'),
                            'username' => env('USERNAME'),
                            'password' => env('PASSWORD'),
                            'client_id' => env('CLIENT_ID')
                        ], [
                            'Content-Type' => 'application/x-www-form-urlencoded'
                        ]);
                
                        
                        $data = json_decode($response->body());
                        $expiresIn = $data->expires_in;
                        Cache::put('bankily_token', $token, now()->addSeconds($expiresIn));
                        BankilyToken::updateOrCreate(['id' => 1],['acces_token' => $data->access_token,'expires_in' => $data->expires_in,'refresh_token' => $data->refresh_token,'refresh_expires_in' => $data->refresh_expires_in]);
                    }elseif(Carbon::now()->gte($bankilyToken->expires_in)){
                        $response = Http::asForm()->post(env('BANKILY_BASE_URL') . 'authentification', [
                            'grant_type' => env('GRANT_TYPE'),
                            'refresh_token' => $bankilyToken->refresh_token,
                            'client_id' => env('CLIENT_ID')
                        ], [
                            'Content-Type' => 'application/x-www-form-urlencoded'
                        ]);
                        
                        $data = json_decode($response->body());
                        BankilyToken::updateOrCreate(['id' => 1],['acces_token' => $data->access_token,'expires_in' => $data->expires_in,'refresh_token' => $data->refresh_token,'refresh_expires_in' => $data->refresh_expires_in]);
                    }
                
            })->everyMinute();
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
