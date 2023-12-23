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
        Schema::create('spent_record', function (Blueprint $table) {
            $table->id('id_reg');
            $table->double('amount');
            $table->string('type');
            $table->string('description');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id')->on('users');
            $table->date('date');
            $table->unsignedBigInteger('id_store');
            $table->foreign('id_store')->references('id')->on('store_list');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spent_record');
    }
};
