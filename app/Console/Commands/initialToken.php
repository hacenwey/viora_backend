<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use App\Models\BankilyToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
class initialToken extends Command
{
    const ACCESS_TOKEN_CACHE_KEY = 'bankily_access_token';
    const ACCESS_TOKEN_CACHE_EXPIRATION_TIME = 30;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initialToken:save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save initial access token for Bankily API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $response = Http::asForm()->post(env('BANKILY_BASE_URL') . 'authentification', [
                'grant_type' => env('GRANT_TYPE'),
                'username' => env('USERNAME'),
                'password' => env('PASSWORD'),
                'client_id' => env('CLIENT_ID')
            ], [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ]);
            $data = json_decode($response->body());
            BankilyToken::updateOrCreate(['id' => 1],['acces_token' => $data->access_token,'expires_in' => $data->expires_in,'refresh_token' => $data->refresh_token,'refresh_expires_in' => $data->refresh_expires_in]);
            Cache::put(self::ACCESS_TOKEN_CACHE_KEY, $accessToken, self::ACCESS_TOKEN_CACHE_EXPIRATION_TIME);

            $this->info('Token saved successfully.');

        } catch (\Exception $e) {
            $this->error('Error occurred while getting the token: ' . $e->getMessage());
            Log::error('Error occurred while getting the token: ' . $e->getMessage());

        }
    }
}
