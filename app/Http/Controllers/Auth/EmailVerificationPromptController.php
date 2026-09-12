<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
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

        return view('auth.verify-email');
    }
}
