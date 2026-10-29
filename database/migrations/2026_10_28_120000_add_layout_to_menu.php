<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_categories', function (Blueprint $table) {
            $table->string('section', 10)->default('food')->after('slug'); // food | drinks
            $table->string('style', 20)->default('tab-blue')->after('section'); // ver MenuCategory::STYLES
            $table->string('price_label', 20)->nullable()->after('style');   // ej. "25 ml"
            $table->string('price_2_label', 20)->nullable()->after('price_label'); // ej. "50 ml"
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->nullable()->default(null)->change(); // rellenos sin precio
            $table->decimal('price_2', 8, 2)->nullable()->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('price_2');
            $table->decimal('price', 8, 2)->default(0)->nullable(false)->change();
        });

        Schema::table('menu_categories', function (Blueprint $table) {
            $table->dropColumn(['section', 'style', 'price_label', 'price_2_label']);
        });
    }
};
