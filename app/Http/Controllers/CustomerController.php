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
        'alamat' => 'required|string',
        'bank' => 'max:255',
        'kategori' => 'required|string',
        'pekerjaan' => 'max:255',
        'sertifikat' => 'required|string',
        'nilai_transaksi' => 'required|numeric',
        'harga_real' => 'required|numeric',
        'harga_kesepakatan' => 'required|numeric',
        'data_pajak_pembeli' => 'required|numeric',
        'data_pajak_penjual' => 'required|numeric',
    ];

    public function index(Request $request)
    {
        // Initialize the query
        $query = Customer::with('orders');

        // Filter by name if provided and longer than 3 characters
        if ($request->has('name') && strlen($request->input('name')) > 2) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        // Filter by phone if provided and longer than 3 characters
        if ($request->has('phone') && strlen($request->input('phone')) > 3) {
            $query->where('phone', 'like', '%' . $request->input('phone') . '%');
        }

        // Paginate the results
        $customers = $query->paginate(25);

        return response()->json($customers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate($this->validate);

        $customer = Customer::create($validatedData);
        return response()->json($customer);
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
        $validatedData = $request->validate($this->validate);
        $customer->update($validatedData);
        return response()->json($customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer = Customer::find($customer->id);
        // saat hapus customer harus hapus semua order nya
        $customer->orders()->delete();
        $customer->delete();
        return response()->json($customer);
    }
}
