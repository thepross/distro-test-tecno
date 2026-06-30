<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\BitacoraAcceso;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();
        } catch (ValidationException $exception) {
            BitacoraAcceso::registrar(
                $request,
                'login_fallado',
                'rechazado',
                'Correo o contraseña incorrectos.',
                null,
                $request->input('email')
            );

            throw $exception;
        }

        $usuario = $request->user();

        if (! $usuario || $usuario->state !== 'a') {
            BitacoraAcceso::registrar(
                $request,
                'usuario_inactivo',
                'rechazado',
                'La cuenta existe, pero está inactiva.',
                optional($usuario)->id,
                optional($usuario)->email ?: $request->input('email')
            );

            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Tu cuenta se encuentra inactiva. Contacta al administrador.',
            ]);
        }

        $request->session()->regenerate();

        BitacoraAcceso::registrar(
            $request,
            'login_aceptado',
            'aceptado',
            'Ingreso correcto al sistema.',
            $usuario->id,
            $usuario->email
        );

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $usuario = $request->user();

        if ($usuario) {
            BitacoraAcceso::registrar(
                $request,
                'logout',
                'cerrado',
                'Cierre de sesión.',
                $usuario->id,
                $usuario->email
            );
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
