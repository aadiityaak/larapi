<?php

namespace App\Http\Controllers;

use App\Models\Customer;
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

        $query = Customer::with('orders');

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
                    'orders' => $data->orders
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
                    'orders' => $data->orders
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
            ],
            [
                'name.required' => 'Nama harus diisi.',
                'phone.required' => 'Nomor telepon harus diisi.',
                'phone.unique' => 'Nomor telepon sudah ada.',
                'address.required' => 'Alamat harus diisi.',
            ]
        );

        $customer = Customer::create($validatedData);
        $response = [
            'data' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'address' => $customer->address,
                'order_count' => $customer->orders->count(),
                'orders' => $customer->orders
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
            ]
        );
        $customer->update($validatedData);
        $customer->load('orders');

        $response = [
            'data' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'address' => $customer->address,
                'order_count' => $customer->orders->count(),
                'orders' => $customer->orders
            ]
        ];
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->json($customer);
    }
}
