<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\JobdeskController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\ProfileController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('profile', function (Request $request) {
        return $request->user();
    });
    Route::put('profile', [ProfileController::class, 'update']);

    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::apiResources([
        'karyawans' => KaryawanController::class,
        'orders' => OrderController::class,
        'jobdesks' => JobdeskController::class,
        'customers' => CustomerController::class
    ]);
});
