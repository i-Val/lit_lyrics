<?php

namespace Tests\Feature;

use App\Models\ApiClient;
use App\Models\Setting;
use App\Models\Song;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiKeyAuthTest extends TestCase
{
    use RefreshDatabase;

    private function createVerifiedUser(string $email = 'client@example.com'): \App\Models\User
    {
        return \App\Models\User::create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'email_verified_at' => now(),
        ]);
    }

    public function test_register_returns_api_key(): void
    {
        Setting::set('api_is_free', '1', 'api', 'boolean');

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Demo Client',
            'email' => 'demo@example.com',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'api_key',
            'client' => ['id', 'name', 'email', 'is_active', 'subscription_required', 'subscription_expires_at'],
        ]);
    }

    public function test_protected_endpoint_requires_api_key(): void
    {
        Setting::set('api_is_free', '1', 'api', 'boolean');

        $response = $this->getJson('/api/v1/songs');
        $response->assertStatus(401);
    }

    public function test_protected_endpoint_allows_valid_key_when_free(): void
    {
        Setting::set('api_is_free', '1', 'api', 'boolean');

        $user = $this->createVerifiedUser();
        $rawKey = ApiClient::generateApiKey();
        ApiClient::create([
            'user_id' => $user->id,
            'name' => 'Client',
            'email' => 'client@example.com',
            'api_key_hash' => ApiClient::hashApiKey($rawKey),
            'is_active' => true,
        ]);

        Song::create([
            'title' => 'Test Song',
            'author' => 'Tester',
            'category' => 'General',
            'verses' => '<p>Line 1<br>Line 2</p>',
        ]);

        $response = $this->withHeader('X-API-Key', $rawKey)->getJson('/api/v1/songs');
        $response->assertStatus(200);
    }

    public function test_protected_endpoint_requires_subscription_when_not_free(): void
    {
        Setting::set('api_is_free', '0', 'api', 'boolean');

        $user = $this->createVerifiedUser();
        $rawKey = ApiClient::generateApiKey();
        ApiClient::create([
            'user_id' => $user->id,
            'name' => 'Client',
            'email' => 'client@example.com',
            'api_key_hash' => ApiClient::hashApiKey($rawKey),
            'is_active' => true,
        ]);

        $response = $this->withHeader('X-API-Key', $rawKey)->getJson('/api/v1/songs');
        $response->assertStatus(402);
    }

    public function test_protected_endpoint_allows_subscription_when_active(): void
    {
        Setting::set('api_is_free', '0', 'api', 'boolean');

        $user = $this->createVerifiedUser();
        $rawKey = ApiClient::generateApiKey();
        ApiClient::create([
            'user_id' => $user->id,
            'name' => 'Client',
            'email' => 'client@example.com',
            'api_key_hash' => ApiClient::hashApiKey($rawKey),
            'is_active' => true,
            'subscription_plan' => 'Pro',
            'subscription_expires_at' => now()->addDay(),
        ]);

        $response = $this->withHeader('X-API-Key', $rawKey)->getJson('/api/v1/songs');
        $response->assertStatus(200);
    }

    public function test_regenerate_key_returns_new_key(): void
    {
        $user = $this->createVerifiedUser();
        $rawKey = ApiClient::generateApiKey();
        $client = ApiClient::create([
            'user_id' => $user->id,
            'name' => 'Client',
            'email' => 'client@example.com',
            'api_key_hash' => ApiClient::hashApiKey($rawKey),
            'is_active' => true,
        ]);

        $response = $this->withHeader('X-API-Key', $rawKey)->postJson('/api/v1/auth/regenerate-key');

        $response->assertStatus(200);
        $response->assertJsonStructure(['api_key', 'message']);
        
        $newKey = $response->json('api_key');
        $this->assertNotEquals($rawKey, $newKey);
        
        // Assert the database hash is updated
        $client->refresh();
        $this->assertEquals(ApiClient::hashApiKey($newKey), $client->api_key_hash);
    }

    public function test_old_key_becomes_invalid_after_regeneration(): void
    {
        Setting::set('api_is_free', '1', 'api', 'boolean');
        $user = $this->createVerifiedUser();
        $rawKey = ApiClient::generateApiKey();
        ApiClient::create([
            'user_id' => $user->id,
            'name' => 'Client',
            'email' => 'client@example.com',
            'api_key_hash' => ApiClient::hashApiKey($rawKey),
            'is_active' => true,
        ]);

        // Verify old key works initially
        $response = $this->withHeader('X-API-Key', $rawKey)->getJson('/api/v1/songs');
        $response->assertStatus(200);

        // Regenerate key
        $regenResponse = $this->withHeader('X-API-Key', $rawKey)->postJson('/api/v1/auth/regenerate-key');
        $regenResponse->assertStatus(200);
        $newKey = $regenResponse->json('api_key');

        // Verify old key is now invalid
        $oldResponse = $this->withHeader('X-API-Key', $rawKey)->getJson('/api/v1/songs');
        $oldResponse->assertStatus(401);

        // Verify new key works
        $newResponse = $this->withHeader('X-API-Key', $newKey)->getJson('/api/v1/songs');
        $newResponse->assertStatus(200);
    }

    public function test_regenerate_key_requires_valid_key(): void
    {
        $response = $this->withHeader('X-API-Key', 'invalid-key')->postJson('/api/v1/auth/regenerate-key');
        $response->assertStatus(401);
    }
}

