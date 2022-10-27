<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('provinces')->insert(
            [

                [
                    'name' => 'Nouadhibou',
                    'state_id' => '4'

                ],
                [
                    'name' => 'Chammi',
                    'state_id' => '4'

                ],
                [
                    'name' => 'Dar naim',
                    'state_id' => '1'

                ],
                [
                    'name' => 'Tayar ',
                    'state_id' => '1'

                ],
                [
                    'name' => 'Toujounine',
                    'state_id' => '1'

                ],
                [
                    'name' => 'Tevragh zeina ',
                    'state_id' => '2'

                ],
                [
                    'name' => 'Lksar',
                    'state_id' => '2'

                ],
                [
                    'name' => 'Sabkha',
                    'state_id' => '2'

                ],


                [
                    'name' => 'Arafat',
                    'state_id' => '3'

                ],
                [
                    'name' => 'EL Mina',
                    'state_id' => '3'

                ],
                [
                    'name' => 'Riyad',
                    'state_id' => '3'

                ],
                [
                    'name' => 'Aleg',
                    'state_id' => '8'

                ],
                [
                    'name' => 'Bababe',
                    'state_id' => '8'

                ],
                [
                    'name' => 'Magta-Lahjar',
                    'state_id' => '8'

                ],
                [
                    'name' => 'Bogué',
                    'state_id' => '8'

                ],
                [
                    'name' => 'Ayoun al_Atrous',
                    'state_id' => '5'

                ],
                [
                    'name' => 'Tintane',
                    'state_id' => '5'

                ],
                [
                    'name' => 'Kobeni',
                    'state_id' => '5'

                ],
                [
                    'name' => 'Tamchakett',
                    'state_id' => '5'

                ],

            ]
        );
    }
}
