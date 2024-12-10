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

    public function show(Jobdesk $jobdesk)
    {
        return response()->json($jobdesk);
    }
}
