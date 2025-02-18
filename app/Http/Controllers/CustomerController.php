<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerMeta;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $validate = [
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'address' => 'required|string',
    ];

    public function index(Request $request)
    {
        $paginate = $request->query('paginate');
        $validated = $request->validate([
            'name' => 'nullable|string|min:3',
            'phone' => 'nullable|string|min:4',
        ]);

        $query = Customer::with('orders', 'meta');

        if (!empty($validated['name'])) {
            $query->where('name', 'like', '%' . $validated['name'] . '%');
        }

        if (!empty($validated['phone'])) {
            $query->where('phone', 'like', '%' . $validated['phone'] . '%');
        }

        $query->orderBy('created_at', 'desc');

        if ($paginate === 'false') {
            $customers = $query->get()->map(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'phone' => $data->phone,
                    'address' => $data->address,
                    'order_count' => $data->orders->count(),
                    'orders' => $data->orders,
                    'meta' => $data->meta
                ];
            });
        } else {
            $customers = $query->paginate(25);
            $customers->getCollection()->transform(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'phone' => $data->phone,
                    'address' => $data->address,
                    'order_count' => $data->orders->count(),
                    'orders' => $data->orders,
                    'meta' => $data->meta
                ];
            });
        }
        return response()->json($customers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20|unique:customers,phone',
                'address' => 'required|string',
                'meta' => 'nullable|array',
            ],
            [
                'name.required' => 'Nama harus diisi.',
                'phone.required' => 'Nomor telepon harus diisi.',
                'phone.unique' => 'Nomor telepon sudah ada.',
                'address.required' => 'Alamat harus diisi.',
                'meta.array' => 'Meta harus berupa array.',
            ]
        );

        $customer = Customer::create($validatedData);
        if (isset($validatedData['meta'])) {
            $customer->meta()->createMany($validatedData['meta']);
        }
        $response = [
            'data' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'address' => $customer->address,
                'order_count' => $customer->orders->count(),
                'orders' => $customer->orders,
                'meta' => $customer->meta
            ]
        ];
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $customer = Customer::find($customer->id);
        return response()->json($customer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $customer = Customer::find($customer->id);
        $validatedData = $request->validate(
            [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20|unique:customers,phone,' . $customer->id,
                'address' => 'required|string',
                'meta' => 'nullable|array',
            ],
            [
                'name.required' => 'Nama harus diisi.',
                'phone.required' => 'Nomor telepon harus diisi.',
                'phone.unique' => 'Nomor telepon sudah ada.',
                'address.required' => 'Alamat harus diisi.',
                'meta.array' => 'Meta harus berupa array.',
            ]
        );
        $customer->update($validatedData);
        if (isset($validatedData['meta'])) {
            // tanpa delete meta yang ada, update jika key sama 
            foreach ($validatedData['meta'] as $metaData) {
                $meta = CustomerMeta::updateOrCreate(
                    ['customer_id' => $customer->id, 'meta_key' => $metaData['meta_key']]
                );
                $meta->meta_value = $metaData['meta_value'];
                $meta->save();
            }
        }
        $customer->load('orders', 'meta');

        $response = [
            'data' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'address' => $customer->address,
                'order_count' => $customer->orders->count(),
                'orders' => $customer->orders,
                'meta' => $customer->meta
            ]
        ];
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        // Retrieve all orders for the customer
        $orders = $customer->orders;

        // Loop through each order and delete related jobdesks
        foreach ($orders as $order) {
            $order->jobdesks()->delete(); // Ensure jobdesks relationship exists on Order
        }

        // Now delete the orders themselves
        $customer->orders()->delete();

        // Finally, delete the customer
        $customer->delete();

        return response()->json($customer);
    }

    public function storeMeta(Request $request, Customer $customer)
    {
        $validatedData = $request->validate([
            'meta' => 'required|array',
            'meta.*.meta_key' => 'required|string|max:100',
            'meta.*.meta_value' => 'required|string',
        ]);

        foreach ($validatedData['meta'] as $metaData) {
            $meta = new CustomerMeta();
            $meta->customer_id = $customer->id;
            $meta->meta_key = $metaData['meta_key'];
            $meta->meta_value = $metaData['meta_value'];
            $meta->save();
        }

        return response()->json(['message' => 'Data meta berhasil disimpan.']);
    }
}
