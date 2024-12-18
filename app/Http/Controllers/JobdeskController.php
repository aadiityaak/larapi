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
        $name = $request->query('name'); // New parameter for employee name

        // Initialize the query
        $query = Jobdesk::with('customer', 'order', 'user');

        // Filter by order_id if provided
        if ($orderId) {
            $query->where('order_id', $orderId);
        }

        // Filter by status if provided
        if ($status) {
            $query->where('status', $status);
        }

        if ($name && strlen($name) > 2) {
            $query->whereHas('customer', function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            });
        }

        // Paginate the results
        $jobdesk = $query->paginate(25);

        return response()->json($jobdesk);
    }

    public function show(Jobdesk $jobdesk)
    {
        $jobdesk = Jobdesk::find($jobdesk->id)->load('customer', 'order', 'user');
        return response()->json($jobdesk);
    }
}
