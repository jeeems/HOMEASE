<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->unsignedBigInteger('booking_id')->after('client_id');
            $table->string('booking_title')->after('booking_id');
            $table->json('review_photos')->nullable()->after('comment');

            // Add foreign key constraint if needed
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropColumn(['booking_id', 'booking_title', 'review_photos']);
        });
    }
};
