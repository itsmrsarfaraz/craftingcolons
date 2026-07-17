<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->text('prompt');
            $table->unsignedTinyInteger('marks')->default(1);
            $table->unsignedInteger('order')->default(0);
            $table->string('language')->nullable();
            $table->timestamps();

            $table->index(['assessment_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};