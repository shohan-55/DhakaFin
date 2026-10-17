<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

final class ProvisionAdmin extends Command
{
    protected $signature = 'dhakafin:admin';
    protected $description = 'Provision a DhakaFin administrator interactively (password never appears in shell history).';

    public function handle(): int
    {
        $this->warn('This grants full DhakaFin administration privileges.');

        $name = trim((string) $this->ask('Administrator name'));
        $email = mb_strtolower(trim((string) $this->ask('Administrator email')));

        $identity = Validator::make(
            ['name' => $name, 'email' => $email],
            ['name' => ['required', 'string', 'max:255'], 'email' => ['required', 'email', 'max:255']]
        );
        if ($identity->fails()) {
            $this->error($identity->errors()->first());
            return self::FAILURE;
        }

        $existing = User::query()->where('email', $email)->first();
        if ($existing !== null) {
            $this->warn('An account with this email already exists.');
        }

        if (!$this->confirm('Create/update this user and grant administrator privileges?', false)) {
            $this->info('No changes made.');
            return self::SUCCESS;
        }

        $password = (string) $this->secret('Administrator password');
        $confirmed = (string) $this->secret('Confirm administrator password');
        if (!hash_equals($password, $confirmed)) {
            $this->error('Passwords do not match; no changes made.');
            return self::FAILURE;
        }

        $credentials = Validator::make(
            ['password' => $password],
            ['password' => ['required', Password::min(12)->mixedCase()->numbers()->symbols()]]
        );
        if ($credentials->fails()) {
            $this->error($credentials->errors()->first());
            return self::FAILURE;
        }

        $user = $existing ?? new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->is_admin = true;
        $user->save();

        $this->info('Administrator provisioned successfully.');
        return self::SUCCESS;
    }
}
