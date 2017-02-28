<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customer_status')->insert([
            'id' => '1',
            'status' => 'Новый',
        ]);
        DB::table('customer_status')->insert([
            'id' => '2',
            'status' => 'Зарегистрирован',
        ]);
        DB::table('customer_status')->insert([
            'id' => '3',
            'status' => 'Отказался',
        ]);
        DB::table('customer_status')->insert([
            'id' => '4',
            'status' => 'Недоступен',
        ]);
    }
}
