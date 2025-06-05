<?php

namespace Tests\Feature;

use App\Jobs\SyncSecurityTypesJob;
use App\Models\SecurityType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Bus\PendingBatch;
use App\Services\SecurityTypeService;

class SyncSecurityTypesJobTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_job_should_batch_each_security_type(): void
    {
        Bus::fake();

        SecurityType::factory()
            ->has(\App\Models\Security::factory()->count(100))
            ->count(1000)
            ->create();

        dispatch(new SyncSecurityTypesJob());
        (new SyncSecurityTypesJob())->handle(app(SecurityTypeService::class));

        Bus::assertDispatched(SyncSecurityTypesJob::class);

        Bus::assertBatched(function (PendingBatch $batch) {
            return $batch->name == 'SyncSecurityPricesByType' &&
                $batch->jobs->count() === 1000;
        });
    }
}
