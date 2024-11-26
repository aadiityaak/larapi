<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    private $validate = [
        'order_date' => 'required',
        'service' => 'required',
        'price' => 'required',
        'paid' => 'required',
        'payment_method' => 'required',
        'document' => 'required'
    ];
    public function index(Request $request)
    {
        // Ambil parameter 'customer' dari query string
        $customerId = $request->query('customer');

        // Jika ada ID customer, lakukan filter berdasarkan ID tersebut
        if ($customerId) {
            $orders = Order::with('customer', 'jobdesks')
                ->where('customer_id', $customerId)
                ->paginate(25);
        } else {
            // Jika tidak ada parameter, ambil semua pesanan
            $orders = Order::with('customer', 'jobdesks')->paginate(25);
        }

        return response()->json($orders);
    }

    public function show(Order $order)
    {
        $order = Order::find($order->id)->load('customer');
        return response()->json($order);
    }

    public function update(Request $request, Order $order)
    {
        $order = Order::find($order->id);
        $validatedData = $request->validate($this->validate);
        $order->update($validatedData);
        return response()->json($order);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate($this->validate);
        $order = Order::create($validatedData);
        return response()->json($order);
    }

    public function destroy(Order $order)
    {
        $order = Order::find($order->id);
        $order->delete();
        return response()->json($order);
    }
}
