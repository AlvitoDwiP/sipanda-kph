<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnitKerja;
use App\Services\DashboardMonitoringService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, DashboardMonitoringService $dashboardMonitoringService)
    {
        $validated = $request->validate([
            'tanggal' => 'nullable|date',
            'unit_kerja_id' => 'nullable|integer|exists:ref_unitkerja,id',
        ]);

        $tanggal = isset($validated['tanggal'])
            ? Carbon::parse($validated['tanggal'])
            : Carbon::today();
        $unitKerjaId = $validated['unit_kerja_id'] ?? null;

        $dashboardData = $dashboardMonitoringService->getDashboardData($tanggal, $unitKerjaId);

        $unitKerjaList = UnitKerja::orderBy('nama_unitkerja')->get();

        return view('pages.admin.index', array_merge($dashboardData, [
            'tanggalFilter' => $tanggal->toDateString(),
            'unitKerjaId' => $unitKerjaId,
            'unitKerjaList' => $unitKerjaList,
            'routePrefix' => 'admin',
            'dashboardTitle' => 'Dashboard Monitoring Admin',
        ]));
    }
}
