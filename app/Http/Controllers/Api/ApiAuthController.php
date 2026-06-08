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

        $client = ApiClient::create([
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
}
