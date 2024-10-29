<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $customers = Customer::all();
        return response()->json($customers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:15',
            'kategori' => 'required|string|max:50',
            'pekerjaan' => 'required|string|max:50',
            'bank' => 'required|string|max:50',
            'sertifikat' => 'required|string|max:50',
            'nilai_transaksi' => 'required|numeric',
            'harga_real' => 'required|numeric',
            'harga_kesepakatan' => 'required|numeric',
            'data_pajak_pembeli' => 'required|string|max:100',
            'data_pajak_penjual' => 'required|string|max:100',
        ]);

        $customer = Customer::create($validatedData);
        return response()->json($customer, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer): JsonResponse
    {
        return response()->json($customer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'string|max:100',
            'alamat' => 'string|max:255',
            'whatsapp' => 'string|max:15',
            'kategori' => 'string|max:50',
            'pekerjaan' => 'string|max:50',
            'bank' => 'string|max:50',
            'sertifikat' => 'string|max:50',
            'nilai_transaksi' => 'numeric',
            'harga_real' => 'numeric',
            'harga_kesepakatan' => 'numeric',
            'data_pajak_pembeli' => 'string|max:100',
            'data_pajak_penjual' => 'string|max:100',
        ]);

        $customer->update($validatedData);
        return response()->json($customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();
        return response()->json(null, 204);
    }
}
