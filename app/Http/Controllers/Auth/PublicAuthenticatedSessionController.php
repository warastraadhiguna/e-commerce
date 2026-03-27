<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PublicLoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicAuthenticatedSessionController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->user()) {
            return redirect()->route('home');
        }

        $redirect = trim((string) $request->query('redirect', ''));

        if ($redirect !== '' && (! str_starts_with($redirect, '/') || str_starts_with($redirect, '//'))) {
            $redirect = '';
        }

        return view('auth.login', [
            'redirectTo' => $redirect,
        ]);
    }

    public function store(PublicLoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()
            ->to($request->safeRedirectTo())
            ->with('public_feedback', [
                'icon' => 'success',
                'title' => 'Login berhasil',
                'text' => 'Selamat datang kembali. Kamu sudah berhasil masuk.',
            ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        auth()->guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
