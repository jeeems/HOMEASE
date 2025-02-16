<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('worker_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Emergency Contact Details
            $table->string('emergency_name');
            $table->string('emergency_relationship');
            $table->string('emergency_phone');

            // Work & Service Details
            $table->string('service_type');
            $table->decimal('hourly_rate', 8, 2);
            $table->integer('experience');
            $table->string('specialization')->nullable();
            $table->text('work_locations');
            $table->string('reference');

            // Background Check Verification
            $table->string('photo');
            $table->string('gov_id');
            $table->string('clearance');

            // Transportation & Equipment
            $table->enum('transportation', ['Yes', 'No']);
            $table->enum('tools', ['Yes', 'No']);

            // Terms & Conditions
            $table->boolean('agreed_terms')->default(false);
            $table->boolean('agreed_privacy_policy')->default(false);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('worker_verifications');
    }
};
