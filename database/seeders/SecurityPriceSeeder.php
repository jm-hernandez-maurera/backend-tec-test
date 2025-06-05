<?php

namespace Database\Seeders;

use App\Models\SecurityPrice;
use App\Models\SecurityType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SecurityPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $securityTypes = SecurityType::with(['securities'])->get();
        foreach ($securityTypes as $securityType) {
            foreach ($securityType->securities as $security) {
                SecurityPrice::factory()->for($security)->create();
            }
        }
    }
}
