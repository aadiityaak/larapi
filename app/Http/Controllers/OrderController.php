<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{

    private $validate = [
        'order_date' => 'required',
        'product_id' => 'required',
        'price' => 'required',
        'paid' => 'required',
        'payment_method' => 'required',
        'data' => 'nullable',
        'customer_id' => 'required|exists:customers,id',
    ];
    public function index(Request $request)
    {
        $customerId = $request->query('customer_id');
        $paginate = $request->query('paginate');
        $name = $request->query('name');
        $product = $request->query('product');
        $status = $request->query('status');
        $status = isset($status) ? $status : null;

        $query = Order::with('customer', 'jobdesks', 'product', 'product.dataProducts.data');

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        if ($name && strlen($name) > 2) {
            $query->whereHas('customer', function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            });
        }

        if ($product && strlen($product) > 2) {
            $query->whereHas('product', function ($query) use ($product) {
                $query->where('name', 'like', '%' . $product . '%');
            });
        }

        if ($status) {
            $query->whereDoesntHave('jobdesks', function ($query) use ($status) {
                $query->where('status', '!=', 'Selesai');
            });
            if ($status === 'Selesai') {
                $query->whereNotNull('lampiran');
            }
            if ($status === 'Arsip') {
                $query->whereNull('lampiran');
            }
            $query->whereHas('jobdesks');
        } else {
            $query->whereHas('jobdesks', function ($query) {
                $query->where('status', '!=', 'Selesai');
            })->orWhereDoesntHave('jobdesks');
        }

        $query->orderBy('created_at', 'desc');

        // Check if pagination should be disabled
        if ($paginate === 'false') {
            // Get all records without pagination
            $orders = $query->get();
        } else {
            // Paginate results
            $orders = $query->paginate(25);
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
        // Validasi dokumen jika ada
        if ($request->hasFile('lampiran')) {
            // unset all validated data
            $this->validate = [];
            $this->validate = [
                'lampiran' => 'required|mimes:pdf',
            ];
        }

        // Validasi data yang diterima
        $validatedData = $request->validate($this->validate);

        // Validasi dokumen jika ada
        if ($request->hasFile('lampiran')) {
            $this->validate['lampiran'] = 'required|mimes:pdf';
            $filePath = $request->file('lampiran')->store('lampiran', 'public');
            $validatedData['lampiran'] = $filePath;
        }

        // Cek dan hapus dokumen lama jika ada
        if ($order->lampiran) {
            Storage::disk('public')->delete($order->lampiran);
        }

        // Update order dengan data yang sudah divalidasi
        $order->update($validatedData);

        return response()->json($order);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_date' => 'required',
            'product_id' => 'required',
            'price' => 'required',
            'paid' => 'required',
            'payment_method' => 'required',
            'data' => 'nullable',
            'customer_id' => 'required|exists:customers,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        $order = Order::create($validator->validated());
        $users = User::where('is_admin', 1)->orWhere('position', 'owner')->get();
        Notification::send($users, new NewOrderNotification($order));
        return response()->json($order);
    }

    public function destroy(Order $order)
    {
        $order = Order::find($order->id);
        $order->jobdesks()->delete();
        $order->delete();
        return response()->json($order);
    }
}
