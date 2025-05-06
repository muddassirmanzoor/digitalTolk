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

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_user_and_returns_token(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(201)->assertJsonStructure(['token']);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_login_returns_token(): void
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'login@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)->assertJsonStructure(['token']);
    }
}
