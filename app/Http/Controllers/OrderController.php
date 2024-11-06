<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::all()->load('customer');
        return response()->json($orders);
    }

    public function show(Order $order)
    {
        $order = Order::find($order->id)->load('customer');
        return response()->json($order);
    }
}
