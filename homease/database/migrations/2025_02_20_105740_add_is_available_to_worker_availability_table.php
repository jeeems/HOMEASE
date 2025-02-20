<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('worker_availability', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('worker_id'); // Foreign key to users table
            $table->boolean('is_available')->default(1); // Availability status
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('worker_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('worker_availability');
    }
};
