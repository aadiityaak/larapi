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
        $totalCustomers = Customer::count();
        $totalOrders = Order::count();
        $orderBulanIni = Order::whereMonth('order_date', now()->month)->count();
        $totalKaryawan = User::count();

        $totalPendapatan = intval(Order::sum('paid'));
        $pendapatanBulanIni = intval(Order::whereMonth('order_date', now()->month)->sum('paid'));
        $pendapatanBulanSebelumnya = intval(Order::whereMonth('order_date', now()->subMonth()->month)->sum('paid'));
        $totalTagihan = intval(Order::sum('price'));
        $totalTagihanBulanIni = intval(Order::whereMonth('order_date', now()->month)->sum('price'));
        $totalBelumbayar = $totalTagihan - $totalPendapatan;

        // Menghitung jobdesk berdasarkan status
        $totalJobdesk = Jobdesk::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        // Menyiapkan data untuk response
        $data = [
            'total_customers' => $totalCustomers,
            'order_bulan_ini' => $orderBulanIni,
            'total_orders' => $user->position !== 'Staff' ? $totalOrders : 0,
            'total_pendapatan' => $user->position !== 'Staff' ? $totalPendapatan : 0,
            'pendapatan_bulan_ini' => $user->position !== 'Staff' ? $pendapatanBulanIni : 0,
            'pendapatan_bulan_sebelumnya' => $user->position !== 'Staff' ? $pendapatanBulanSebelumnya : 0,
            'total_tagihan' => $user->position !== 'Staff' ? $totalTagihan : 0,
            'total_tagihan_bulan_ini' => $user->position !== 'Staff' ? $totalTagihanBulanIni : 0,
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
