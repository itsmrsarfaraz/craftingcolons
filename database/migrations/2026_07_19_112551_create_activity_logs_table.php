<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            // Added explicit index for rapid filtering by specific staff members
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete()->index();
            $table->string('action');
            // This macro handles the fields AND automatically generates the composite index safely
            $table->nullableMorphs('subject');
            $table->string('description');
            $table->json('changes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();

            // Kept for rapid chronological sorting/ordering in dashboards
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};