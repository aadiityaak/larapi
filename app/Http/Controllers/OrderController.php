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

        if ($name && strlen($name) > 2 && !$customerId) {
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
        } else if (!($customerId || $name)) {
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
        // {
        //     "current_page": 1,
        //     "data": [
        //         {
        //             "id": 85,
        //             "no_order": "AN00085",
        //             "customer_id": "29",
        //             "order_date": "2025-01-23",
        //             "product_id": "22",
        //             "price": 100000,
        //             "payment_method": "Tunai",
        //             "paid": 50000,
        //             "data": [],
        //             "lampiran": null,
        //             "created_at": "2025-01-23T06:30:49.000000Z",
        //             "updated_at": "2025-01-24T07:37:35.000000Z",
        //             "customer": {
        //                 "id": 29,
        //                 "name": "Aditya Kristyanto",
        //                 "phone": "087715567339",
        //                 "alamat": "Tunggul, Jarum, Bayat",
        //                 "created_at": "2025-01-23T06:21:08.000000Z",
        //                 "updated_at": "2025-01-24T05:59:50.000000Z"
        //             },
        //             "jobdesks": [],
        //             "product": {
        //                 "id": 22,
        //                 "name": "BPRS Madina Mandiri",
        //                 "price": "4761810",
        //                 "category": "bank",
        //                 "description": "-",
        //                 "created_at": "2025-01-23T03:28:38.000000Z",
        //                 "updated_at": "2025-01-23T03:28:38.000000Z",
        //                 "data_products": []
        //             }
        //         },
        //         {
        //             "id": 84,
        //             "no_order": "AN00084",
        //             "customer_id": "3",
        //             "order_date": "2025-01-23",
        //             "product_id": "22",
        //             "price": 100000,
        //             "payment_method": "Tunai",
        //             "paid": null,
        //             "data": [],
        //             "lampiran": null,
        //             "created_at": "2025-01-23T06:20:44.000000Z",
        //             "updated_at": "2025-01-24T06:48:29.000000Z",
        //             "customer": {
        //                 "id": 3,
        //                 "name": "Patricia Yuliarti",
        //                 "phone": "0460 7975 746",
        //                 "alamat": "Ds. Ir. H. Juanda No. 315, Salatiga 95933, Jambi",
        //                 "created_at": "2025-01-23T03:28:38.000000Z",
        //                 "updated_at": "2025-01-23T03:28:38.000000Z"
        //             },
        //             "jobdesks": [],
        //             "product": {
        //                 "id": 22,
        //                 "name": "BPRS Madina Mandiri",
        //                 "price": "4761810",
        //                 "category": "bank",
        //                 "description": "-",
        //                 "created_at": "2025-01-23T03:28:38.000000Z",
        //                 "updated_at": "2025-01-23T03:28:38.000000Z",
        //                 "data_products": []
        //             }
        //         },
        //         {
        //             "id": 62,
        //             "no_order": "AN00062",
        //             "customer_id": "22",
        //             "order_date": "2025-01-22",
        //             "product_id": "1",
        //             "price": 7428569,
        //             "payment_method": "Tunai",
        //             "paid": 5070730,
        //             "data": null,
        //             "lampiran": null,
        //             "created_at": "2025-01-23T03:28:39.000000Z",
        //             "updated_at": "2025-01-24T06:55:03.000000Z",
        //             "customer": {
        //                 "id": 22,
        //                 "name": "Iriana Palastri",
        //                 "phone": "(+62) 936 4097 394",
        //                 "alamat": "Dk. Flores No. 674, Semarang 83218, Sumsel",
        //                 "created_at": "2025-01-23T03:28:38.000000Z",
        //                 "updated_at": "2025-01-23T03:28:38.000000Z"
        //             },
        //             "jobdesks": [
        //                 {
        //                     "id": 211,
        //                     "order_id": "62",
        //                     "deskripsi": "Saepe inventore qui beatae.",
        //                     "user_id": "6",
        //                     "tanggal_pengerjaan": "2025-01-14",
        //                     "tanggal_selesai": "2025-01-23",
        //                     "status": "Selesai",
        //                     "created_at": "2025-01-23T03:28:39.000000Z",
        //                     "updated_at": "2025-01-23T03:28:39.000000Z"
        //                 },
        //                 {
        //                     "id": 212,
        //                     "order_id": "62",
        //                     "deskripsi": "Qui magni et natus omnis labore.",
        //                     "user_id": "3",
        //                     "tanggal_pengerjaan": "2025-01-17",
        //                     "tanggal_selesai": "2025-01-23",
        //                     "status": "Selesai",
        //                     "created_at": "2025-01-23T03:28:39.000000Z",
        //                     "updated_at": "2025-01-23T03:28:39.000000Z"
        //                 },
        //                 {
        //                     "id": 213,
        //                     "order_id": "62",
        //                     "deskripsi": "Quo voluptas numquam facilis est dignissimos.",
        //                     "user_id": "6",
        //                     "tanggal_pengerjaan": "2025-01-16",
        //                     "tanggal_selesai": null,
        //                     "status": "Progress",
        //                     "created_at": "2025-01-23T03:28:39.000000Z",
        //                     "updated_at": "2025-01-23T03:28:39.000000Z"
        //                 },
        //                 {
        //                     "id": 214,
        //                     "order_id": "62",
        //                     "deskripsi": "Enim quia neque repudiandae consequatur.",
        //                     "user_id": "6",
        //                     "tanggal_pengerjaan": "2025-01-18",
        //                     "tanggal_selesai": null,
        //                     "status": "Progress",
        //                     "created_at": "2025-01-23T03:28:39.000000Z",
        //                     "updated_at": "2025-01-23T03:28:39.000000Z"
        //                 }
        //             ],
        //             "product": {
        //                 "id": 1,
        //                 "name": "Perjanjian Kredit",
        //                 "price": "600000",
        //                 "category": "perorangan",
        //                 "description": "-",
        //                 "created_at": "2025-01-23T03:28:38.000000Z",
        //                 "updated_at": "2025-01-24T04:14:36.000000Z",
        //                 "data_products": [
        //                     {
        //                         "id": 157,
        //                         "data_id": 1,
        //                         "product_id": 1,
        //                         "created_at": "2025-01-24T04:14:36.000000Z",
        //                         "updated_at": "2025-01-24T04:14:36.000000Z",
        //                         "data": {
        //                             "id": 1,
        //                             "name": "Judul Akta",
        //                             "type": "text",
        //                             "created_at": "2025-01-23T03:28:38.000000Z",
        //                             "updated_at": "2025-01-23T03:28:38.000000Z"
        //                         }
        //                     },
        //                     {
        //                         "id": 158,
        //                         "data_id": 2,
        //                         "product_id": 1,
        //                         "created_at": "2025-01-24T04:14:36.000000Z",
        //                         "updated_at": "2025-01-24T04:14:36.000000Z",
        //                         "data": {
        //                             "id": 2,
        //                             "name": "Nomor Akta",
        //                             "type": "text",
        //                             "created_at": "2025-01-23T03:28:38.000000Z",
        //                             "updated_at": "2025-01-23T03:28:38.000000Z"
        //                         }
        //                     },
        //                     {
        //                         "id": 159,
        //                         "data_id": 3,
        //                         "product_id": 1,
        //                         "created_at": "2025-01-24T04:14:36.000000Z",
        //                         "updated_at": "2025-01-24T04:14:36.000000Z",
        //                         "data": {
        //                             "id": 3,
        //                             "name": "Tanggal Akta",
        //                             "type": "date",
        //                             "created_at": "2025-01-23T03:28:38.000000Z",
        //                             "updated_at": "2025-01-23T03:28:38.000000Z"
        //                         }
        //                     },
        //                     {
        //                         "id": 160,
        //                         "data_id": 4,
        //                         "product_id": 1,
        //                         "created_at": "2025-01-24T04:14:36.000000Z",
        //                         "updated_at": "2025-01-24T04:14:36.000000Z",
        //                         "data": {
        //                             "id": 4,
        //                             "name": "Yang Mengerjakan",
        //                             "type": "text",
        //                             "created_at": "2025-01-23T03:28:38.000000Z",
        //                             "updated_at": "2025-01-23T03:28:38.000000Z"
        //                         }
        //                     },
        //                     {
        //                         "id": 161,
        //                         "data_id": 5,
        //                         "product_id": 1,
        //                         "created_at": "2025-01-24T04:14:36.000000Z",
        //                         "updated_at": "2025-01-24T04:14:36.000000Z",
        //                         "data": {
        //                             "id": 5,
        //                             "name": "Jumlah Pinjaman",
        //                             "type": "number",
        //                             "created_at": "2025-01-23T03:28:38.000000Z",
        //                             "updated_at": "2025-01-23T03:28:38.000000Z"
        //                         }
        //                     },
        //                     {
        //                         "id": 162,
        //                         "data_id": 6,
        //                         "product_id": 1,
        //                         "created_at": "2025-01-24T04:14:36.000000Z",
        //                         "updated_at": "2025-01-24T04:14:36.000000Z",
        //                         "data": {
        //                             "id": 6,
        //                             "name": "Lain-lain",
        //                             "type": "text",
        //                             "created_at": "2025-01-23T03:28:38.000000Z",
        //                             "updated_at": "2025-01-23T03:28:38.000000Z"
        //                         }
        //                     }
        //                 ]
        //             }
        //         },
        // $orders->getCollection()->transform(function ($data) {
        //     return [
        //         'id' => $data->id,
        //         'no_order' => $data->no_order,
        //         'customer_id' => $data->customer->id,
        //         'order_date' => $data->order_date,
        //         'product_id' => $data->product->id,
        //         'price' => $data->price,
        //     ];
        // });

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
        $order->delete();
        return response()->json($order);
    }
}
