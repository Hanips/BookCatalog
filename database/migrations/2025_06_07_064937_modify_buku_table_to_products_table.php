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
        Schema::rename('buku', 'products');

        Schema::table('products', function (Blueprint $table) {
            // Remove existing columns
            $table->dropColumn(['isbn', 'jumlah_halaman', 'sinopsis', 'rating', 'url_buku', 'pengarang']);

            // Add new columns
            $table->float('long_product')->nullable();
            $table->float('width_product')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->text('description')->nullable();

            // Modify existing columns
            $table->renameColumn('penerbit_id', 'label_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Add back removed columns
            $table->string('isbn')->nullable();
            $table->integer('jumlah_halaman')->nullable();
            $table->text('sinopsis')->nullable();
            $table->decimal('rating', 2, 1)->nullable();
            $table->string('url_buku')->nullable();
            $table->string('pengarang')->nullable();

            // Remove new columns
            $table->dropColumn(['long_product', 'width_product', 'slug', 'description']);

            // Rename label_id back to penerbit_id
            $table->renameColumn('label_id', 'penerbit_id');
        });

        Schema::rename('products', 'buku');
    }
};
