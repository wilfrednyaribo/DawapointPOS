<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('categories', function (Blueprint $table) {
        // 1. Drop the old global unique index on slug
        $table->dropUnique(['slug']);

        // 2. Add a new composite unique index (pharmacy_id + slug)
        // This allows "Antibiotics" to exist for Pharmacy 1 AND Pharmacy 2
        $table->unique(['pharmacy_id', 'slug']);
    });
}

public function down()
{
    Schema::table('categories', function (Blueprint $table) {
        $table->dropUnique(['pharmacy_id', 'slug']);
        $table->unique('slug');
    });
}
};
