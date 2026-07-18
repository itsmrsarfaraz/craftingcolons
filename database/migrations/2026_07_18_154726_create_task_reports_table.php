<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->date('report_date');
            $table->text('summary');
            $table->string('evidence_path')->nullable();
            $table->timestamps();

            $table->unique(['task_id', 'report_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_reports');
    }
};