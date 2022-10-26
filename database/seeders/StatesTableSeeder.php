<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('states')->insert(
            [

                [

                    'name' => 'Hodh El Gharbi'

                ],
                [

                    'name' => 'Dakhlet_Nouadhibou'

                ],
                [

                    'name' => 'La Saba'

                ],

                [

                    'name' => 'Adrar'

                ],
                [

                    'name' => 'Brakna'

                ],
                [

                    'name' => 'Gorgol'

                ],
                [

                    'name' => 'Guidimaka'

                ],
                [

                    'name' => 'Inchiri'

                ],
                [

                    'name' => 'Tagant'

                ],
                [

                    'name' => 'tiris zemmour'

                ],
                [

                    'name' => 'Trarza'

                ],
                [

                    'name' => 'Hodh Ech Chargui'

                ],
                [

                    'name' => 'Nouakchott-Nord '

                ],
                [

                    'name' => 'Nouakchott-Ouest'

                ],
                [

                    'name' => 'Nouakchott-Sud'

                ],






            ]
        );


        DB::table('provinces')->insert(
            [


                [
                    'name' => 'Nouadhibou',
                    'state_id' => '2'

                ],
                // [
                //     'name' => 'Chammi',
                //     'state_id' => '2'

                // ],
                [
                    'name' => 'Dar naim',
                    'state_id' => '13'

                ],
                // [
                //     'name' => 'Tayar ',
                //     'state_id' => '13'

                // ],
                [
                    'name' => 'Toujounine',
                    'state_id' => '13'

                ],
                [
                    'name' => 'Tevragh zeina ',
                    'state_id' => '14'

                ],
                // [
                //     'name' => 'Lksar',
                //     'state_id' => '14'

                // ],
                // [
                //     'name' => 'Sabkha',
                //     'state_id' => '14'

                // ],


                [
                    'name' => 'Arafat',
                    'state_id' => '15'

                ],
                // [
                //     'name' => 'EL Mina',
                //     'state_id' => '15'

                // ],
                // [
                //     'name' => 'Riyad',
                //     'state_id' => '15'

                // ],










            ]
        );
    }
}
