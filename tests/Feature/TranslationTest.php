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

class TranslationTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate(): string
    {
        $user = User::factory()->create();
        return $user->createToken('test-token')->plainTextToken;
    }

    public function test_can_create_translation(): void
    {
        $token = $this->authenticate();

        $response = $this->postJson('/api/translations', [
            'locale' => 'en',
            'key' => 'welcome.message',
            'value' => 'Welcome!',
            'tags' => ['web']
        ], ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(201)->assertJsonFragment(['key' => 'welcome.message']);
    }

    public function test_can_list_translations_with_filter(): void
    {
        $token = $this->authenticate();
        Translation::factory()->create(['locale' => 'en', 'key' => 'home.title']);

        $response = $this->getJson('/api/translations?locale=en&key=home', [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200)->assertJsonFragment(['key' => 'home.title']);
    }
}
