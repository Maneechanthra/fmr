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
        Schema::create('restaurant_openings', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('restaurant_id');
            $table->integer('day_open');
            $table->time('time_open');
            $table->time('time_close');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_openings');
    }
};
