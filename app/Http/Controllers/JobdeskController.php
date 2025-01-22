<?php

namespace App\Http\Controllers;

use App\Models\Jobdesk;
use Illuminate\Http\Request;

class JobdeskController extends Controller
{
    public function index(Request $request)
    {
        $orderId = $request->query('order_id');
        $status = $request->query('status');
        $name = $request->query('name');
        $userId = $request->query('user_id');

        // Initialize the query
        $query = Jobdesk::with('order', 'order.customer', 'user', 'order.product');

        // Filter by order_id if provided
        if ($orderId) {
            $query->where('order_id', $orderId);
        }

        // Filter by status if provided
        if ($status) {
            $query->where('status', $status);
        }

        // Filter by user_id if provided
        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($name && strlen($name) > 2) {
            $query->whereHas('order.customer', function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            });
        }

        // Paginate the results
        $jobdesk = $query->paginate(25);

        return response()->json($jobdesk);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'user_id' => 'required|exists:users,id',
            'tanggal_pengerjaan' => 'nullable',
            'tanggal_selesai' => 'nullable',
            'status' => 'required',
        ]);
        if (isset($validatedData['tanggal_pengerjaan'])) {
            $validatedData['tanggal_pengerjaan'] = date('Y-m-d', strtotime($validatedData['tanggal_pengerjaan']));
        }
        if (isset($validatedData['tanggal_selesai'])) {
            $validatedData['tanggal_selesai'] = date('Y-m-d', strtotime($validatedData['tanggal_selesai']));
        }
        $jobdesk = Jobdesk::create($validatedData);
        return response()->json($jobdesk);
    }

    public function update(Request $request, Jobdesk $jobdesk)
    {
        $jobdesk = Jobdesk::find($jobdesk->id);
        $validatedData = $request->validate(
            [
                'order_id' => 'required|exists:orders,id',
                'user_id' => 'required|exists:users,id',
                'deskripsi' => 'nullable',
                'tanggal_pengerjaan' => 'nullable',
                'tanggal_selesai' => 'nullable',
                'status' => 'required',
            ],
            [
                'order_id.required' => 'Pilih order yang akan dikerjakan.',
                'user_id.required' => 'Pilih karyawan yang akan mengerjakan jobdesk.',
                'status.required' => 'Status harus diisi.',
            ]
        );
        if (isset($validatedData['tanggal_pengerjaan'])) {
            $validatedData['tanggal_pengerjaan'] = date('Y-m-d', strtotime($validatedData['tanggal_pengerjaan']));
        }

        if (isset($validatedData['tanggal_selesai'])) {
            $validatedData['tanggal_selesai'] = date('Y-m-d', strtotime($validatedData['tanggal_selesai']));
        }
        $jobdesk->update($validatedData);
        return response()->json($jobdesk);
    }

    public function show(Jobdesk $jobdesk)
    {
        $jobdesk = Jobdesk::find($jobdesk->id)->load('customer', 'order', 'user');
        return response()->json($jobdesk);
    }

    public function destroy(Jobdesk $jobdesk)
    {
        $jobdesk = Jobdesk::find($jobdesk->id);
        $jobdesk->delete();
        return response()->json(['message' => 'Jobdesk deleted successfully']);
    }
}
