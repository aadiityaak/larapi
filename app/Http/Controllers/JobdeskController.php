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

        if ($orderId) {
            $jobdesk = Jobdesk::with('customer', 'order', 'user')->where('order_id', $orderId)->paginate(25);
        } else {
            if ($status) {
                $jobdesk = Jobdesk::with('customer', 'order', 'user')->where('status', $status)->paginate(25);
            } else {
                $jobdesk = Jobdesk::with('customer', 'order', 'user')->paginate(25);
            }
        }
        return response()->json($jobdesk);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'user_id' => 'required|exists:users,id',
            'tanggal_pengerjaan' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'status' => 'required',
        ]);
        $jobdesk = Jobdesk::create($validatedData);
        return response()->json($jobdesk);
    }

    public function update(Request $request, Jobdesk $jobdesk)
    {
        $jobdesk = Jobdesk::find($jobdesk->id);
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'user_id' => 'required|exists:users,id',
            'tanggal_pengerjaan' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'status' => 'required',
        ]);
        $jobdesk->update($validatedData);
        return response()->json($jobdesk);
    }

    public function show(Jobdesk $jobdesk)
    {
        $jobdesk = Jobdesk::find($jobdesk->id)->load('customer', 'order', 'user');
        return response()->json($jobdesk);
    }
}
