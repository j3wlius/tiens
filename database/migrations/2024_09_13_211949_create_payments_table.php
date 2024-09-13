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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('dist_no');
            $table->string('dist_name');
            $table->string('shop_id');
            $table->decimal('net_pay', 8, 2); // Corrected line
            $table->bigInteger('contacts'); // Changed to bigInteger
            $table->boolean('status');
            $table->string('error');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
