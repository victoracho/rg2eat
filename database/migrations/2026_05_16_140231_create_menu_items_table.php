<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_category_id')
                ->constrained('menu_categories')
                ->cascadeOnDelete();
            $table->string('name_es');
            $table->string('name_en')->nullable();
            $table->string('name_pt')->nullable();
            $table->text('description_es')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_pt')->nullable();
            $table->decimal('price', 8, 2)->default(0);
            $table->string('currency', 3)->default('EUR');
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->json('tags')->nullable(); // ['vegan','spicy','gluten-free',...]
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['menu_category_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
