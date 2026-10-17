<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_rate_rules', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 120)->unique();
            $table->string('tax_type', 12)->index();
            $table->string('category', 120);
            $table->text('description');
            $table->unsignedInteger('rate_basis_points');
            $table->date('effective_from')->index();
            $table->date('effective_until')->nullable();
            $table->string('reference_code', 160);
            $table->string('provision_reference', 200);
            $table->string('source_url', 2048);
            $table->string('status', 16)->default('draft')->index();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_rate_rules');
    }
};
