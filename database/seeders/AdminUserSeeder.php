<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'erosmunozzanon@gmail.com';

        $user = User::where('email', $email)->first();

        if ($user) {
            $user->update([
                'is_admin'           => true,
                'email_verified_at'  => $user->email_verified_at ?? now(),
            ]);
            $this->command->info("Usuario existente actualizado con permisos admin: {$email}");
        } else {
            User::create([
                'name'               => 'Eros Muñoz',
                'email'              => $email,
                'password'           => Hash::make('12345678'),
                'is_admin'           => true,
                'email_verified_at'  => now(),
            ]);
            $this->command->info("Usuario admin creado: {$email}");
        }
    }
}
