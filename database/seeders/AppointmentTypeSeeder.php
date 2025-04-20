<?php

namespace Database\Seeders;

use App\Models\AppointmentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        AppointmentType::create(['name' => 'Consultation', 'duration_minutes' => 30]);
        AppointmentType::create(['name' => 'Follow-Up', 'duration_minutes' => 15]);
    }
}
