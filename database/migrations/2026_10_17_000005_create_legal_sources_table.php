<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legal_sources', function (Blueprint $table): void {
            $table->id();
            $table->string('title', 220);
            $table->string('slug', 220)->unique();
            $table->string('source_type', 32);
            $table->string('reference_code', 120)->nullable()->index();
            $table->string('provision_reference', 220)->nullable();
            $table->string('page_reference', 120)->nullable();
            $table->text('summary');
            $table->string('source_url', 2048);
            $table->date('document_date')->nullable();
            $table->string('status', 16)->default('draft')->index();
            $table->timestamp('verified_at')->nullable()->index();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legal_sources');
    }
};
