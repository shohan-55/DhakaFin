<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Production-safe default: do not create test users or sample tax data.
     * Run php artisan dhakafin:admin interactively to provision an administrator.
     */
    public function run(): void
    {
        // Intentionally empty.
    }
}
