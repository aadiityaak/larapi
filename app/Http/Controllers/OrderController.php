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
    public function index()
    {
        $orders = Order::with('customer', 'jobdesks')->paginate(25);
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
