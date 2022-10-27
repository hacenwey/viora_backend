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

                    'name' => 'Nouakchott-Nord '

                ],
                [

                    'name' => 'Nouakchott-Ouest'

                ],
                [

                    'name' => 'Nouakchott-Sud'

                ],


                [

                    'name' => 'Dakhlet_Nouadhibou'

                ],
                [

                    'name' => 'Hodh El Gharbi'

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








            ]
        );

    }
}
