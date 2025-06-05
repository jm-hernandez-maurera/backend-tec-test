<?php

namespace Tests;

use App\Services\SecurityTypeService;
use Database\Seeders\SecurityPriceSeeder;
use Database\Seeders\SecuritySeeder;
use Database\Seeders\SecurityTypeSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function createSecurityTypes()
    {
        $this->seed(SecurityTypeSeeder::class);
    }

    public function createSecurities()
    {
        $this->seed(SecuritySeeder::class);
    }

    public function createSecurityPrices()
    {
        $this->seed(SecurityPriceSeeder::class);
    }
}
