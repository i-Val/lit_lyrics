<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('dashboard.users.index', compact('users'));
    }

    public function create()
    {
        return view('dashboard.users.create');
    }

    public function store(UserStoreRequest $request)
    {
        $validated = $request->validated();

        // Security check: Only super admin can assign the 'super admin' role.
        if ($validated['role'] === 'super admin' && Auth::user()->role !== 'super admin') {
            return back()->withInput()->withErrors(['role' => 'Only super admins can assign the super admin role.']);
        }

        // Create user with a temporary random password
        $tempPassword = Str::random(12);
        $user = User::create([
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'email' => $validated['email'],
            'password' => $tempPassword,
            'role' => $validated['role'],
        ]);

        // Send password reset link so the user can set their own password
        Password::sendResetLink(['email' => $user->email]);

        return redirect()->route('dashboard.users.index')->with('status', 'User created. A password reset link has been emailed.');
    }

    public function edit(User $user)
    {
        // Security check: Only super admin can edit a super admin account.
        if ($user->role === 'super admin' && Auth::user()->role !== 'super admin') {
            return redirect()->route('dashboard.users.index')->withErrors(['Only super admins can edit super admin accounts.']);
        }

        return view('dashboard.users.edit', compact('user'));
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $validated = $request->validated();

        // Security check: Only super admin can modify a super admin account.
        if ($user->role === 'super admin' && Auth::user()->role !== 'super admin') {
            return redirect()->route('dashboard.users.index')->withErrors(['Only super admins can modify super admin accounts.']);
        }

        // Security check: Only super admin can promote to super admin.
        if ($validated['role'] === 'super admin' && Auth::user()->role !== 'super admin') {
            return back()->withInput()->withErrors(['role' => 'Only super admins can assign the super admin role.']);
        }

        $user->update($validated);

        return redirect()->route('dashboard.users.edit', $user)->with('status', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return back()->withErrors(['You cannot delete your own account.']);
        }

        // Security check: Only super admin can delete a super admin account.
        if ($user->role === 'super admin' && Auth::user()->role !== 'super admin') {
            return redirect()->route('dashboard.users.index')->withErrors(['Only super admins can delete super admin accounts.']);
        }

        $user->delete();
        return redirect()->route('dashboard.users.index')->with('status', 'User deleted.');
    }

    public function sendResetLink(User $user)
    {
        $status = Password::sendResetLink(['email' => $user->email]);

        return back()->with('status', __($status));
    }
}