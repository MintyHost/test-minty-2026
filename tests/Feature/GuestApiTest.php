<?php
namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Guest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_can_create_guest_for_booking()
    {
        $booking = Booking::factory()->create();

        $payload = [
            'first_name' => 'Juanjo',
            'last_name' => 'Sanchez',
            'email' => 'juanjo@example.com',
            'phone_number' => '1234567890',
            'booking_id' => $booking->id,
        ];

        $response = $this->postJson('/api/guests', $payload);

        $response->assertCreated();
    }

    public function test_cannot_create_guest_with_invalid_data()
    {
        $response = $this->postJson('/api/guests', []);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['booking_id', 'first_name', 'email']);
    }
}