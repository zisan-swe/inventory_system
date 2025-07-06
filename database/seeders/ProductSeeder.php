<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Product 01',
                'code' => '001',
                'purchase_price' => 100.00,
                'sell_price' => 200.00,
                'opening_stock' => 50,
                'current_stock' => 40,
                'created_at' => Carbon::now()->subDay(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
