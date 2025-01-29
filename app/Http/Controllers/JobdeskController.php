<?php

namespace App\Http\Controllers;

use App\Models\JobDesk;
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
        $query = JobDesk::with('order', 'order.customer', 'user', 'order.product');

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
        $jobdesk = $query->orderBy('id', 'desc')->paginate(25);

        return response()->json($jobdesk);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'user_id' => 'required|exists:users,id',
                'description' => 'required|string',
                'tanggal_pengerjaan' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date',
                'status' => 'nullable|string',
            ]);

            if (isset($validatedData['tanggal_pengerjaan'])) {
                $validatedData['tanggal_pengerjaan'] = date('Y-m-d', strtotime($validatedData['tanggal_pengerjaan']));
            }
            if (isset($validatedData['tanggal_selesai'])) {
                $validatedData['tanggal_selesai'] = date('Y-m-d', strtotime($validatedData['tanggal_selesai']));
            }
            $jobdesk = JobDesk::create($validatedData);
            // relation
            $jobdesk->load('order', 'order.customer', 'user', 'order.product');
            return response()->json($jobdesk, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Server Error', 'error' => $e->getMessage()], 500);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $jobdesk = JobDesk::find($id);
            $validatedData = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'user_id' => 'required|exists:users,id',
                'description' => 'required|string',
                'tanggal_pengerjaan' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date',
                'status' => 'nullable|string',
            ]);
            $jobdesk->update($validatedData);
            $jobdesk->load('order', 'order.customer', 'user', 'order.product');
            return response()->json($jobdesk);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(JobDesk $jobdesk)
    {
        $jobdesk = JobDesk::find($jobdesk->id)->load('order', 'order.customer', 'user', 'order.product');
        return response()->json($jobdesk);
    }

    public function destroy(JobDesk $jobdesk)
    {
        $jobdesk = JobDesk::find($jobdesk->id);
        $jobdesk->delete();
        return response()->json(['message' => 'JobDesk deleted successfully']);
    }
}
