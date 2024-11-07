<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Auth\ProfileController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('profile', function (Request $request) {
        return $request->user();
    });
    Route::put('profile', [ProfileController::class, 'update']);

    Route::apiResources([
        'karyawans' => KaryawanController::class,
        'orders' => OrderController::class,
        'customers' => CustomerController::class
    ]);
});
