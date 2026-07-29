<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;

class TwoFactorController extends Controller
{
    protected TwoFactorService $twoFactorService;

    public function __construct(TwoFactorService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
    }

    /**
     * Show the 2FA verification form.
     */
    public function show()
    {
        return view('auth.2fa.verify');
    }

    /**
     * Verify the 2FA code.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        if ($this->twoFactorService->verify($request->user(), $request->code)) {
            $request->session()->put('2fa_passed', true);
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['code' => 'Invalid verification code.']);
    }

    /**
     * Resend the 2FA code.
     */
    public function resend(Request $request)
    {
        $this->twoFactorService->sendCode($request->user());
        return back()->with('status', 'Verification code resent.');
    }

    /**
     * Disable 2FA.
     */
    public function disable(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        if ($this->twoFactorService->disable($request->user(), $request->code)) {
            return redirect()->back()->with('status', '2FA disabled successfully.');
        }

        return back()->withErrors(['code' => 'Invalid verification code.']);
    }

    /**
     * Enable 2FA.
     */
    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        if ($this->twoFactorService->enable($request->user(), $request->code)) {
            return redirect()->back()->with('status', '2FA enabled successfully.');
        }

        return back()->withErrors(['code' => 'Invalid verification code.']);
    }
}