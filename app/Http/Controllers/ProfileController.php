<?php

namespace App\Http\Controllers;

use App\Models\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\ProfilePasswordUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit()
    {
        $user = Auth::user();
        $apiClient = ApiClient::query()->where('user_id', $user->id)->first();

        return view('dashboard.profile', [
            'user' => $user,
            'apiClient' => $apiClient,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();

        if ($request->hasFile('profile_photo_path')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $path = $request->file('profile_photo_path')->store('profile-photos', 'public');
            $validated['profile_photo_path'] = $path;
        }

        $user->update($validated);

        return back()->with('status', 'Profile updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(ProfilePasswordUpdateRequest $request)
    {
        $validated = $request->validated();

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'Password updated successfully.');
    }

    public function resetApiKey(Request $request)
    {
        $user = Auth::user();
        $rawKey = ApiClient::generateApiKey();

        $apiClient = ApiClient::query()->where('user_id', $user->id)->first();
        if (! $apiClient) {
            $apiClient = ApiClient::query()->where('email', $user->email)->first();
        }

        $attributes = [
            'user_id' => $user->id,
            'name' => trim($user->firstname.' '.$user->lastname),
            'email' => $user->email,
            'api_key_hash' => ApiClient::hashApiKey($rawKey),
            'api_key_created_at' => now(),
            'is_active' => true,
        ];

        if (! $apiClient) {
            $apiClient = ApiClient::create($attributes + ['requests_count' => 0]);
        } else {
            $apiClient->fill($attributes)->save();
        }

        return back()
            ->with('status', 'API key generated successfully.')
            ->with('new_api_key', $rawKey);
    }
}
