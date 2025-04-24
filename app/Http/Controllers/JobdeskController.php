<?php

namespace App\Http\Controllers;

use App\Models\Jobdesk;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JobdeskController extends Controller
{
    public function index(Request $request)
    {
        $orderId = $request->query('order_id');
        $status = $request->query('status');
        $name = $request->query('name');
        $userId = $request->query('user_id');
        $dari = $request->query('dari');
        $sampai = $request->query('sampai');
        $user = $request->user();

        // Initialize the query
        $query = Jobdesk::with('order', 'order.customer', 'user', 'order.product');

        // Filter by order_id if provided
        if ($orderId) {
            $query->where('order_id', $orderId);
        }

        if (in_array($user->position, ['Staff'])) {
            $query->where('user_id', $user->id);
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

        if ($dari && $sampai) {
            $query->whereBetween('order.order_date', [$dari, $sampai]);
        }

        if ($dari && !$sampai) {
            $query->where('order.order_date', '>=', $dari);
        }

        if (!$dari && $sampai) {
            $query->where('order.order_date', '<=', $sampai);
        }

        // Paginate the results
        $jobdesk = $query->orderBy('id', 'asc')->paginate(25);

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

            $validatedData['tanggal_pengerjaan'] = $validatedData['tanggal_pengerjaan'] ? Carbon::parse($validatedData['tanggal_pengerjaan'])->setTimezone('Asia/Jakarta')->startOfDay() : null;
            $validatedData['tanggal_selesai'] = $validatedData['tanggal_selesai'] ? Carbon::parse($validatedData['tanggal_selesai'])->setTimezone('Asia/Jakarta')->endOfDay() : null;

            $jobdesk = Jobdesk::create($validatedData);
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
            $jobdesk = Jobdesk::find($id);
            $validatedData = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'user_id' => 'required|exists:users,id',
                'description' => 'required|string',
                'tanggal_pengerjaan' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date',
                'status' => 'nullable|string',
            ]);

            $validatedData['tanggal_pengerjaan'] = $validatedData['tanggal_pengerjaan'] ? Carbon::parse($validatedData['tanggal_pengerjaan'])->setTimezone('Asia/Jakarta')->startOfDay() : null;
            $validatedData['tanggal_selesai'] = $validatedData['tanggal_selesai'] ? Carbon::parse($validatedData['tanggal_selesai'])->setTimezone('Asia/Jakarta')->endOfDay() : null;

            $jobdesk->update($validatedData);
            $jobdesk->load('order', 'order.customer', 'user', 'order.product');
            return response()->json($jobdesk);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(Jobdesk $jobdesk)
    {
        $jobdesk = Jobdesk::find($jobdesk->id)->load('order', 'order.customer', 'user', 'order.product');
        return response()->json($jobdesk);
    }

    public function destroy(Jobdesk $jobdesk)
    {
        $jobdesk = Jobdesk::find($jobdesk->id);
        $jobdesk->delete();
        return response()->json(['message' => 'Jobdesk deleted successfully']);
    }
}
