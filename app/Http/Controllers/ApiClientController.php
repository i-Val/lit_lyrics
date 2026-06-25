<?php

namespace App\Http\Controllers;

use App\Models\ApiClient;
use Illuminate\Http\Request;

class ApiClientController extends Controller
{
    public function index(Request $request)
    {
        $q = (string) $request->query('q', '');

        $clients = ApiClient::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($subQuery) use ($q) {
                    $subQuery->where('name', 'like', '%'.$q.'%')
                        ->orWhere('email', 'like', '%'.$q.'%');
                });
            })
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('dashboard.api-clients.index', compact('clients', 'q'));
    }

    public function edit(ApiClient $apiClient)
    {
        return view('dashboard.api-clients.edit', compact('apiClient'));
    }

    public function update(Request $request, ApiClient $apiClient)
    {
        $validated = $request->validate([
            'is_active' => ['required', 'in:0,1'],
            'subscription_plan' => ['nullable', 'string', 'max:255'],
            'subscription_expires_at' => ['nullable', 'date'],
        ]);

        $apiClient->update([
            'is_active' => $validated['is_active'] === '1',
            'subscription_plan' => $validated['subscription_plan'] ?? null,
            'subscription_expires_at' => $validated['subscription_expires_at'] ?? null,
        ]);

        return redirect()
            ->route('dashboard.api-clients.edit', $apiClient)
            ->with('status', 'API client updated successfully.');
    }

    public function destroy(ApiClient $apiClient)
    {
        $apiClient->delete();

        return redirect()
            ->route('dashboard.api-clients.index')
            ->with('status', 'API client deleted successfully.');
    }

    public function resetKey(ApiClient $apiClient)
    {
        $rawKey = ApiClient::generateApiKey();

        $apiClient->update([
            'api_key_hash' => ApiClient::hashApiKey($rawKey),
            'api_key_created_at' => now(),
        ]);

        return redirect()
            ->route('dashboard.api-clients.edit', $apiClient)
            ->with('status', 'API key reset successfully.')
            ->with('new_api_key', $rawKey);
    }
}
