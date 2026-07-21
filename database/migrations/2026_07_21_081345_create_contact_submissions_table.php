<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email');
            $table->string('subject')->nullable();
            $table->text('message')->nullable();
            $table->string('type')->default('contact');
            $table->boolean('is_read')->default(false);
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['type', 'is_read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_submissions');
    }
};