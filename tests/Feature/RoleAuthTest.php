<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAuthTest extends TestCase
{
    use RefreshDatabase;

    private function createUser(string $role): User
    {
        return User::create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => str_replace(' ', '', $role) . '@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => $role,
            'email_verified_at' => now(),
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_user_role_can_access_dashboard_and_lyrics_but_not_settings_or_users(): void
    {
        $user = $this->createUser('user');

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get('/lyrics');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get('/users');
        $response->assertStatus(403);

        $response = $this->actingAs($user)->get('/settings');
        $response->assertStatus(403);
    }

    public function test_admin_role_can_access_users_but_not_settings(): void
    {
        $admin = $this->createUser('admin');

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/users');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/settings');
        $response->assertStatus(403);
    }

    public function test_super_admin_role_can_access_settings(): void
    {
        $superAdmin = $this->createUser('super admin');

        $response = $this->actingAs($superAdmin)->get('/settings');
        $response->assertStatus(200);
    }

    public function test_self_registered_users_default_to_user_role(): void
    {
        $response = $this->post('/register', [
            'firstname' => 'Jane',
            'lastname' => 'Doe',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com',
            'role' => 'user',
        ]);
    }

    public function test_admin_can_assign_user_and_admin_roles_but_not_super_admin(): void
    {
        $admin = $this->createUser('admin');

        // Admin assigns 'admin' role -> Allowed
        $response = $this->actingAs($admin)->post('/users', [
            'firstname' => 'New',
            'lastname' => 'Admin',
            'email' => 'newadmin@example.com',
            'role' => 'admin',
        ]);
        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'email' => 'newadmin@example.com',
            'role' => 'admin',
        ]);

        // Admin assigns 'super admin' role -> Fails validation/security check
        $response2 = $this->actingAs($admin)->post('/users', [
            'firstname' => 'New',
            'lastname' => 'Super',
            'email' => 'newsuper@example.com',
            'role' => 'super admin',
        ]);
        $response2->assertSessionHasErrors(['role']);
        $this->assertDatabaseMissing('users', [
            'email' => 'newsuper@example.com',
        ]);
    }

    public function test_super_admin_can_assign_super_admin_role(): void
    {
        $superAdmin = $this->createUser('super admin');

        $response = $this->actingAs($superAdmin)->post('/users', [
            'firstname' => 'New',
            'lastname' => 'Super',
            'email' => 'newsuper@example.com',
            'role' => 'super admin',
        ]);
        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'email' => 'newsuper@example.com',
            'role' => 'super admin',
        ]);
    }

    public function test_admin_cannot_edit_or_delete_super_admin(): void
    {
        $admin = $this->createUser('admin');
        $superAdmin = $this->createUser('super admin');

        // Try editing
        $response = $this->actingAs($admin)->get("/users/{$superAdmin->id}/edit");
        $response->assertRedirect('/users');
        $response->assertSessionHasErrors();

        // Try updating
        $response = $this->actingAs($admin)->put("/users/{$superAdmin->id}", [
            'firstname' => 'Hacked',
            'lastname' => 'Super',
            'email' => $superAdmin->email,
            'role' => 'user',
        ]);
        $response->assertRedirect('/users');
        $response->assertSessionHasErrors();

        // Try deleting
        $response = $this->actingAs($admin)->delete("/users/{$superAdmin->id}");
        $response->assertRedirect('/users');
        $response->assertSessionHasErrors();
    }
}
