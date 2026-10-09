<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HousePriceController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HousePriceController::class, 'index'])
    ->name('house-price.index');

Route::post('/predict', [HousePriceController::class, 'predict'])
    ->name('house-price.predict');
