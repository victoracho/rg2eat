<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_categories', function (Blueprint $table) {
            // Subgrupo dentro del recuadro de otro grupo (ej. Rellenos dentro de Los Chilangos)
            $table->foreignId('parent_id')->nullable()->after('id')
                ->constrained('menu_categories')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('menu_categories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parent_id');
        });
    }
};
