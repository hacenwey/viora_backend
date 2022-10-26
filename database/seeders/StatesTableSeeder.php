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

    }
}
