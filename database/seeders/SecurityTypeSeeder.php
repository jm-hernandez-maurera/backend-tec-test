<?php

namespace Database\Seeders;

use App\Models\SecurityType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SecurityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [];

        foreach (config('app.security_types_names') as $securityType){
            $types[] = [
                'name' => $securityType,
                'slug' => Str::slug($securityType, '_')
            ];
        }
        DB::table('security_types')->insert($types);
    }
}
