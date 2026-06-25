<?php

namespace App\Http\Middleware;

use App\Models\ApiClient;
use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $rawKey = $this->extractApiKey($request);
        if ($rawKey === null) {
            return response()->json(['message' => 'API key is required.'], 401);
        }

        $apiKeyHash = ApiClient::hashApiKey($rawKey);
        $client = ApiClient::query()->where('api_key_hash', $apiKeyHash)->first();
        if (! $client) {
            return response()->json(['message' => 'Invalid API key.'], 401);
        }

        if ($client->user_id === null || ! $client->user) {
            return response()->json(['message' => 'API key is not linked to a registered user.'], 403);
        }

        if (method_exists($client->user, 'hasVerifiedEmail') && ! $client->user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email verification required.'], 403);
        }

        if (! $client->is_active) {
            return response()->json(['message' => 'API client is disabled.'], 403);
        }

        $isFree = Setting::get('api_is_free', '1') === '1';
        if (! $isFree && ! $client->isSubscriptionActive()) {
            return response()->json(['message' => 'Active subscription required.'], 402);
        }

        $client->forceFill([
            'last_used_at' => now(),
            'last_used_ip' => $request->ip(),
            'requests_count' => (int) $client->requests_count + 1,
        ])->save();

        $request->attributes->set('apiClient', $client);

        return $next($request);
    }

    private function extractApiKey(Request $request): ?string
    {
        $authHeader = (string) $request->header('Authorization', '');
        if (preg_match('/^Bearer\s+(.+)$/i', $authHeader, $matches) === 1) {
            $token = trim($matches[1]);

            return $token !== '' ? $token : null;
        }

        $key = (string) $request->header('X-API-Key', '');
        $key = trim($key);

        return $key !== '' ? $key : null;
    }
}
