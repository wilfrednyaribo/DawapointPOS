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
    // 1. Add to Users table
    Schema::table('users', function (Blueprint $table) {
        $table->foreignId('pharmacy_id')->nullable()->constrained()->onDelete('cascade')->after('id');
    });

    // 2. Add to Drugs table
    Schema::table('drugs', function (Blueprint $table) {
        $table->foreignId('pharmacy_id')->nullable()->constrained()->onDelete('cascade')->after('id');
        $table->index('pharmacy_id'); // Speed up queries
    });

    // 3. Add to Sales table
    Schema::table('sales', function (Blueprint $table) {
        $table->foreignId('pharmacy_id')->nullable()->constrained()->onDelete('cascade')->after('id');
    });

    // 4. Add to Categories, Suppliers, Customers, Purchases
    Schema::table('categories', function (Blueprint $table) {
        $table->foreignId('pharmacy_id')->nullable()->constrained()->onDelete('cascade')->after('id');
    });
    
    Schema::table('suppliers', function (Blueprint $table) {
        $table->foreignId('pharmacy_id')->nullable()->constrained()->onDelete('cascade')->after('id');
    });
    
    Schema::table('customers', function (Blueprint $table) {
        $table->foreignId('pharmacy_id')->nullable()->constrained()->onDelete('cascade')->after('id');
    });

    Schema::table('purchases', function (Blueprint $table) {
        $table->foreignId('pharmacy_id')->nullable()->constrained()->onDelete('cascade')->after('id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
};
