<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Database\Factories\tests\ExternalPricesFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;
use Mockery;
use App\Services\SecurityService;
use App\Services\SecurityPriceService;
use App\Services\SyncSecurityPricesService;
use App\Models\SecurityType;
use App\Models\Security;
use Illuminate\Support\Collection;

class SyncSecurityPricesServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $syncService;
    protected $prices;
    protected $securityType;

    public function setUp(): void
    {
        parent::setUp();
        $this->createSecurityTypes();
        $this->createSecurities();

        $securityService = app(SecurityService::class);
        $securityPriceService = app(SecurityPriceService::class);
        // Instantiate the SyncSecurityPricesService
        $this->syncService = new SyncSecurityPricesService($securityService, $securityPriceService);

        // Get a Valid Security Type By Name
        $name = config('app.security_types_names')[0];
        $this->securityType = SecurityType::where('name', $name)->first();

        // Create Fake External Prices for a given Security Type
        $this->prices = (new ExternalPricesFactory())->makeManyByType(typeName: $this->securityType->name);

    }

    public function test_sync_security_prices_by_type_should_create_new_security_prices()
    {
        // Sync External prices with current securities
        $this->syncService->syncSecurityPricesByType($this->securityType, $this->prices);

        // Get expected securities with new prices
        $names = Arr::pluck($this->prices, 'symbol');
        $securities = Security::with(['securityPrices'])
            ->where('security_type_id', $this->securityType->id)
            ->whereIn('symbol', $names)
            ->get();

        // Assertions to verify database changes
        foreach ($securities as $security) {
            $this->assertDatabaseHas('security_prices', [
                'security_id' => $security->id,
                'last_price' => collect($this->prices)->firstWhere('symbol', $security->symbol)['price']
            ]);
        }
    }

    public function test_sync_security_prices_by_type_should_update_existing_security_prices()
    {
        $this->createSecurityPrices();

        // Get expected securities
        $names = Arr::pluck($this->prices, 'symbol');
        $securities = Security::with(['securityPrices'])
            ->where('security_type_id', $this->securityType->id)
            ->whereIn('symbol', $names)
            ->get();

        // Sync External prices with current securities
        $this->syncService->syncSecurityPricesByType($this->securityType, $this->prices);

        foreach ($this->prices as $price) {
            $security = $securities->where('symbol', $price["symbol"])->first();
            $securityPrice = $security->securityPrices->last();

            $date = Carbon::createFromFormat("Y-m-d\TH:i:sP", $price['last_price_datetime'], '-04:00');

            if ($securityPrice->as_of_date->lessThan($date)){
                $now = $date->copy()->setTimezone('UTC')->format('Y-m-d H:i:s');
                $this->assertDatabaseHas('security_prices', [
                    'security_id' => $security->id,
                    'last_price' => $price['price'],
                    'as_of_date'    => $now
                ]);
            }else {
                $this->assertDatabaseHas('security_prices', [
                    'security_id' => $security->id,
                    'last_price' => $securityPrice->last_price,
                    'as_of_date'    => $securityPrice->as_of_date
                ]);
            }
        }
    }

}
