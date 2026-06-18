<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::latest();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%");
            });
        }

        if ($request->filled('filter')) {
            match ($request->filter) {
                'admin'      => $query->where('is_admin', true),
                'verified'   => $query->whereNotNull('email_verified_at'),
                'unverified' => $query->whereNull('email_verified_at'),
                default      => null,
            };
        }

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users'],
            'password' => ['required', Password::min(8)],
            'is_admin' => ['nullable'],
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'is_admin'          => $request->has('is_admin'),
            'email_verified_at' => $request->has('verified') ? now() : null,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Usuario «{$user->name}» creado correctamente.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Usuario «{$name}» eliminado.");
    }

    public function toggleAdmin(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes modificar tu propio rol.');
        }

        $user->update(['is_admin' => ! $user->is_admin]);

        $accion = $user->is_admin ? 'promovido a administrador' : 'removido de administrador';

        return back()->with('success', "«{$user->name}» {$accion}.");
    }

    public function verify(User $user)
    {
        if ($user->hasVerifiedEmail()) {
            return back()->with('error', "«{$user->name}» ya tiene el email verificado.");
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return back()->with('success', "Email de «{$user->name}» verificado correctamente.");
    }

    public function resendVerification(User $user)
    {
        if ($user->hasVerifiedEmail()) {
            return back()->with('error', "«{$user->name}» ya tiene el email verificado.");
        }

        $user->sendEmailVerificationNotification();

        return back()->with('success', "Email de verificación reenviado a «{$user->email}».");
    }
}
