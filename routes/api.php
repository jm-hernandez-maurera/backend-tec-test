<?php

use Illuminate\Support\Facades\Route;

Route::post('/security_types/{securityType}/sync-prices', [\App\Http\Controllers\SecurityTypeController::class, 'syncAndUpdatePrices']);

