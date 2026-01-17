<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $logsQuery = Log::with('user');

        // Apply search filter if provided
        if ($request->filled('q')) {
            $q = $request->q;
            $logsQuery->where(function ($query) use ($q) {
                $query->where('aksi', 'like', "%{$q}%")
                      ->orWhereHas('user', function ($userQuery) use ($q) {
                          $userQuery->where('name', 'like', "%{$q}%")
                                   ->orWhere('email', 'like', "%{$q}%")
                                   ->orWhere('nip', 'like', "%{$q}%");
                      });
            });
        }

        $logs = $logsQuery
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('pages.admin.log.index', compact('logs'));
    }
}