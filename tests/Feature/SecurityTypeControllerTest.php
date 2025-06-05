<?php

namespace Tests\Feature;

use App\Jobs\SyncSecurityPricesByTypeJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Support\Facades\Bus;

class SecurityTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_not_sync_prices_from_not_found_security_type(): void
    {
        $id = 0;
        $response = $this->postJson("/api/security_types/{$id}/sync-prices");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_should_dispatch_sync_prices_by_security_type_succesfully(): void
    {
        $this->createSecurityTypes();

        Bus::fake();

        $response = $this->postJson('/api/security_types/1/sync-prices');
        $response->assertStatus(Response::HTTP_OK);
        Bus::assertDispatched(SyncSecurityPricesByTypeJob::class);

    }
}
