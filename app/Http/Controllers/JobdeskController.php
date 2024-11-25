<?php

namespace App\Http\Controllers;

use App\Models\Jobdesk;
use Illuminate\Http\Request;

class JobdeskController extends Controller
{
    public function index()
    {
        $jobdesk = Jobdesk::with('customer', 'order', 'user')->paginate(25);
        return response()->json($jobdesk);
    }

    public function show(Jobdesk $jobdesk)
    {
        return response()->json($jobdesk);
    }
}
