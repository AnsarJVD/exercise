<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtworkController;

Route::post('/exercise-1-artwork-version', [ArtworkController::class, 'store']);
Route::post('/exercise-3-cart-validator', [ArtworkController::class, 'ex3']);
Route::post('/exercise-4-vendor-allocation', [ArtworkController::class, 'ex4']);
Route::post('/exercise-5-discount', [ArtworkController::class, 'ex5']);
Route::post('/exercise-6-approval-flow', [ArtworkController::class, 'ex6']);

Route::post('/exercise-9−webhook', [ArtworkController::class, 'ex9']);
Route::post('/exercise-12-bundle-pricing', [ArtworkController::class, 'ex12']);
Route::post('/exercise-13-cart-merge', [ArtworkController::class, 'ex13']);
Route::post('/exercise-14-upsell', [ArtworkController::class, 'ex14']);
Route::post('/exercise-15-shipping-rule', [ArtworkController::class, 'ex15']);


