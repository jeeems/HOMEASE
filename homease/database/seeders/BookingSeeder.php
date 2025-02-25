<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $worker = User::where('role', 'worker')->first();
        $client = User::where('role', 'client')->first();

        if ($worker && $client) {
            Booking::create([
                'worker_id' => $worker->id,
                'client_id' => $client->id,
                'service_type' => 'Plumbing',
                'scheduled_date' => Carbon::now()->addDays(2),
                'status' => 'pending',
                'booking_title' => 'Kitchen Sink Repair',
                'notes' => 'Fix leaking sink in the kitchen.',
                'client_address' => $client->address,
            ]);

            Booking::create([
                'worker_id' => $worker->id,
                'client_id' => $client->id,
                'service_type' => 'Electrical',
                'scheduled_date' => Carbon::now()->addDays(4),
                'status' => 'ongoing',
                'booking_title' => 'Living Room Rewiring',
                'notes' => 'Rewire the living room lights.',
                'client_address' => $client->address,
            ]);
        }
    }
}
