<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Currency::insert([
            array( 'code' => 'AED', 'name' => 'United Arab Emirates dirham', 'status' => 0 ,'toux_change'=> '37'),
            array( 'code' => 'AFN', 'name' => 'Afghan afghani', 'status' => 0 ,'toux_change'=> '37'),
            array( 'code' => 'ALL', 'name' => 'Albanian Lek', 'status' => 0 ,'toux_change'=> '37'),
            array( 'code' => 'AMD', 'name' => 'Armenian Dram', 'status' => 0 ,'toux_change'=> '37'),
            array( 'code' => 'ANG', 'name' => 'Netherlands Antillean gulden', 'status' => 0 ,'toux_change'=> '37'),
            array( 'code' => 'AOA', 'name' => 'Angolan Kwanza', 'status' => 0 ,'toux_change'=> '37'),
            array( 'code' => 'ARS', 'name' => 'Argentine Peso', 'status' => 0 ,'toux_change'=> '37'),
            array( 'code' => 'AUD', 'name' => 'Australian Dollar', 'status' => 0 ,'toux_change'=> '37'),
        ]);
    }
}
