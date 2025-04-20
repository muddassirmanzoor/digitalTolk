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
        $slot = TimeSlot::create([
            'address_id' => 1,
            'specific_date' => '2025-04-22', // Example of a dynamic slot
            'start_time' => '09:00',
            'end_time' => '12:00',
        ]);

        $slot->appointmentTypes()->attach([1, 2]);

    }
}
