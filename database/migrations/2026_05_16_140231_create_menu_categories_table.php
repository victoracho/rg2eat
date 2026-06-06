<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('icon')->nullable(); // emoji or short text
            $table->string('name_es');
            $table->string('name_en')->nullable();
            $table->string('name_pt')->nullable();
            $table->text('description_es')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_pt')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_categories');
    }
};
