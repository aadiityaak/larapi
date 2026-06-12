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
    Auth\ProfileController,
    Auth\AuthenticatedSessionController,
    Auth\EmailVerificationSendNotificationController,
    Auth\NewPasswordController,
    Auth\PasswordResetLinkController,
    Auth\RegisteredUserController,
    Auth\VerifyEmailController,
    MaintenanceController
};
use Spatie\Permission\Models\Permission;

// Routes tanpa middleware
Route::get('/settings/background', [SettingLoginController::class, 'index']);
Route::get('/settings/favicon', [SettingFaviconController::class, 'index']);
Route::get('/settings', [SettingController::class, 'index']);

// Auth routes for API (use web middleware for session support)
Route::middleware('web')->group(function () {
    Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('guest')->name('api.register');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest')->name('api.login');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->middleware('guest')->name('api.password.email');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->middleware('guest')->name('api.password.store');
    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class])->middleware(['auth:sanctum', 'signed', 'throttle:6,1'])->name('api.verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationSendNotificationController::class, 'store'])->middleware(['auth:sanctum', 'throttle:6,1'])->name('api.verification.send');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:sanctum')->name('api.logout');
});

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

    // Order list for dropdown
    Route::get('orders/list', [OrderController::class, 'list']);

    // Order print (PDF)
    Route::get('orders/{order}/print', [OrderController::class, 'print'])->name('orders.print');

    // Jobdesk stats endpoint
    Route::get('jobdesks/stats', [JobdeskController::class, 'stats']);

    // Minimal karyawan list for order workflows
    Route::get('karyawans/min', [KaryawanController::class, 'min']);

    // API Resources
    Route::apiResources([
        'posts' => PostController::class,
        'categories' => CategoryController::class,
        'karyawans' => KaryawanController::class,
        'orders' => OrderController::class,
        'jobdesks' => JobdeskController::class,
        'customers' => CustomerController::class,
        'produk' => ProductController::class,
        'products' => ProductController::class,
        'metas' => MetaController::class,
        'roles' => RoleController::class
    ]);

    // Settings resource routes (protected) except index which is public
    Route::apiResource('settings', SettingController::class)->except(['index']);

    // Maintenance (admin only)
    Route::prefix('maintenance')->group(function () {
        Route::post('{action}', [MaintenanceController::class, 'run'])
            ->where('action', 'migrate|migrate-fresh|cache-clear|config-clear|route-clear|view-clear|optimize-clear|queue-restart|storage-link');
    });
});
