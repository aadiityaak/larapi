<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\JobdeskController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\ProfileController;


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('profile', function (Request $request) {
        return $request->user();
    });
    Route::get('user', [UserController::class, 'index']);
    Route::post('jobdesk-reminder', [NotificationController::class, 'sendJobdeskReminder']);
    Route::put('profile', [ProfileController::class, 'update']);
    Route::get('home', [DashboardController::class, 'index']);
    Route::apiResources([
        'karyawans' => KaryawanController::class,
        'orders' => OrderController::class,
        'jobdesks' => JobdeskController::class,
        'customers' => CustomerController::class,
        'settings' => SettingController::class,
        'products' => ProductController::class,
        'data' => DataController::class
    ]);
});
