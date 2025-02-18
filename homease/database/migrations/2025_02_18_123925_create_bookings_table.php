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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained('users')->onDelete('cascade'); // Worker assigned
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade'); // Client who booked
            $table->string('service_type'); // Type of service requested
            $table->dateTime('scheduled_date'); // When the service is scheduled
            $table->enum('status', ['pending', 'ongoing', 'completed', 'cancelled'])->default('pending'); // Booking status
            $table->text('notes')->nullable(); // Optional notes from the client
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
