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

        $rawKey = ApiClient::generateApiKey();
        ApiClient::create([
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

        $rawKey = ApiClient::generateApiKey();
        ApiClient::create([
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

        $rawKey = ApiClient::generateApiKey();
        ApiClient::create([
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
}
