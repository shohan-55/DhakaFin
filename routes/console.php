<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// CLI-only bootstrap: never creates a publicly known administrator password.
Artisan::command('dhakafin:admin:create {email}', function (string $email): int {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->error('A valid email address is required.');
        return 1;
    }

    $email = strtolower(trim($email));
    $user = \App\Models\User::query()->where('email', $email)->first();
    if ($user && !$this->confirm('Update the password and grant administrator access to this existing user?')) {
        $this->warn('No changes made.');
        return 1;
    }

    $password = $this->secret('Enter a unique administrator password (at least 12 characters)');
    if (!is_string($password) || strlen($password) < 12) {
        $this->error('Password must contain at least 12 characters.');
        return 1;
    }

    $name = $this->ask('Administrator display name', $user?->name ?? 'DhakaFin Administrator');
    $user ??= new \App\Models\User();
    $user->email = $email;
    $user->name = $name;
    $user->password = \Illuminate\Support\Facades\Hash::make($password);
    $user->is_admin = true;
    $user->save();
    $this->info('Administrator account configured.');
    return 0;
})->purpose('Interactively create or authorize a DhakaFin administrator.');
