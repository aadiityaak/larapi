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
        'paid' => 'nullable',
        'payment_method' => 'required',
        'meta' => 'nullable',
        'customer_id' => 'required|exists:customers,id',
    ];

    public function index(Request $request)
    {
        $customerId = $request->query('customer_id');
        $paginate = $request->query('paginate');
        $name = $request->query('name');
        $productQuery = $request->query('product');
        $status = $request->query('status');
        $status = isset($status) ? $status : null;
        $user = $request->user();

        $query = Order::with('customer', 'jobdesks', 'product', 'product.metaProducts.meta');

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        if ($name || $productQuery) {
            $query->whereHas('customer', function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            });
            $query->whereHas('product', function ($query) use ($productQuery) {
                $query->where('name', 'like', '%' . $productQuery . '%');
            });
        } else {
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
            } else if (!($customerId || $name)) {
                $query->whereHas('jobdesks', function ($query) {
                    $query->where('status', '!=', 'Selesai');
                })->orWhereDoesntHave('jobdesks');
            }
        }

        $query->orderBy('created_at', 'asc');

        // Check if pagination should be disabled
        if ($paginate === 'false') {
            // Get all records without pagination
            $orders = $query->get()->map(function ($data) use ($user) {
                return [
                    'id' => $data->id,
                    'no_order' => $data->no_order,
                    'customer_id' => $data->customer->id ?? null,
                    'order_date' => $data->order_date,
                    'product_id' => $data->product->id ?? null,
                    'price' => $user->position !== 'Staff' ? $data->price : 0,
                    'payment_method' => $data->payment_method,
                    'paid' => $user->position !== 'Staff' ? $data->paid : 0,
                    'meta' => $data->meta,
                    'lampiran' => $data->lampiran,
                    'jobdesk_count' => $data->jobdesks()->count(),
                    'created_at' => $data->created_at,
                    'customer' => [
                        'id' => $data->customer->id,
                        'name' => $data->customer->name,
                        'phone' => $data->customer->phone,
                        'address' => $data->customer->address,
                    ],
                    'jobdesks' => $data->jobdesks,
                    'product' => [
                        'id' => $data->product->id,
                        'name' => $data->product->name,
                        'category' => $data->product->category,
                        'description' => $data->product->description,
                        'meta_products' => $data->product->metaProducts->pluck('meta'),
                    ]
                ];
            });
        } else {
            // Paginate results
            $orders = $query->paginate(25);
            $orders->getCollection()->transform(function ($data) use ($user) {
                return [
                    'id' => $data->id,
                    'no_order' => $data->no_order,
                    'customer_id' => $data->customer->id ?? null,
                    'order_date' => $data->order_date,
                    'product_id' => $data->product->id ?? null,
                    'price' => $user->position !== 'Staff' ? $data->price : 0,
                    'payment_method' => $data->payment_method,
                    'paid' => $user->position !== 'Staff' ? $data->paid : 0,
                    'meta' => $data->meta,
                    'lampiran' => $data->lampiran,
                    'jobdesk_count' => $data->jobdesks()->count(),
                    'created_at' => $data->created_at,
                    'customer' => $data->customer ? [
                        'id' => $data->customer->id,
                        'name' => $data->customer->name,
                        'phone' => $data->customer->phone,
                        'address' => $data->customer->address,
                    ] : null,
                    'jobdesks' => $data->jobdesks,
                    'product' => $data->product ? [
                        'id' => $data->product->id,
                        'name' => $data->product->name,
                        'category' => $data->product->category,
                        'description' => $data->product->description,
                        'meta_products' => $data->product->metaProducts->pluck('meta'),
                    ] : null,
                    'position' => $user->position,
                ];
            });
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
        $user = $request->user();

        // Jika ada lampiran, hanya validasi lampiran
        if ($request->hasFile('lampiran')) {
            $request->validate([
                'lampiran' => 'required|mimes:pdf',
            ]);

            $filePath = $request->file('lampiran')->store('lampiran', 'public');

            // Hapus dokumen lama jika ada
            if ($order->lampiran) {
                Storage::disk('public')->delete($order->lampiran);
            }

            // Update order dengan lampiran baru
            $order->update(['lampiran' => $filePath]);
        } else {
            // Validasi data lain jika lampiran tidak ada
            $validatedData = $request->validate([
                'order_date' => 'required',
                'product_id' => 'required',
                'price' => 'required',
                'paid' => 'nullable',
                'payment_method' => 'required',
                'meta' => 'nullable',
                'customer' => 'required',
            ]);

            // Update order dengan data yang sudah divalidasi
            $order->update($validatedData);
        }

        // Load relasi dan respon
        $order->load('customer', 'jobdesks', 'product', 'product.metaProducts.meta');
        $response = [
            'id' => $order->id,
            'no_order' => $order->no_order,
            'customer_id' => $order->customer->id,
            'order_date' => $order->order_date,
            'product_id' => $order->product->id,
            'price' => $user->position !== 'Staff' ? $order->price : 0,
            'payment_method' => $order->payment_method,
            'paid' => $user->position !== 'Staff' ? $order->paid : 0,
            'meta' => $order->meta,
            'lampiran' => $order->lampiran,
            'jobdesk_count' => $order->jobdesks()->count(),
            'created_at' => $order->created_at,
            'customer' => [
                'id' => $order->customer->id,
                'name' => $order->customer->name,
                'phone' => $order->customer->phone,
                'address' => $order->customer->address,
            ],
            'jobdesks' => $order->jobdesks,
            'product' => [
                'id' => $order->product->id,
                'name' => $order->product->name,
                'category' => $order->product->category,
                'description' => $order->product->description,
                'meta_products' => $order->product->metaProducts->pluck('meta'),
            ]
        ];

        return response()->json($response);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'order_date' => 'required',
            'product_id' => 'required',
            'price' => 'required',
            'paid' => 'required',
            'payment_method' => 'required',
            'meta' => 'nullable',
            'customer_id' => 'required|exists:customers,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        $order = Order::create($validator->validated());
        $order->load('customer', 'jobdesks', 'product', 'product.metaProducts.meta');

        $users = User::where('is_admin', 1)->orWhere('position', 'owner')->get();
        Notification::send($users, new NewOrderNotification($order));
        $response = [
            'id' => $order->id,
            'no_order' => $order->no_order,
            'customer_id' => $order->customer->id,
            'order_date' => $order->order_date,
            'product_id' => $order->product->id,
            'price' => $user->position !== 'Staff' ? $order->price : 0,
            'payment_method' => $order->payment_method,
            'paid' => $user->position !== 'Staff' ? $order->paid : 0,
            'meta' => $order->meta,
            'lampiran' => $order->lampiran,
            'jobdesk_count' => $order->jobdesks()->count(),
            'created_at' => $order->created_at,
            'customer' => [
                'id' => $order->customer->id,
                'name' => $order->customer->name,
                'phone' => $order->customer->phone,
                'address' => $order->customer->address,
            ],
            'jobdesks' => $order->jobdesks,
            'product' => [
                'id' => $order->product->id,
                'name' => $order->product->name,
                'category' => $order->product->category,
                'description' => $order->product->description,
                'meta_products' => $order->product->metaProducts->pluck('meta'),
            ]
        ];
        return response()->json($response);
    }

    public function destroy(Order $order)
    {
        $order = Order::find($order->id);
        $order->jobdesks()->delete();
        $order->delete();
        return response()->json($order);
    }
}
