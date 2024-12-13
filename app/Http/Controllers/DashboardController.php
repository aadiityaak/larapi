<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Jobdesk;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a summary of dashboard data.
     */
    public function index()
    {
        $totalCustomers = Customer::count();
        $totalOrders = Order::count();
        $totalJobdesks = Jobdesk::count();
        // Jobdesk status == 'Masuk'
        $totalJobdesksMasuk = Jobdesk::where('status', 'Masuk')->count();

        // Menghitung total pendapatan dari semua order
        // $totalRevenue = Order::sum('total_price');

        // Menyiapkan data untuk response
        $data = [
            'total_customers' => $totalCustomers,
            'total_orders' => $totalOrders,
            'total_jobdesks' => $totalJobdesks,
            'total_jobdesks_masuk' => $totalJobdesksMasuk,
            // 'total_revenue' => $totalRevenue,
        ];

        return response()->json($data);
    }
}
