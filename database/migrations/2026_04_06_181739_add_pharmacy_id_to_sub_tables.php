<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Drug Batches
        Schema::table('drug_batches', function (Blueprint $table) {
            $table->foreignId('pharmacy_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->index('pharmacy_id');
        });

        // 2. Inventory Movements
        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->foreignId('pharmacy_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->index('pharmacy_id');
        });

        // 3. Sale Items
        Schema::table('sale_items', function (Blueprint $table) {
            $table->foreignId('pharmacy_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->index('pharmacy_id');
        });

        // 4. Purchase Items
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->foreignId('pharmacy_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->index('pharmacy_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drug_batches', function (Blueprint $table) {
            $table->dropForeign(['pharmacy_id']);
            $table->dropColumn('pharmacy_id');
        });

        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->dropForeign(['pharmacy_id']);
            $table->dropColumn('pharmacy_id');
        });

        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropForeign(['pharmacy_id']);
            $table->dropColumn('pharmacy_id');
        });

        Schema::table('purchase_items', function (Blueprint $table) {
            $table->dropForeign(['pharmacy_id']);
            $table->dropColumn('pharmacy_id');
        });
    }
};