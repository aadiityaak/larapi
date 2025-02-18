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
use App\Http\Controllers\MetaController;
use App\Http\Controllers\SendNotificationController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\BankSettingController;


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('profile', function (Request $request) {
        return $request->user();
    });
    Route::get('user', [UserController::class, 'index']);
    Route::post('jobdesk-reminder', [SendNotificationController::class, 'sendJobdeskReminder']);
    Route::put('profile', [ProfileController::class, 'update']);
    Route::get('home', [DashboardController::class, 'index']);

    Route::get('notifications', [NotificationController::class, 'index']);
    Route::put('notifications/{id}', [NotificationController::class, 'update']);
    Route::put('notifications/read/{id}', [NotificationController::class, 'read']);
    Route::put('notifications/read-all', [NotificationController::class, 'readAll']);
    Route::get('notifications/unread', [NotificationController::class, 'unread']);

    Route::get('/permissions', [PermissionController::class, 'index']);
    Route::post('/user/{user}/permissions', [PermissionController::class, 'update']);

    Route::get('/settings/banks', [BankSettingController::class, 'index']);
    Route::post('/settings/banks', [BankSettingController::class, 'store']);
    Route::delete('/settings/banks', [BankSettingController::class, 'destroy']);

    Route::post('customers/{customer}/meta', [CustomerController::class, 'storeMeta']);

    Route::apiResources([
        'karyawans' => KaryawanController::class,
        'orders' => OrderController::class,
        'jobdesks' => JobdeskController::class,
        'customers' => CustomerController::class,
        'settings' => SettingController::class,
        'products' => ProductController::class,
        'metas' => MetaController::class
    ]);
});
