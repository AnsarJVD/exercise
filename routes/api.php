<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtworkController;

Route::post('/user', [ArtworkController::class, 'store']);
Route::post('/ex3', [ArtworkController::class, 'ex3']);
Route::post('/ex4', [ArtworkController::class, 'ex4']);