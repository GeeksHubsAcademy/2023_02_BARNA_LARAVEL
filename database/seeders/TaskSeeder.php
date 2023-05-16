<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tasks')->insert(
            [
                [
                    'title' => 'Comprar',
                    'description' => 'pan, patatas, huevos y jamon',
                    'status' => 0,
                    'user_id' => 1
                ],
                [
                    'title' => 'Comprar',
                    'description' => 'pan, patatas, huevos y jamon',
                    'status' => 0,
                    'user_id' => 3
                ],
                [
                    'title' => 'Comprar',
                    'description' => 'pan, patatas, huevos y jamon',
                    'status' => 0,
                    'user_id' => 2
                ],
            ]
        );

        DB::table('tasks')->insert(
            [
                [
                    'title' => 'Comprar',
                    'description' => 'pan, patatas, huevos y jamon',
                    'status' => 0,
                    'user_id' => 1
                ],
            ]
        );       
    }    
}
