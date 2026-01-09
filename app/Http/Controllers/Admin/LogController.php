<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $logs = Log::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('pages.admin.log.index', compact('logs'));
    }
}