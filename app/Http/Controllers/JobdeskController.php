<?php

namespace App\Http\Controllers;

use App\Models\Jobdesk;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JobdeskController extends Controller
{
    public function index(Request $request)
    {
        try {
            $orderId = $request->query('order_id');
            $status = $request->query('status');
            $name = $request->query('name');
            $userId = $request->query('user_id');
            $makerId = $request->query('maker_id');
            $picId = $request->query('pic_id');
            $dari = $request->query('dari');
            $sampai = $request->query('sampai');
            $user = $request->user();

            // Optimized eager loading with selective fields
            $query = Jobdesk::with([
                'order:id,no_order,customer_id,product_id,order_date,maker_id,pic_id',
                'order.customer:id,name,phone,address',
                'order.product:id,name,category,description',
                'order.maker:id,name',
                'order.pic:id,name',
                'user:id,name,email'
            ])
                ->select([
                    'id',
                    'order_id',
                    'user_id',
                    'description',
                    'status',
                    'tanggal_pengerjaan',
                    'tanggal_selesai',
                    'created_at',
                    'updated_at'
                ]);

            // Filter by order_id if provided
            if ($orderId && $orderId !== '') {
                $query->where('order_id', $orderId);
            }

            // Role-based filtering
            if (in_array($user->role, ['staff'])) {
                $query->where('user_id', $user->id);
            }

            // Filter by status if provided
            if ($status && $status !== '') {
                $query->where('status', $status);
            }

            // Filter by user_id if provided
            if ($userId && $userId !== '') {
                $query->where('user_id', $userId);
            }

            // Filter by order maker_id if provided
            if ($makerId && $makerId !== '') {
                $query->whereHas('order', function ($q) use ($makerId) {
                    $q->where('maker_id', $makerId);
                });
            }

            // Filter by order pic_id if provided
            if ($picId && $picId !== '') {
                $query->whereHas('order', function ($q) use ($picId) {
                    $q->where('pic_id', $picId);
                });
            }

            // Search by customer name (minimum 3 characters)
            if ($name && strlen($name) > 2) {
                $query->whereHas('order.customer', function ($query) use ($name) {
                    $query->where('name', 'like', '%' . $name . '%');
                });
            }

            // Date range filters
            if ($dari && $sampai) {
                $query->whereHas('order', function ($q) use ($dari, $sampai) {
                    $q->whereBetween('order_date', [
                        date('Y-m-d', strtotime($dari)),
                        date('Y-m-d', strtotime($sampai))
                    ]);
                });
            } elseif ($dari) {
                $query->whereHas('order', function ($q) use ($dari) {
                    $q->where('order_date', '>=', date('Y-m-d', strtotime($dari)));
                });
            } elseif ($sampai) {
                $query->whereHas('order', function ($q) use ($sampai) {
                    $q->where('order_date', '<=', date('Y-m-d', strtotime($sampai)));
                });
            }

            // Get total counts for stats (without filters for global stats)
            try {
                $totalCounts = $this->getGlobalStatusCounts($user);
            } catch (\Exception $e) {
                // Fallback to simple counts if there's an error
                $totalCounts = [
                    'total' => 0,
                    'masuk' => 0,
                    'progress' => 0,
                    'selesai' => 0,
                    'completion_rate' => 0
                ];
            }

            // Order and paginate
            $jobdesks = $query->orderBy('id', 'desc')->paginate(25);

            // Transform data for frontend
            $jobdesks->through(function ($jobdesk) use ($user) {
                try {
                    return $this->formatJobdeskResponse($jobdesk, $user);
                } catch (\Exception $e) {
                    // Fallback to basic response if formatting fails
                    $basic = [
                        'id' => $jobdesk->id,
                        'order_id' => $jobdesk->order_id,
                        'user_id' => $jobdesk->user_id,
                        'description' => $jobdesk->description,
                        'status' => $jobdesk->status,
                        'tanggal_pengerjaan' => $jobdesk->tanggal_pengerjaan,
                        'tanggal_selesai' => $jobdesk->tanggal_selesai,
                        'created_at' => $jobdesk->created_at,
                        'updated_at' => $jobdesk->updated_at,
                        'order' => $jobdesk->order,
                        'user' => $jobdesk->user,
                        'customer_name' => $jobdesk->order?->customer?->name ?? 'Tidak diketahui',
                        'customer_phone' => $jobdesk->order?->customer?->phone ?? '-',
                        'product_name' => $jobdesk->order?->product?->name ?? '-',
                        'assigned_to' => $jobdesk->user?->name ?? 'Belum ditugaskan',
                        'maker_name' => $jobdesk->order?->maker?->name ?? null,
                        'pic_name' => $jobdesk->order?->pic?->name ?? null,
                        'error' => 'Formatting error: ' . $e->getMessage()
                    ];
                    return $basic;
                }
            });

            // Just return the paginated jobdesks like OrderController
            // We'll add the extra data in the frontend from status_counts endpoint
            return response()->json($jobdesks);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server Error',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => basename($e->getFile())
            ], 500);
        }
    }
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'user_id' => 'required|exists:users,id',
                'description' => 'required|string',
                'tanggal_pengerjaan' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date',
                'status' => 'nullable|string',
            ]);

            $validatedData['tanggal_pengerjaan'] = $validatedData['tanggal_pengerjaan'] ? Carbon::parse($validatedData['tanggal_pengerjaan'])->setTimezone('Asia/Jakarta')->startOfDay() : null;
            $validatedData['tanggal_selesai'] = $validatedData['tanggal_selesai'] ? Carbon::parse($validatedData['tanggal_selesai'])->setTimezone('Asia/Jakarta')->endOfDay() : null;

            $jobdesk = Jobdesk::create($validatedData);
            // Load relations without role field
            $jobdesk->load([
                'order',
                'order.customer',
                'user:id,name,email',
                'order.product'
            ]);
            return response()->json($jobdesk, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Server Error', 'error' => $e->getMessage()], 500);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $jobdesk = Jobdesk::find($id);
            $validatedData = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'user_id' => 'required|exists:users,id',
                'description' => 'required|string',
                'tanggal_pengerjaan' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date',
                'status' => 'nullable|string',
            ]);

            $validatedData['tanggal_pengerjaan'] = $validatedData['tanggal_pengerjaan'] ? Carbon::parse($validatedData['tanggal_pengerjaan'])->setTimezone('Asia/Jakarta')->startOfDay() : null;
            $validatedData['tanggal_selesai'] = $validatedData['tanggal_selesai'] ? Carbon::parse($validatedData['tanggal_selesai'])->setTimezone('Asia/Jakarta')->endOfDay() : null;

            $jobdesk->update($validatedData);
            $jobdesk->load([
                'order',
                'order.customer',
                'user:id,name,email',
                'order.product'
            ]);
            return response()->json($jobdesk);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(Jobdesk $jobdesk)
    {
        $jobdesk = Jobdesk::find($jobdesk->id)->load([
            'order',
            'order.customer',
            'user:id,name,email',
            'order.product'
        ]);
        return response()->json($jobdesk);
    }

    public function destroy(Jobdesk $jobdesk)
    {
        try {
            $jobdesk->delete();
            return response()->json(['message' => 'Jobdesk deleted successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting jobdesk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get global status counts (ignoring all filters) for dashboard stats
     */
    private function getGlobalStatusCounts($user)
    {
        try {
            // Create a fresh query without any filters
            $baseQuery = Jobdesk::query();

            // Only apply role-based filtering if user is staff
            if (in_array($user->role, ['staff'])) {
                $baseQuery->where('user_id', $user->id);
            }

            // Get total count
            $totalCount = $baseQuery->count();

            // Count by status - handle null status gracefully
            $masukCount = (clone $baseQuery)->where('status', 'Masuk')->count();
            $progressCount = (clone $baseQuery)->where('status', 'Progress')->count();
            $selesaiCount = (clone $baseQuery)->where('status', 'Selesai')->count();

            return [
                'total' => $totalCount,
                'masuk' => $masukCount,
                'progress' => $progressCount,
                'selesai' => $selesaiCount,
                'completion_rate' => $totalCount > 0 ? round(($selesaiCount / $totalCount) * 100, 2) : 0
            ];
        } catch (\Exception $e) {
            // Return zero counts if there's an error
            return [
                'total' => 0,
                'masuk' => 0,
                'progress' => 0,
                'selesai' => 0,
                'completion_rate' => 0
            ];
        }
    }

    /**
     * Get status counts for dashboard stats
     */
    private function getStatusCounts($query, $user)
    {
        try {
            // Clone query for each status count to avoid conflicts
            $baseQuery = clone $query;

            // Remove pagination and get total count
            $totalCount = $baseQuery->count();

            // Count by status - handle null status gracefully
            $masukCount = (clone $query)->where('status', 'Masuk')->count();
            $progressCount = (clone $query)->where('status', 'Progress')->count();
            $selesaiCount = (clone $query)->where('status', 'Selesai')->count();

            return [
                'total' => $totalCount,
                'masuk' => $masukCount,
                'progress' => $progressCount,
                'selesai' => $selesaiCount,
                'completion_rate' => $totalCount > 0 ? round(($selesaiCount / $totalCount) * 100, 2) : 0
            ];
        } catch (\Exception $e) {
            // Return zero counts if there's an error
            return [
                'total' => 0,
                'masuk' => 0,
                'progress' => 0,
                'selesai' => 0,
                'completion_rate' => 0
            ];
        }
    }

    /**
     * Format jobdesk response for frontend
     */
    private function formatJobdeskResponse($jobdesk, $user)
    {
        try {
            return [
                'id' => $jobdesk->id ?? null,
                'order_id' => $jobdesk->order_id ?? null,
                'user_id' => $jobdesk->user_id ?? null,
                'description' => $jobdesk->description ?? '',
                'status' => $jobdesk->status ?? '',
                'tanggal_pengerjaan' => $jobdesk->tanggal_pengerjaan ?? null,
                'tanggal_selesai' => $jobdesk->tanggal_selesai ?? null,
                'created_at' => $jobdesk->created_at ?? null,
                'updated_at' => $jobdesk->updated_at ?? null,

                // Enhanced order information
                'order' => $jobdesk->order ? [
                    'id' => $jobdesk->order->id ?? null,
                    'no_order' => $jobdesk->order->no_order ?? '',
                    'order_date' => $jobdesk->order->order_date ?? null,
                    'customer' => $jobdesk->order->customer ? [
                        'id' => $jobdesk->order->customer->id ?? null,
                        'name' => $jobdesk->order->customer->name ?? '',
                        'phone' => $jobdesk->order->customer->phone ?? '',
                        'address' => $jobdesk->order->customer->address ?? '',
                    ] : null,
                    'product' => $jobdesk->order->product ? [
                        'id' => $jobdesk->order->product->id ?? null,
                        'name' => $jobdesk->order->product->name ?? '',
                        'category' => $jobdesk->order->product->category ?? '',
                        'description' => $jobdesk->order->product->description ?? '',
                    ] : null,
                ] : null,

                // User information
                'user' => $jobdesk->user ? [
                    'id' => $jobdesk->user->id ?? null,
                    'name' => $jobdesk->user->name ?? '',
                    'email' => $jobdesk->user->email ?? '',
                    // Removed role field since it doesn't exist in users table
                ] : null,

                // Frontend convenience fields
                'customer_name' => $jobdesk->order?->customer?->name ?? 'Tidak diketahui',
                'customer_phone' => $jobdesk->order?->customer?->phone ?? '-',
                'product_name' => $jobdesk->order?->product?->name ?? '-',
                'assigned_to' => $jobdesk->user?->name ?? 'Belum ditugaskan',
                'status_text' => $this->getStatusText($jobdesk->status ?? ''),
                'status_class' => $this->getStatusClass($jobdesk->status ?? ''),
                'is_overdue' => $this->isOverdue($jobdesk),
                'days_remaining' => $this->getDaysRemaining($jobdesk),
            ];
        } catch (\Exception $e) {
            // Return basic response if formatting fails
            return [
                'id' => $jobdesk->id ?? null,
                'order_id' => $jobdesk->order_id ?? null,
                'user_id' => $jobdesk->user_id ?? null,
                'description' => $jobdesk->description ?? '',
                'status' => $jobdesk->status ?? '',
                'tanggal_pengerjaan' => $jobdesk->tanggal_pengerjaan ?? null,
                'tanggal_selesai' => $jobdesk->tanggal_selesai ?? null,
                'created_at' => $jobdesk->created_at ?? null,
                'updated_at' => $jobdesk->updated_at ?? null,
                'order' => $jobdesk->order ?? null,
                'user' => $jobdesk->user ?? null,
                'customer_name' => 'Error loading data',
                'status_text' => 'Unknown',
                'formatting_error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get human readable status text
     */
    private function getStatusText($status)
    {
        switch ($status) {
            case 'Masuk':
                return 'Belum Mulai';
            case 'Progress':
                return 'Sedang Dikerjakan';
            case 'Selesai':
                return 'Selesai';
            default:
                return $status ?? 'Tidak Diketahui';
        }
    }

    /**
     * Get CSS class for status badge
     */
    private function getStatusClass($status)
    {
        switch ($status) {
            case 'Masuk':
                return 'bg-yellow-100 text-yellow-800 border-yellow-200';
            case 'Progress':
                return 'bg-blue-100 text-blue-800 border-blue-200';
            case 'Selesai':
                return 'bg-green-100 text-green-800 border-green-200';
            default:
                return 'bg-gray-100 text-gray-800 border-gray-200';
        }
    }

    /**
     * Check if jobdesk is overdue
     */
    private function isOverdue($jobdesk)
    {
        try {
            if (!$jobdesk || !$jobdesk->tanggal_selesai || $jobdesk->status === 'Selesai') {
                return false;
            }

            return Carbon::now()->gt(Carbon::parse($jobdesk->tanggal_selesai));
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get days remaining for completion
     */
    private function getDaysRemaining($jobdesk)
    {
        try {
            if (!$jobdesk || !$jobdesk->tanggal_selesai || $jobdesk->status === 'Selesai') {
                return null;
            }

            $now = Carbon::now();
            $deadline = Carbon::parse($jobdesk->tanggal_selesai);

            return $now->diffInDays($deadline, false); // false = can be negative
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get jobdesk statistics - separate endpoint for dashboard
     */
    public function stats(Request $request)
    {
        try {
            $user = $request->user();

            // Get global stats (ignoring filters) for dashboard
            $stats = $this->getGlobalStatusCounts($user);

            // Add additional stats with error handling (also global)
            try {
                $baseQuery = Jobdesk::query();

                // Only apply role-based filtering if user is staff
                if (in_array($user->role, ['staff'])) {
                    $baseQuery->where('user_id', $user->id);
                }

                $stats['overdue_count'] = (clone $baseQuery)
                    ->where('status', '!=', 'Selesai')
                    ->where('tanggal_selesai', '<', Carbon::now())
                    ->whereNotNull('tanggal_selesai')
                    ->count();
            } catch (\Exception $e) {
                $stats['overdue_count'] = 0;
            }

            try {
                $baseQuery = Jobdesk::query();

                // Only apply role-based filtering if user is staff
                if (in_array($user->role, ['staff'])) {
                    $baseQuery->where('user_id', $user->id);
                }

                $stats['today_deadline'] = (clone $baseQuery)
                    ->where('status', '!=', 'Selesai')
                    ->whereDate('tanggal_selesai', Carbon::today())
                    ->count();
            } catch (\Exception $e) {
                $stats['today_deadline'] = 0;
            }

            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
