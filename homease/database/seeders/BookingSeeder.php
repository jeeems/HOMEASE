<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'service_type' => 'Electrician',
                'scheduled_date' => Carbon::now()->addDays(2),
                'status' => 'pending',
                'notes' => 'Fix leaking sink in the kitchen.',
            ]);

            Booking::create([
                'worker_id' => $worker->id,
                'client_id' => $client->id,
                'service_type' => 'Electrician',
                'scheduled_date' => Carbon::now()->addDays(4),
                'status' => 'ongoing',
                'notes' => 'Rewire the living room lights.',
            ]);
        }
    }
}
