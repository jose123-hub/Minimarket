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
    if (! $request->user()->hasVerifiedEmail()) {
        $request->user()->markEmailAsVerified();

        event(new Verified($request->user()));
    }

    $role = $request->user()->role;

    if ($role === 'admin') {
        return redirect('/dashboard?verified=1');
    }

    if ($role === 'cashier') {
        return redirect('/cashier/dashboard?verified=1');
    }

    return redirect('/client/catalog?verified=1');
    }
}
