<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $role = $request->user()->role;
        $dashboardRoute = match ($role) {
            'super_admin', 'admin' => 'admin.dashboard',
            'kph' => 'kph.dashboard',
            'operator_display' => 'operator-display.display-jobdesk.manage',
            'pegawai' => 'pegawai.dashboard',
            default => 'login',
        };

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route($dashboardRoute, absolute: false).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(route($dashboardRoute, absolute: false).'?verified=1');
    }
}
