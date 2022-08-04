<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CustomerAddressType;

class CustomerAddressTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CustomerAddressType::firstOrCreate([
            'name' => 'Billing'
        ]);
        CustomerAddressType::firstOrCreate([
            'name' => 'Shipping'
        ]);
    }
}
