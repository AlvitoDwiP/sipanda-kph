<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataDiri;
use App\Models\User;
use Illuminate\Http\Request;

class NotifAdminController extends Controller
{
    public function index()
    {
        $userBaru = User::where('status_akun', 'nonaktif')
            ->where('created_at', '>=', now()->subDays(2))
            ->orderBy('created_at', 'desc')
            ->get();

        $perubahanDataDiri = DataDiri::where('updated_at', '>=', now()->subDays(2))
            ->orderBy('updated_at', 'desc')
            ->get();

        return view(
            'pages.admin.notifikasi.index',
            compact('userBaru', 'perubahanDataDiri')
        );
    }
}
