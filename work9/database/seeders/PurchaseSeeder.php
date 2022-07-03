<?php

namespace Database\Seeders;

use Carbon\Factory;
use Doctrine\DBAL\Driver\IBMDB2\Exception\Factory as ExceptionFactory;
use Doctrine\DBAL\Types\IntegerType;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 1000; $i++){
            DB::table('purchases')->insert([
                'warehouse_ID' => 1234567,
                'warehouse_name' => Str::random(10),
                'total_cost' => 1234567,
                'total_price' => 1234567,
                'total_USD' => 1234567,
                'user_ID' => 1234567,
                'user_name' => Str::random(10),
                'status' => Str::random(10),
                'created_at' => '2022-02-25 11:24:46',
                'updated_at' => '2022-02-25 11:24:46',
    
            ]);
        }


        
    }
}
