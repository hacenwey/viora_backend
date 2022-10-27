<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('states')->where('name', 'Dakhlet_Nouadhibou')->update(['name' => 'Dakhlet Nouadhibou']);
    }
}
