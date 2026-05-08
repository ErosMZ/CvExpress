<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        $user->sendEmailVerificationNotification();

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
}