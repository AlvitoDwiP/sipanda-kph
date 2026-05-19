<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DisplayJobdeskService;
use Illuminate\Http\Request;

class DisplayJobdeskController extends Controller
{
    public function index()
    {
        return view('pages.display.jobdesk', [
            'scanRoute' => route('admin.display-jobdesk.scan'),
            'backRoute' => route('admin.dashboard'),
            'pageTitle' => 'Display Job Desk Harian',
        ]);
    }

    public function scan(Request $request, DisplayJobdeskService $displayJobdeskService)
    {
        $validated = $request->validate([
            'token' => 'required|string|max:64',
        ]);

        return response()->json($displayJobdeskService->scanToken($validated['token']));
    }
}
