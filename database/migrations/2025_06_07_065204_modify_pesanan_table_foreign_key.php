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
        Schema::table('pesanan', function (Blueprint $table) {
            // Drop existing foreign key constraint for buku_id
            // Assuming the foreign key constraint is named pesanan_buku_id_foreign
            $table->dropForeign(['buku_id']);

            // Rename buku_id column to product_id
            $table->renameColumn('buku_id', 'product_id');

            // Add new foreign key constraint on product_id referencing the id column of the products table
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            // Drop foreign key constraint for product_id
            $table->dropForeign(['product_id']);

            // Rename product_id column back to buku_id
            $table->renameColumn('product_id', 'buku_id');

            // Add back the foreign key constraint on buku_id referencing the id column of the buku table
            $table->foreign('buku_id')->references('id')->on('buku')->onDelete('cascade');
        });
    }
};
