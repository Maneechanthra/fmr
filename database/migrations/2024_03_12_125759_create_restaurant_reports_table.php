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
        Schema::create('restaurant_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('restaurant_id');
            $table->string('title');
            $table->string('descriptions');
            $table->integer('status')->default('0');
            $table->integer('report_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_reports');
    }
};
