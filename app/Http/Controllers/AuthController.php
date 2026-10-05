<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showRegister() {
        return view('register');
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        Auth::login($user);

        // El envío no debe tumbar el registro: si el proveedor SMTP falla
        // (p. ej. SendGrid sin créditos), el usuario podrá reenviarlo luego.
        try {
            $user->sendEmailVerificationNotification();
        } catch (\Throwable $e) {
            Log::error('No se pudo enviar el email de verificación', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
            // Temporal (debug): duplicar en stderr para verlo en los logs de Render
            Log::channel('stderr')->error('MAIL-DEBUG registro: '.$e->getMessage());
            return redirect('/email/verify')
                ->with('error', 'No pudimos enviar el email de verificación. Pulsa "Reenviar" en unos minutos.');
        }

        return redirect('/email/verify');
    }

    public function showLogin() {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            if (!Auth::user()->hasVerifiedEmail()) {
                Auth::logout();
                return back()->with('error', 'Debes verificar tu email primero');
            }

            return redirect('/');
        }

        return back()->with('error', 'Credenciales incorrectas');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}