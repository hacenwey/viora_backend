<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TokenService;

class initialToken extends Command
{
   
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
        TokenService::initialToken();
    }
}
