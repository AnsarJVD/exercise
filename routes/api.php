<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtworkController;

Route::post('/exercise-1−artwork-version', [ArtworkController::class, 'store']);
Route::post('/exercise-3−cart-validator', [ArtworkController::class, 'ex3']);
Route::post('/exercise-4−vendor-allocation', [ArtworkController::class, 'ex4']);
Route::post('/exercise-5−discount', [ArtworkController::class, 'ex5']);