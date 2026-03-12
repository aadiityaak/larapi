<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceController extends Controller
{
    protected array $whitelist = [
        'migrate' => ['command' => 'migrate', 'params' => ['--force' => true]],
        'migrate-fresh' => ['command' => 'migrate:fresh', 'params' => ['--force' => true]],
        'cache-clear' => ['command' => 'cache:clear', 'params' => []],
        'config-clear' => ['command' => 'config:clear', 'params' => []],
        'route-clear' => ['command' => 'route:clear', 'params' => []],
        'view-clear' => ['command' => 'view:clear', 'params' => []],
        'optimize-clear' => ['command' => 'optimize:clear', 'params' => []],
        'queue-restart' => ['command' => 'queue:restart', 'params' => []],
        'storage-link' => ['command' => 'storage:link', 'params' => []],
    ];

    public function run(Request $request, string $action)
    {
        $this->authorizeAccess($request);

        if (!isset($this->whitelist[$action])) {
            return response()->json([
                'ok' => false,
                'message' => 'Aksi tidak diizinkan.'
            ], Response::HTTP_BAD_REQUEST);
        }

        $cfg = $this->whitelist[$action];

        try {
            Artisan::call($cfg['command'], $cfg['params']);
            $output = Artisan::output();

            return response()->json([
                'ok' => true,
                'action' => $action,
                'output' => $output,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'action' => $action,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    protected function authorizeAccess(Request $request): void
    {
        $user = $request->user();
        if (!$user) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        // Prefer Spatie permissions if available
        try {
            if (method_exists($user, 'hasRole') || method_exists($user, 'hasPermissionTo')) {
                if (
                    ($user->hasRole('Admin') ?? false) ||
                    ($user->hasRole('Super Admin') ?? false) ||
                    ($user->hasRole('admin') ?? false) ||
                    ($user->hasPermissionTo('system:maintain') ?? false) ||
                    ($user->hasPermissionTo('setting:update') ?? false)
                ) {
                    return;
                }
            }
        } catch (\Throwable $e) {
            // Fallback below
        }

        // On local environment, allow any authenticated user to run maintenance
        if (app()->environment('local')) {
            return;
        }

        // Fallback: allow only users with 'admin' position field if exists
        if (property_exists($user, 'position') && strtolower((string)$user->position) === 'admin') {
            return;
        }

        abort(Response::HTTP_FORBIDDEN, 'Anda tidak memiliki akses maintenance.');
    }
}
