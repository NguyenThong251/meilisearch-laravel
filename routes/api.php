<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::post('/products', [ProductController::class, 'store']);
Route::get('/products/search', [ProductController::class, 'search']);
