<?php

namespace Database\Seeders;

use App\Models\TimeSlot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $dayOfWeek = strtolower(now()->addDays(2)->format('l')); // ensures it will match the test logic

        $slot = TimeSlot::create([
            'address_id' => 1,
            'weekdays' => json_encode([$dayOfWeek]),
            'start_time' => '09:00',
            'end_time' => '12:00',
        ]);

        $slot->appointmentTypes()->attach([1, 2]);


    }
}
