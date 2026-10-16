<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $t) {
            $t->id();
            $t->unsignedTinyInteger('position')->default(0);
            $t->string('title');
            $t->string('kind');                 // badge text, e.g. "Web / SaaS"
            $t->string('categories');           // space separated filter keys: "web edu"
            $t->string('tagline');
            $t->text('details');
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('projects'); }
};
