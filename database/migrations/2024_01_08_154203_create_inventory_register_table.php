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
        Schema::create('inventory_register', function (Blueprint $table) {
            $table->string('id_product')->primary();
            $table->string('description');
            $table->string('gender');
            $table->string('size');
            $table->double('sale_price');
            $table->string('brand');
            $table->string('color');
            $table->date('date_entry');
            $table->unsignedBigInteger('id_store');
            $table->foreign('id_store')->references('id')->on('store_list');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_register');
    }
};
