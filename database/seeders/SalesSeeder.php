<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SalesSeeder extends Seeder
{
    public function run(): void
    {
        // Assume product_id = 1 exists
        $productId = 1;
        $unitPrice = 200.00;
        $quantity = 10;
        $discount = 50.00;

        $totalBeforeDiscount = $unitPrice * $quantity; // 2000
        $subtotal = $totalBeforeDiscount - $discount;  // 1950
        $vatRate = 0.05;
        $vatAmount = $subtotal * $vatRate;             // 97.5
        $totalPayable = $subtotal + $vatAmount;        // 2047.5

        $amountPaid = 1000.00;
        $dueAmount = $totalPayable - $amountPaid;      // 1047.5

        // Insert into sales table
        $saleId = DB::table('sales')->insertGetId([
            'customer_name' => 'Test Customer',
            'discount' => $discount,
            'vat' => $vatAmount,
            'total' => $totalPayable,
            'paid' => $amountPaid,
            'due' => $dueAmount,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Insert into sale_items table (if you have one)
        DB::table('sale_items')->insert([
            'sale_id' => $saleId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total' => $unitPrice * $quantity,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
