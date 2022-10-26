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
                    'state_id' => '2'

                ],
                [
                    'name' => 'Chammi',
                    'state_id' => '2'

                ],
                [
                    'name' => 'Dar naim',
                    'state_id' => '13'

                ],
                [
                    'name' => 'Tayar ',
                    'state_id' => '13'

                ],
                [
                    'name' => 'Toujounine',
                    'state_id' => '13'

                ],
                [
                    'name' => 'Tevragh zeina ',
                    'state_id' => '14'

                ],
                [
                    'name' => 'Lksar',
                    'state_id' => '14'

                ],
                [
                    'name' => 'Sabkha',
                    'state_id' => '14'

                ],


                [
                    'name' => 'Arafat',
                    'state_id' => '15'

                ],
                [
                    'name' => 'EL Mina',
                    'state_id' => '15'

                ],
                [
                    'name' => 'Riyad',
                    'state_id' => '15'

                ],










            ]
        );
    }
}
