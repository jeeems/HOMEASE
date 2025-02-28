<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompletionDetailsToBookingsTable extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dateTime('completion_date')->nullable();
            $table->decimal('hours_worked', 8, 2)->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['completion_date', 'hours_worked', 'total_amount']);
        });
    }
}
