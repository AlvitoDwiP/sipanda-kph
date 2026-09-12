<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            $role = $request->user()->role;
            $dashboardRoute = match ($role) {
                'super_admin', 'admin' => 'admin.dashboard',
                'kph' => 'kph.dashboard',
                'operator_display' => 'operator-display.display-jobdesk.manage',
                'pegawai' => 'pegawai.dashboard',
                default => 'login',
            };

            return redirect()->intended(route($dashboardRoute, absolute: false));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
