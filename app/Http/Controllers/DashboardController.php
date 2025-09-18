<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Jobdesk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a summary of dashboard data.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Get date filters from request
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        // If no dates provided, use current month as default
        if (!$dateFrom || !$dateTo) {
            $dateFrom = now()->startOfMonth()->format('Y-m-d');
            $dateTo = now()->format('Y-m-d');
        }

        // Base queries with date filters
        $orderQuery = Order::whereBetween('order_date', [$dateFrom, $dateTo]);
        $jobdeskQuery = Jobdesk::whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);

        // Total counts (not filtered by date for context)
        $totalCustomers = Customer::count();
        $totalOrders = Order::count();
        $totalKaryawan = User::count();

        // Filtered data based on date range
        $orderBulanIni = $orderQuery->count();
        $totalPendapatan = intval($orderQuery->sum('paid'));
        $pendapatanBulanIni = intval($orderQuery->sum('paid'));

        // Previous period comparison (same date range but previous period)
        $dateDiff = \Carbon\Carbon::parse($dateFrom)->diffInDays(\Carbon\Carbon::parse($dateTo));
        $previousDateFrom = \Carbon\Carbon::parse($dateFrom)->subDays($dateDiff + 1)->format('Y-m-d');
        $previousDateTo = \Carbon\Carbon::parse($dateFrom)->subDay()->format('Y-m-d');
        $pendapatanBulanSebelumnya = intval(Order::whereBetween('order_date', [$previousDateFrom, $previousDateTo])->sum('paid'));

        $totalTagihanPeriode = intval($orderQuery->sum('price'));
        $totalTagihanBulanIni = $totalTagihanPeriode;
        $totalTagihan = intval(Order::sum('price')) - intval(Order::sum('paid'));

        // Menghitung jobdesk berdasarkan status dengan filter tanggal
        $totalJobdesk = $jobdeskQuery->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        // Menyiapkan data untuk response
        $data = [
            'total_customers' => $totalCustomers,
            'order_bulan_ini' => $orderBulanIni,
            'total_orders' => $user->role !== 'staff' ? $totalOrders : 0,
            'total_pendapatan' => $user->role !== 'staff' ? $totalPendapatan : 0,
            'pendapatan_bulan_ini' => $user->role !== 'staff' ? $pendapatanBulanIni : 0,
            'pendapatan_bulan_sebelumnya' => $user->role !== 'staff' ? $pendapatanBulanSebelumnya : 0,
            'total_tagihan' => $user->role !== 'staff' ? $totalTagihan : 0,
            'total_tagihan_bulan_ini' => $user->role !== 'staff' ? $totalTagihanBulanIni : 0,
            'total_karyawan' => $totalKaryawan,
            'total_jobdesks' => [
                'Masuk' => (int) $totalJobdesk->get('Masuk', 0),
                'Progress' => (int) $totalJobdesk->get('Progress', 0),
                'Selesai' => (int) $totalJobdesk->get('Selesai', 0),
            ],
        ];

        return response()->json($data);
    }
}
