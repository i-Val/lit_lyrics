<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiClient;
use App\Models\Setting;
use Illuminate\Http\Request;

class ApiAuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:api_clients,email'],
        ]);

        $rawKey = ApiClient::generateApiKey();

        $nameParts = explode(' ', $validated['name'], 2);
        $user = \App\Models\User::firstOrCreate(
            ['email' => $validated['email']],
            [
                'firstname' => $nameParts[0] ?? 'Demo',
                'lastname' => $nameParts[1] ?? 'Client',
                'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16)),
                'email_verified_at' => now(),
            ]
        );

        $client = ApiClient::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'api_key_hash' => ApiClient::hashApiKey($rawKey),
            'is_active' => true,
        ]);

        $isFree = Setting::get('api_is_free', '1') === '1';

        return response()->json([
            'api_key' => $rawKey,
            'client' => [
                'id' => $client->id,
                'name' => $client->name,
                'email' => $client->email,
                'is_active' => $client->is_active,
                'subscription_required' => ! $isFree,
                'subscription_expires_at' => optional($client->subscription_expires_at)->toISOString(),
            ],
        ], 201);
    }

    public function regenerateKey(Request $request)
    {
        /** @var \App\Models\ApiClient|null $client */
        $client = $request->attributes->get('apiClient');

        if (! $client) {
            return response()->json(['message' => 'API client not found.'], 404);
        }

        $newRawKey = ApiClient::generateApiKey();

        $client->update([
            'api_key_hash' => ApiClient::hashApiKey($newRawKey),
            'api_key_created_at' => now(),
        ]);

        return response()->json([
            'api_key' => $newRawKey,
            'message' => 'API key regenerated successfully.',
        ], 200);
    }
}

