<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
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
        $paginate = $request->query('paginate', true);
        $name = $request->query('name');
        $productQuery = $request->query('product');
        $bank = $request->query('bank');
        $dari = $request->query('dari');
        $sampai = $request->query('sampai');
        $status = $request->query('status');
        $user = $request->user();

        // Optimized eager loading based on frontend needs
        $query = Order::with([
            'customer',
            'customer.meta',
            'relatedOrder.customer',
            'relatedOrders.customer',
            'jobdesks:id,order_id,status,description',
            'product:id,name,category,description',
            'product.metaProducts:id,product_id,meta_id',
            'product.metaProducts.meta:id,name,type',
            'maker:id,name',
            'pic:id,name'
        ])
            ->select([
                'id',
                'no_order',
                'customer_id',
                'created_by',
                'maker_id',
                'pic_id',
                'pemberi_order',
                'pemberi_phone',
                'product_id',
                'order_date',
                'price',
                'payment_method',
                'paid',
                'billing_notes',
                'meta',
                'lampiran',
                'related_order_id',
                'relation_type',
                'created_at'
            ]);

        // Apply customer filter
        if (isset($customerId) && $customerId !== 'undefined') {
            $query->where('customer_id', $customerId);
        }

        // Apply search and date filters
        $this->applyFilters($query, $name, $productQuery, $bank, $dari, $sampai);

        // Apply status filter - optimized for frontend tabs
        $this->applyStatusFilter($query, $status);

        // Default behavior: show oldest unfinished orders first, then newest finished orders
        if (!$status) {
            // When no status filter is applied, prioritize unfinished orders (oldest first)
            $query->orderBy(
                \DB::raw("CASE 
                    WHEN (
                        SELECT COUNT(*) 
                        FROM jobdesks 
                        WHERE jobdesks.order_id = orders.id 
                        AND jobdesks.status != 'Selesai'
                    ) > 0 
                    OR (
                        SELECT COUNT(*) 
                        FROM jobdesks 
                        WHERE jobdesks.order_id = orders.id
                    ) = 0 
                    THEN 0 
                    ELSE 1 
                END")
            )
                ->orderBy('created_at', 'asc'); // Oldest unfinished first
        } else {
            // When status filter is applied, use latest first
            $query->orderBy('created_at', 'desc');
        }

        // Get results with or without pagination
        return $this->getOrderResults($query, $paginate, $user);
    }

    private function applyFilters($query, $name, $productQuery, $bank, $dari, $sampai)
    {
        // Search by customer name (minimum 3 characters as per frontend)
        if ($name && strlen($name) >= 3) {
            $query->whereHas('customer', function ($q) use ($name) {
                $q->where('name', 'like', '%' . $name . '%');
            });
        }

        // Search by product name (minimum 3 characters as per frontend)
        if ($productQuery && strlen($productQuery) >= 3) {
            $query->whereHas('product', function ($q) use ($productQuery) {
                $q->where('name', 'like', '%' . $productQuery . '%');
            });
        }

        // Bank/category filter optimized for frontend dropdown
        if ($bank) {
            if ($bank === 'Perorangan') {
                $query->where(function ($q) {
                    $q->whereHas('customer.meta', function ($subQuery) {
                        $subQuery->where('meta_key', 'bank')
                            ->where('meta_value', 'Perorangan');
                    })->orWhereDoesntHave('customer.meta', function ($subQuery) {
                        $subQuery->where('meta_key', 'bank');
                    });
                });
            } else {
                $query->whereHas('customer.meta', function ($subQuery) use ($bank) {
                    $subQuery->where('meta_key', 'bank')
                        ->where('meta_value', $bank);
                });
            }
        }

        // Date range filter optimized for frontend date picker
        if ($dari && $sampai) {
            $query->whereBetween('order_date', [
                date('Y-m-d', strtotime($dari)),
                date('Y-m-d', strtotime($sampai))
            ]);
        } elseif ($dari) {
            $query->where('order_date', '>=', date('Y-m-d', strtotime($dari)));
        } elseif ($sampai) {
            $query->where('order_date', '<=', date('Y-m-d', strtotime($sampai)));
        }
    }

    private function applyStatusFilter($query, $status)
    {
        if (!$status) {
            return; // Show all orders by default
        }

        switch ($status) {
            case 'Masuk':
                $query->where(function ($q) {
                    $q->whereHas('jobdesks', function ($query) {
                        $query->where('status', '!=', 'Selesai');
                    })->orWhereDoesntHave('jobdesks');
                });
                break;

            case 'Selesai':
                $query->whereDoesntHave('jobdesks', function ($query) {
                    $query->where('status', '!=', 'Selesai');
                })->whereNotNull('lampiran')->whereHas('jobdesks');
                break;

            case 'Arsip':
                $query->whereDoesntHave('jobdesks', function ($query) {
                    $query->where('status', '!=', 'Selesai');
                })->whereNull('lampiran')->whereHas('jobdesks');
                break;
        }
    }

    private function getOrderResults($query, $paginate, $user)
    {
        if ($paginate === 'false' || $paginate === false) {
            $orders = $query->get();
            return response()->json($orders->map(function ($order) use ($user) {
                return $this->formatOrderResponse($order, $user);
            }));
        }

        $orders = $query->paginate(25);
        $orders->through(function ($order) use ($user) {
            return $this->formatOrderResponse($order, $user);
        });

        return response()->json($orders);
    }

    private function formatOrderResponse($order, $user)
    {
        // Calculate jobdesk count from loaded relation instead of separate query
        $jobdeskCount = $order->jobdesks ? $order->jobdesks->count() : 0;
        $completedJobdesks = $order->jobdesks ? $order->jobdesks->where('status', 'Selesai')->count() : 0;

        return [
            'id' => $order->id,
            'no_order' => $order->no_order,
            'customer_id' => $order->customer?->id,
            'pemberi_order' => $order->pemberi_order,
            'pemberi_phone' => $order->pemberi_phone,
            'maker_id' => $order->maker_id,
            'pic_id' => $order->pic_id,
            'maker_name' => $order->maker?->name,
            'pic_name' => $order->pic?->name,
            'order_date' => $order->order_date,
            'product_id' => $order->product?->id,
            'price' => $user->role !== 'staff' ? $order->price : 0,
            'payment_method' => $order->payment_method,
            'paid' => $user->role !== 'staff' ? $order->paid : 0,
            'billing_notes' => $order->billing_notes,
            'meta' => $order->meta,
            'lampiran' => $order->lampiran,
            'jobdesk_count' => $jobdeskCount,
            'completed_jobdesks' => $completedJobdesks, // Added for frontend progress calculation
            'progress_percentage' => $jobdeskCount > 0 ? round(($completedJobdesks / $jobdeskCount) * 100) : 0,
            'created_at' => $order->created_at,
            'customer' => $order->customer ? [
                'id' => $order->customer->id,
                'name' => $order->customer->name,
                'phone' => $order->customer->phone,
                'phones' => $order->customer->phones,
                'address' => $order->customer->address,
                'meta' => $order->customer->meta ? $order->customer->meta->map(function ($meta) {
                    return [
                        'id' => $meta->id,
                        'meta_key' => $meta->meta_key,
                        'meta_value' => $meta->meta_value
                    ];
                }) : []
            ] : null,
            'related_order_id' => $order->related_order_id,
            'relation_type' => $order->relation_type,
            'related_order' => $order->relatedOrder ? [
                'id' => $order->relatedOrder->id,
                'no_order' => $order->relatedOrder->no_order,
                'customer' => $order->relatedOrder->customer ? [
                    'id' => $order->relatedOrder->customer->id,
                    'name' => $order->relatedOrder->customer->name,
                    'phone' => $order->relatedOrder->customer->phone,
                    'phones' => $order->relatedOrder->customer->phones,
                    'address' => $order->relatedOrder->customer->address,
                ] : null,
            ] : null,
            'related_orders' => $order->relatedOrders ? $order->relatedOrders->map(function ($relatedOrder) {
                return [
                    'id' => $relatedOrder->id,
                    'no_order' => $relatedOrder->no_order,
                    'relation_type' => $relatedOrder->relation_type,
                    'customer' => $relatedOrder->customer ? [
                        'id' => $relatedOrder->customer->id,
                        'name' => $relatedOrder->customer->name,
                        'phone' => $relatedOrder->customer->phone,
                        'phones' => $relatedOrder->customer->phones,
                        'address' => $relatedOrder->customer->address,
                    ] : null,
                ];
            }) : [],
            'jobdesks' => $order->jobdesks ? $order->jobdesks->map(function ($jobdesk) {
                return [
                    'id' => $jobdesk->id,
                    'status' => $jobdesk->status,
                    'description' => $jobdesk->description ?? '-'
                ];
            }) : [],
            'product' => $order->product ? [
                'id' => $order->product->id,
                'name' => $order->product->name,
                'category' => $order->product->category,
                'description' => $order->product->description,
                'meta_products' => $order->product->metaProducts ? $order->product->metaProducts->map(function ($metaProduct) {
                    return $metaProduct->meta ? [
                        'id' => $metaProduct->meta->id,
                        'name' => $metaProduct->meta->name,
                        'type' => $metaProduct->meta->type
                    ] : null;
                })->filter() : [],
            ] : null,
            'role' => $user->role,
            // Added fields for frontend convenience
            'is_completed' => $jobdeskCount > 0 && $completedJobdesks === $jobdeskCount,
            'has_lampiran' => !empty($order->lampiran),
            'last_jobdesk_status' => $order->jobdesks && $order->jobdesks->isNotEmpty()
                ? $this->getLastJobdeskStatus($order->jobdesks)
                : null,
        ];
    }

    private function getLastJobdeskStatus($jobdesks)
    {
        // Priority order as per frontend logic
        $priority = ['Progress', 'Masuk', 'Selesai'];

        foreach ($priority as $status) {
            $lastJobdesk = $jobdesks->where('status', $status)->last();
            if ($lastJobdesk) {
                return [
                    'status' => $status,
                    'description' => $lastJobdesk->description ?? '-'
                ];
            }
        }

        return [
            'status' => null,
            'description' => '-'
        ];
    }

    public function show(Order $order)
    {
        $order = Order::with([
            'customer',
            'customer.meta',
            'relatedOrder.customer',
            'relatedOrders.customer',
            'jobdesks',
            'product',
            'product.metaProducts.meta',
            'maker',
            'pic'
        ])->findOrFail($order->id);

        return response()->json($order);
    }

    public function update(Request $request, Order $order)
    {
        $user = $request->user();

        // Jika ada lampiran, validasi hanya file lampiran
        if ($request->hasFile('lampiran')) {
            $request->validate([
                'lampiran' => 'required|mimes:pdf|max:15000',
            ], [
                'lampiran.required' => 'Lampiran harus diisi.',
                'lampiran.mimes' => 'Lampiran harus berupa file PDF.',
                'lampiran.max' => 'Lampiran tidak boleh lebih besar dari 15MB.',
            ]);

            // Simpan file lampiran baru
            $filePath = $request->file('lampiran')->store('lampiran', 'public');
            // simpan lengkap dengan asset
            $filePath = asset('storage/' . $filePath);

            // Hapus dokumen lama jika ada
            if ($order->lampiran) {
                Storage::disk('public')->delete($order->lampiran);
            }

            // Update order dengan lampiran baru
            $order->update(['lampiran' => $filePath]);
        } else {
            // Jika tidak ada lampiran, validasi field lain
            $validatedData = $request->validate([
                'order_date' => 'required',
                'product_id' => 'required',
                'price' => 'required',
                'paid' => 'nullable',
                'payment_method' => 'required',
                'meta' => 'nullable',
                'customer' => 'required',
                'related_order_id' => 'nullable|exists:orders,id',
                'relation_type' => 'nullable|in:penjual,pembeli,lainnya',
                'pemberi_order' => 'nullable|string|max:255',
                'pemberi_phone' => 'nullable|string|max:50',
                'billing_notes' => 'nullable|string|max:10000',
                'maker_id' => 'nullable|exists:users,id',
                'pic_id' => 'nullable|exists:users,id',
            ], [
                'order_date.required' => 'Tanggal pesanan harus diisi.',
                'product_id.required' => 'Produk harus dipilih.',
                'price.required' => 'Harga harus diisi.',
                'payment_method.required' => 'Metode pembayaran harus dipilih.',
                'customer.required' => 'Pelanggan harus diisi.',
            ]);

            // Update order dengan data yang sudah divalidasi
            $order->update($validatedData);
        }

        // Load relasi dan siapkan respons
        $order->load('customer', 'jobdesks', 'product', 'product.metaProducts.meta');
        $response = [
            'id' => $order->id,
            'no_order' => $order->no_order,
            'customer_id' => $order->customer->id,
            'order_date' => $order->order_date,
            'product_id' => $order->product->id,
            'price' => $user->role !== 'staff' ? $order->price : 0,
            'payment_method' => $order->payment_method,
            'paid' => $user->role !== 'staff' ? $order->paid : 0,
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
            'related_order_id' => 'nullable|exists:orders,id',
            'relation_type' => 'nullable|in:penjual,pembeli,lainnya',
            'pemberi_order' => 'nullable|string|max:255',
            'pemberi_phone' => 'nullable|string|max:50',
            'billing_notes' => 'nullable|string|max:10000',
            'maker_id' => 'nullable|exists:users,id',
            'pic_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        $order = Order::create($validator->validated());
        // set maker (created_by) on create
        if (!$order->created_by && $user) {
            $order->created_by = $user->id;
            $order->save();
        }
        $order->load('customer', 'jobdesks', 'product', 'product.metaProducts.meta');

        $users = User::whereHas('permissions', function ($query) {
            $query->where('name', 'menu:settings');
        })->get();

        // Tambahkan maker dan PIC sebagai penerima, tanpa duplikasi
        $extraRecipients = collect();
        if ($order->maker_id) {
            $maker = User::find($order->maker_id);
            if ($maker) $extraRecipients->push($maker);
        }
        if ($order->pic_id) {
            $pic = User::find($order->pic_id);
            if ($pic) $extraRecipients->push($pic);
        }
        $recipients = $users->concat($extraRecipients)->unique('id')->values();
        if ($recipients->isNotEmpty()) {
            Notification::send($recipients, new NewOrderNotification($order));
        }

        $response = [
            'id' => $order->id,
            'no_order' => $order->no_order,
            'customer_id' => $order->customer->id,
            'pemberi_order' => $order->pemberi_order,
            'pemberi_phone' => $order->pemberi_phone,
            'order_date' => $order->order_date,
            'product_id' => $order->product->id,
            'price' => $user->role !== 'staff' ? $order->price : 0,
            'payment_method' => $order->payment_method,
            'paid' => $user->role !== 'staff' ? $order->paid : 0,
            'billing_notes' => $order->billing_notes,
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

    /**
     * Get order statistics for dashboard
     */
    public function stats(Request $request)
    {
        $customerId = $request->query('customer_id');

        $query = Order::query();

        if (isset($customerId) && $customerId !== 'undefined') {
            $query->where('customer_id', $customerId);
        }

        $totalOrders = $query->count();

        // Count orders by status based on jobdesk completion
        $masukCount = (clone $query)->where(function ($q) {
            $q->whereHas('jobdesks', function ($query) {
                $query->where('status', '!=', 'Selesai');
            })->orWhereDoesntHave('jobdesks');
        })->count();

        $selesaiCount = (clone $query)->whereDoesntHave('jobdesks', function ($query) {
            $query->where('status', '!=', 'Selesai');
        })->whereNotNull('lampiran')->whereHas('jobdesks')->count();

        $arsipCount = (clone $query)->whereDoesntHave('jobdesks', function ($query) {
            $query->where('status', '!=', 'Selesai');
        })->whereNull('lampiran')->whereHas('jobdesks')->count();

        return response()->json([
            'total_orders' => $totalOrders,
            'masuk' => $masukCount,
            'selesai' => $selesaiCount,
            'arsip' => $arsipCount,
            'completion_rate' => $totalOrders > 0 ? round((($selesaiCount + $arsipCount) / $totalOrders) * 100, 2) : 0
        ]);
    }

    public function list(Request $request)
    {
        $excludeId = $request->query('exclude_id');

        $query = Order::with('customer')
            ->select(['id', 'no_order', 'customer_id', 'order_date'])
            ->orderBy('created_at', 'desc');

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $orders = $query->get()->map(function ($order) {
            return [
                'id' => $order->id,
                'no_order' => $order->no_order,
                'customer_name' => $order->customer?->name,
                'customer' => $order->customer ? [
                    'id' => $order->customer->id,
                    'name' => $order->customer->name,
                    'phone' => $order->customer->phone,
                    'phones' => $order->customer->phones,
                    'address' => $order->customer->address,
                ] : null,
            ];
        });

        return response()->json($orders);
    }

    /**
     * Generate printable PDF for an order
     */
    public function print(Order $order)
    {
        $order = Order::with([
            'customer',
            'customer.meta',
            'relatedOrder.customer',
            'relatedOrder.customer.meta',
            'relatedOrders.customer',
            'relatedOrders.customer.meta',
            'jobdesks:id,order_id,user_id,status,description',
            'jobdesks.user:id,name',
            'product:id,name,category,description',
            'maker:id,name',
            'pic:id,name'
        ])->findOrFail($order->id);

        $settings = \App\Models\Setting::all()->pluck('setting_value', 'setting_key')->toArray();

        $data = [
            'app_name' => $settings['app_name'] ?? 'KANTOR NOTARIS',
            'app_description' => $settings['app_description'] ?? '',
            'address' => $settings['address'] ?? '',
            'phone' => $settings['phone'] ?? '',
            'email' => $settings['email'] ?? '',
            'order' => $order,
            'maker' => $order->maker?->name ?: $order->jobdesks->pluck('user.name')->filter()->unique()->implode(', '),
            'pic' => $order->pic?->name ?: '',
        ];

        $pdf = Pdf::loadView('orders.print', $data)->setPaper([0, 0, 595.28, 935.43], 'portrait');

        $filename = 'Order-' . ($order->no_order ?? $order->id) . '.pdf';
        return $pdf->download($filename);
    }
}
