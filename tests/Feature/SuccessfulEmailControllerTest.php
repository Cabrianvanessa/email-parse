<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\SuccessfulEmail;
use Illuminate\Support\Facades\Hash;

class SuccessfulEmailControllerTest extends TestCase
{
    use RefreshDatabase; // Ensures the database is reset after each test

    /** @test */
    public function it_can_save_an_email_record()
    {
        // Create a user and generate a token using the factory
        $user = User::factory()->create([
            'password' => Hash::make('password'), // Ensure the password matches in authentication
        ]);

        $token = $user->createToken('TestToken')->plainTextToken;

        // Define the email data including all required fields
        $data = [
            'affiliate_id' => 1,
            'envelope' => 'Envelope Data',
            'from' => 'sender@example.com',
            'subject' => 'Test Subject',
            'dkim' => 'dkim_value',
            'SPF' => 'spf_value',
            "spam_score" => 0,
            'email' => '<html><body>From: example@example.com<br>To: recipient@example.com<br><br>This is a test email.</body></html>',
            'sender_ip' => '192.168.1.1',
            'to' => 'recipient@example.com',
            'timestamp' => time(),
        ];

        // Send a POST request to the store endpoint with authentication
        $response = $this->postJson('/api/emails', $data, ['Authorization' => "Bearer $token"]);

        // Assert the response status
        $response->assertStatus(201);

        // Assert the email was saved in the database with the correct plain text
        $this->assertDatabaseHas('successful_emails', $data);
    }
}
