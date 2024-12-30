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
        Schema::create('lichsu_hddonvi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mals',50)->nullable();
            $table->string('madv',50)->nullable();
            $table->string('macqcq',50)->nullable();
            $table->string('action')->nullable();
            $table->string('ghichu')->nullable();
            $table->date('ngaythang')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lichsu_hddonvi');
    }
};
