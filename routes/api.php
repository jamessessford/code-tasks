<?php

declare(strict_types=1);

use App\Http\Controllers\StoresNearPostcodeController;
use App\Http\Controllers\StoresThatDeliverToPostcodeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function (): void {
    Route::get('stores-near-postcode/{latitude}/{longitude}/{distance}', StoresNearPostcodeController::class)->name('stores-near-postcode');
    Route::get('stores-that-deliver-to-postcode/{postcode}', StoresThatDeliverToPostcodeController::class)->name('stores-that-deliver-to-postcode');
});
