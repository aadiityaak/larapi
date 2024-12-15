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
        // Ambil parameter query dari request
        $customerId = $request->query('customer_id');
        $name = $request->query('name');
        $status = $request->query('status');
        $status = ($status === 'Arsip') ? 'Selesai' : $status;

        // Mulai query dasar
        $query = Order::with('customer', 'jobdesks');

        // Filter berdasarkan customer_id jika ada
        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        // Filter berdasarkan nama customer jika parameter name diberikan dan panjangnya > 2
        if ($name && strlen($name) > 2) {
            $query->whereHas('customer', function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            });
        }

        if ($status) {
            $query->whereDoesntHave('jobdesks', function ($query) use ($status) {
                $query->where('status', '!=', $status);
            });
        } else {
            $query->whereHas('jobdesks', function ($query) {
                $query->where('status', '!=', 'Selesai');
            });
        }

        // Paginate hasil
        $orders = $query->paginate(25);

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
