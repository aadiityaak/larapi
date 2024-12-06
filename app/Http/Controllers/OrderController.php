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
        'document' => 'required',
        'customer_id' => 'required|exists:customers,id',
    ];
    public function index(Request $request)
    {
        // Ambil parameter 'customer' dari query string
        $customerId = $request->query('customer_id');

        // if ($request->has('name') && strlen($request->input('name')) > 3) {
        //     $query->where('name', 'like', '%' . $request->input('name') . '%');
        // }

        // Jika ada ID customer, lakukan filter berdasarkan ID tersebut
        if ($customerId) {
            $orders = Order::with('customer', 'jobdesks')
                ->where('customer_id', $customerId)
                ->paginate(25);
        } else {
            if ($request->has('name') && strlen($request->input('name')) > 2) {
                $name = $request->input('name');
                $orders = Order::with('customer', 'jobdesks')
                    ->whereHas('customer', function ($query) use ($name) {
                        $query->where('name', 'like', '%' . $name . '%');
                    })
                    ->paginate(25);
            } else {
                // Jika tidak ada parameter, ambil semua pesanan
                $orders = Order::with('customer', 'jobdesks')->paginate(25);
            }
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
        // Validate the incoming request
        $validatedData = $request->validate($this->validate);

        // Create the order with validated data
        $order = Order::create($validatedData);

        // Return the created order as a JSON response
        return response()->json($order);
    }

    public function destroy(Order $order)
    {
        $order = Order::find($order->id);
        $order->delete();
        return response()->json($order);
    }
}
