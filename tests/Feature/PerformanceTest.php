<?php

namespace Tests\Feature;

use App\Models\Translation;
use App\Models\AppointmentType;
use App\Models\TimeSlot;
use App\Models\User;
use App\Services\AvailabilityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_performance_is_under_500ms(): void
    {
        $user = User::factory()->create([
            'email' => 'speed@example.com',
            'password' => bcrypt('password123')
        ]);

        $start = microtime(true);

        $this->postJson('/api/login', [
            'email' => 'speed@example.com',
            'password' => 'password123'
        ])->assertStatus(200);

        $end = microtime(true);
        $this->assertLessThan(0.5, $end - $start, 'Login API took too long');
    }
}
