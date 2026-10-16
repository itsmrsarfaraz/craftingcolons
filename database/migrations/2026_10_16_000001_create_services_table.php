<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('services', function (Blueprint $t) {
            $t->id();
            $t->unsignedTinyInteger('position')->default(0);
            $t->string('title');
            $t->text('description');
            $t->json('badges');                 // ["Laravel","PHP",...]
            $t->string('quest_type');           // matches the contact form <select>
            $t->string('info');                 // text shown in the quest info bar
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('services'); }
};
