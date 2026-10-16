<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('email');
            $t->string('quest_type');
            $t->text('message');
            $t->string('ip', 45)->nullable();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('contact_messages'); }
};
