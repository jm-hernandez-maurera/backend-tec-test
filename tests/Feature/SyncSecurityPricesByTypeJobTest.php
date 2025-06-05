<?php

namespace Tests\Feature;

use App\Jobs\SyncSecurityPricesByTypeJob;
use App\Jobs\UpdateSecurityPricesJob;
use App\Models\SecurityType;
use App\Services\SyncSecurityPricesService;
use Database\Factories\tests\ExternalPricesFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class SyncSecurityPricesByTypeJobTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_job_should_batch_each_group_of_prices(): void
    {
        $securityType = SecurityType::factory()->create();
        $countOfPrices = 500;
        Bus::fake();

        $this->mock(SyncSecurityPricesService::class, function ($mock) use ($countOfPrices){
            $mock->shouldReceive('getPricesToSync')->once()->andReturn(
                (new ExternalPricesFactory())->makeMany($countOfPrices)
            );
        });

        dispatch(new SyncSecurityPricesByTypeJob($securityType));
        (new SyncSecurityPricesByTypeJob($securityType))->handle(app(SyncSecurityPricesService::class));

        $expectedCount = ceil($countOfPrices / config('app.external_prices_chunk'));

        Bus::assertDispatched(SyncSecurityPricesByTypeJob::class);

        $dispatchedJob = Bus::dispatched(UpdateSecurityPricesJob::class)->first();
        $this->assertCount($expectedCount-1, $dispatchedJob->chained);

    }
}
