<?php

namespace Database\Seeders;

use App\Models\SecurityType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SecuritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $securities = [];

        foreach (config('app.security_types_names') as $securityType){
            $symbols = config('app.securities_symbols')[$securityType];
            $type = SecurityType::where('name', $securityType)->first();
            foreach ($symbols as $symbol) {
                $securities[] = [
                    'symbol' => $symbol,
                    'security_type_id' => $type->id
                ];
            }
        }
        DB::table('securities')->insert($securities);
    }
}
