<?php

namespace Tests\Feature;

use App\Models\Translation;
use App\Models\AppointmentType;
use App\Models\TimeSlot;
use App\Services\AvailabilityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AvailabilityServiceTest extends TestCase
{
    public function testAvailabilityForAppointmentType()
    {
        // Prepare test data
        $address = Translation::create(['name' => 'Test Clinic']);
        $appointmentType = AppointmentType::create(['name' => 'Consultation', 'duration_minutes' => 30]);

        // Create a TimeSlot with a specific date
        $timeSlot = TimeSlot::create([
            'address_id' => $address->id,
            'specific_date' => '2025-04-22',  // A dynamic slot for specific date
            'start_time' => '09:00',
            'end_time' => '12:00',
        ]);
        $timeSlot->appointmentTypes()->attach($appointmentType->id);

        // Use the AvailabilityService to check availability
        $availabilityService = new AvailabilityService();
        $nextSlot = $availabilityService->getNextDisponibilite($address->id, $appointmentType->id);

        // Assertions
        $this->assertNotNull($nextSlot);  // Ensure a slot was found
        $this->assertStringContainsString('2025-04-22', $nextSlot);  // Verify it matches the dynamic date
    }
}
