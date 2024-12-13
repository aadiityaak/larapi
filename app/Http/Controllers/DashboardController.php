<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Jobdesk;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Display a summary of dashboard data.
     */
    public function index()
    {
        $totalCustomers = Customer::count();
        $totalOrders = Order::count();
        $totalKaryawan = User::count();

        $totalPendapatan = intval(Order::sum('paid'));
        $totalBelumbayar = intval(Order::sum('price')) - intval(Order::sum('paid'));

        // Menghitung jobdesk berdasarkan status
        $totalJobdesk = Jobdesk::select('status', \DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        // Menyiapkan data untuk response
        $data = [
            'total_customers' => $totalCustomers,
            'total_orders' => $totalOrders,
            'total_pendapatan' => $totalPendapatan,
            'total_belumbayar' => $totalBelumbayar,
            'total_karyawan' => $totalKaryawan,
            'total_jobdesks' => [
                'Masuk' => $totalJobdesk->get('Masuk', 0),
                'Progress' => $totalJobdesk->get('Progress', 0),
                'Selesai' => $totalJobdesk->get('Selesai', 0),
            ],
        ];

        return response()->json($data);
    }
}
