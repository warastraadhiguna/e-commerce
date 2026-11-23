<?php

namespace App\Console\Commands;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateDeveloper extends Command
{
    protected $signature = 'store:create-developer {email? : Email akun developer baru}';

    protected $description = 'Buat akun Developer melalui terminal server dengan password tersembunyi';

    public function handle(): int
    {
        if (! $this->input->isInteractive()) {
            $this->error('Jalankan secara interaktif. Password dimasukkan melalui prompt tersembunyi.');

            return self::FAILURE;
        }

        $email = strtolower(trim((string) ($this->argument('email') ?: $this->ask('Email developer'))));
        $name = trim((string) $this->ask('Nama developer', 'Developer'));
        $phone = User::normalizePhone($this->ask('Nomor HP developer'));
        $validator = Validator::make(compact('email', 'name', 'phone'), [
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'regex:/^\+?[0-9]{8,15}$/', 'unique:users,phone'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $password = $this->secret('Password developer (minimal 12 karakter)');
        $confirmation = $this->secret('Ulangi password developer');
        $validator = Validator::make(['password' => $password, 'password_confirmation' => $confirmation], [
            'password' => ['required', 'confirmed', Password::min(12)],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        User::query()->create([
            'email' => $email, 'name' => $name, 'phone' => $phone, 'password' => $password,
            'role' => UserRole::Developer, 'is_active' => true, 'email_verified_at' => now(),
        ]);
        $this->info('Akun Developer dibuat: '.$email);
        $this->info('Login melalui /management/login, lalu buka Developer → Identitas & Tema.');

        return self::SUCCESS;
    }
}
