<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\Feature\BaseFeatureTest;

class UserAuthTest extends BaseFeatureTest
{
    protected function setUp(): void
    {
        $this->skipAuth = true; // ✅ Prevents auto-login in BaseFeatureTest
        parent::setUp();
    }

    /** @test */
    public function user_can_register_and_login()
    {
        // You may add registration logic here if needed
        // For now, we manually create a user and then log in

        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'user@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'token',
                     'success',
                     'user',
                 ]);

        $this->token = $response->json('token');
        $this->assertNotNull($this->token);
    }

    /** @test */
    public function a_user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('valid_password')
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(401);
    }
}
