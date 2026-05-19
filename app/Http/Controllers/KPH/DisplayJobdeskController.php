<?php

namespace App\Http\Controllers\KPH;

use App\Http\Controllers\Controller;
use App\Models\DisplayJobdeskSetting;
use App\Models\DisplayScanLog;
use App\Services\DisplayJobdeskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisplayJobdeskController extends Controller
{
    public function manage()
    {
        $setting = DisplayJobdeskSetting::active();
        $todayStart = now()->startOfDay();

        $logs = DisplayScanLog::with('pegawai.user')
            ->where('scanned_at', '>=', $todayStart)
            ->latest('scanned_at')
            ->limit(20)
            ->get();

        $summary = [
            'total' => $logs->count(),
            'success' => $logs->where('status', 'success')->count(),
            'invalid_token' => $logs->where('status', 'invalid_token')->count(),
            'inactive_employee' => $logs->where('status', 'inactive_employee')->count(),
            'empty_task' => $logs->where('status', 'empty_task')->count(),
            'last_scan_at' => optional($logs->first())->scanned_at,
        ];

        return view('pages.display.manage', [
            'scope' => 'kph',
            'pageTitle' => 'Display Job Desk Harian',
            'tvRoute' => route('kph.display-jobdesk.tv'),
            'stateRoute' => route('kph.display-jobdesk.state'),
            'settingUpdateRoute' => route('kph.display-jobdesk.settings.update'),
            'resetRoute' => route('kph.display-jobdesk.reset'),
            'setting' => $setting,
            'logs' => $logs,
            'summary' => $summary,
        ]);
    }

    public function tv()
    {
        $setting = DisplayJobdeskSetting::active();

        return view('pages.display.jobdesk', [
            'scanRoute' => route('kph.display-jobdesk.scan'),
            'stateRoute' => route('kph.display-jobdesk.state'),
            'backRoute' => route('kph.display-jobdesk.manage'),
            'pageTitle' => 'Display Job Desk Harian',
            'displayDurationSeconds' => (int) $setting->display_duration_seconds,
            'showEmployeePhoto' => (bool) $setting->show_employee_photo,
        ]);
    }

    public function index()
    {
        return redirect()->route('kph.display-jobdesk.manage');
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'display_duration_seconds' => 'required|integer|min:5|max:60',
            'show_employee_photo' => 'nullable|boolean',
        ]);

        $setting = DisplayJobdeskSetting::active();
        $setting->update([
            'display_duration_seconds' => $validated['display_duration_seconds'],
            'show_employee_photo' => $request->boolean('show_employee_photo'),
        ]);

        return back()->with('success', 'Pengaturan display berhasil diperbarui.');
    }

    public function reset()
    {
        $setting = DisplayJobdeskSetting::active();
        $setting->update(['reset_requested_at' => now()]);

        return back()->with('success', 'Reset tampilan berhasil dikirim.');
    }

    public function state()
    {
        $setting = DisplayJobdeskSetting::active();

        return response()->json([
            'reset_requested_at' => optional($setting->reset_requested_at)->toIso8601String(),
            'display_duration_seconds' => (int) $setting->display_duration_seconds,
            'show_employee_photo' => (bool) $setting->show_employee_photo,
        ]);
    }

    public function scan(Request $request, DisplayJobdeskService $displayJobdeskService)
    {
        $validated = $request->validate([
            'token' => 'required|string|max:64',
        ]);

        $response = $displayJobdeskService->scanToken($validated['token']);
        $setting = DisplayJobdeskSetting::active();

        if (!$setting->show_employee_photo && isset($response['pegawai'])) {
            $response['pegawai']['foto_url'] = null;
        }

        $this->logScan($validated['token'], $response);

        unset($response['_pegawai_id'], $response['_task_count']);

        return response()->json($response);
    }

    private function logScan(string $token, array $response): void
    {
        try {
            DisplayScanLog::create([
                'scanned_at' => now(),
                'qr_token_hash' => hash('sha256', strtoupper(trim($token))),
                'pegawai_id' => $response['_pegawai_id'] ?? null,
                'scanned_by' => Auth::id(),
                'status' => $response['status'] ?? 'error',
                'task_count' => (int) ($response['_task_count'] ?? 0),
                'message' => $response['message'] ?? null,
            ]);
        } catch (\Throwable $th) {
            report($th);
        }
    }
}
