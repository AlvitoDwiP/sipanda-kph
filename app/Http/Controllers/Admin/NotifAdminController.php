<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataDiri;
use App\Models\User;
use Illuminate\Http\Request;

class NotifAdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q'); // Changed to 'q' to match header

        $userBaruQuery = User::where('status_akun', 'nonaktif')
            ->where('created_at', '>=', now()->subDays(2));

        $perubahanDataDiriQuery = DataDiri::where('updated_at', '>=', now()->subDays(2))
            ->with(['pegawai.user']);

        // Apply search filter if provided
        if ($search) {
            $userBaruQuery->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('nip', 'LIKE', "%{$search}%");
            });

            $perubahanDataDiriQuery->whereHas('pegawai.user', function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('nip', 'LIKE', "%{$search}%");
            });
        }

        $userBaru = $userBaruQuery->orderBy('created_at', 'desc')->get();
        $perubahanDataDiri = $perubahanDataDiriQuery->orderBy('updated_at', 'desc')->get();

        return view(
            'pages.admin.notifikasi.index',
            compact('userBaru', 'perubahanDataDiri', 'search')
        );
    }
}
