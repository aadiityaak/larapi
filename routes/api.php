<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    KaryawanController,
    OrderController,
    JobdeskController,
    CustomerController,
    DashboardController,
    SettingController,
    UserController,
    ProductController,
    MetaController,
    SendNotificationController,
    NotificationController,
    PermissionController,
    SettingBankController,
    SettingLoginController,
    SettingFaviconController,
    PostController,
    CategoryController,
    RoleController,
    Auth\ProfileController
};
use Spatie\Permission\Models\Permission;

// Routes tanpa middleware
Route::get('/settings/background', [SettingLoginController::class, 'index']);
Route::get('/settings/favicon', [SettingFaviconController::class, 'index']);
Route::get('/settings', [SettingController::class, 'index']);

// Routes dengan middleware 'auth:sanctum'
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('profile', fn(Request $request) => $request->user());
    Route::get('user', [UserController::class, 'index']);
    Route::put('profile', [ProfileController::class, 'update']);
    Route::get('home', [DashboardController::class, 'index']);
    Route::post('jobdesk-reminder', [SendNotificationController::class, 'sendJobdeskReminder']);

    // Notifications
    Route::controller(NotificationController::class)->group(function () {
        Route::get('notifications', 'index');
        Route::put('notifications/{id}', 'update');
        Route::put('notifications/read/{id}', 'read');
        Route::put('notifications/read-all', 'readAll');
        Route::get('notifications/unread', 'unread');
    });

    // Permissions
    Route::get('/permissions', [PermissionController::class, 'index']);
    Route::post('/user/{user}/permissions', [PermissionController::class, 'update']);

    Route::get('/capabilities', function () {
        return Permission::select('id', 'name')->get();
    });

    // Settings
    Route::prefix('settings')->group(function () {
        Route::controller(SettingLoginController::class)->group(function () {
            Route::post('/background', 'store');
            Route::delete('/background', 'destroy');
        });

        Route::controller(SettingFaviconController::class)->group(function () {
            Route::post('/favicon', 'store');
            Route::delete('/favicon', 'destroy');
        });

        Route::controller(SettingBankController::class)->group(function () {
            Route::get('/banks', 'index');
            Route::post('/banks', 'store');
            Route::delete('/banks', 'destroy');
        });
    });

    Route::post('customers/{customer}/meta', [CustomerController::class, 'storeMeta']);

    // Order stats endpoint
    Route::get('orders/stats', [OrderController::class, 'stats']);

    // Jobdesk stats endpoint
    Route::get('jobdesks/stats', [JobdeskController::class, 'stats']);

    // API Resources
    Route::apiResources([
        'posts' => PostController::class,
        'categories' => CategoryController::class,
        'karyawans' => KaryawanController::class,
        'orders' => OrderController::class,
        'jobdesks' => JobdeskController::class,
        'customers' => CustomerController::class,
        'products' => ProductController::class,
        'metas' => MetaController::class,
        'roles' => RoleController::class
    ]);

    // Settings resource routes (protected) except index which is public
    Route::apiResource('settings', SettingController::class)->except(['index']);
});
