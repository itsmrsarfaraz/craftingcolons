<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attempts', function (Blueprint $table) {
            $table->unsignedInteger('violation_count')->default(0)->after('status');
            $table->unsignedTinyInteger('max_violations_allowed')->default(3)->after('violation_count');
        });
    }

    public function down(): void
    {
        Schema::table('attempts', function (Blueprint $table) {
            $table->dropColumn(['violation_count', 'max_violations_allowed']);
        });
    }
};