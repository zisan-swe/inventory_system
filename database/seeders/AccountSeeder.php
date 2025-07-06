<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    public function run()
    {
        $accounts = [
            ['name' => 'Cash', 'type' => 'asset'],
            ['name' => 'Accounts Receivable', 'type' => 'asset'],
            ['name' => 'Inventory', 'type' => 'asset'],
            ['name' => 'Sales', 'type' => 'income'],
            ['name' => 'VAT Payable', 'type' => 'liability'],
            ['name' => 'Discount Allowed', 'type' => 'expense'],
        ];

        foreach ($accounts as $account) {
            Account::create($account);
        }
    }
}