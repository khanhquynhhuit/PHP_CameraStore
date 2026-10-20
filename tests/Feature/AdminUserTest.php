<?php

namespace Tests\Feature;

use App\Models\Enums\RoleName;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_user_list(): void
    {
        $customer = User::factory()->customer()->create();
        $this->actingAs($customer)->get(route('admin.users.index'))->assertStatus(403);

        $staff = User::factory()->staff()->create();
        $this->actingAs($staff)->get(route('admin.users.index'))->assertStatus(403);
    }

    public function test_admin_can_view_user_list_and_filter(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->count(5)->customer()->create();
        User::factory()->count(2)->staff()->create();

        $response = $this->actingAs($admin)->get(route('admin.users.index'));
        $response->assertStatus(200);
        $response->assertViewHas('users');
        $response->assertViewHas('roles');

        // Test filter by role
        $filterResponse = $this->actingAs($admin)->get(route('admin.users.index', ['role' => 'staff']));
        $filterResponse->assertStatus(200);
        $users = $filterResponse->viewData('users');
        $this->assertEquals(2, $users->total());
    }

    public function test_admin_can_toggle_user_status(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = User::factory()->customer()->create(['is_active' => true]);

        // Toggle from true to false (Lock)
        $response = $this->actingAs($admin)->patch(route('admin.users.toggle-status', $customer->id));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertFalse($customer->fresh()->is_active);

        // Toggle from false to true (Unlock)
        $response = $this->actingAs($admin)->patch(route('admin.users.toggle-status', $customer->id));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertTrue($customer->fresh()->is_active);
    }

    public function test_admin_cannot_toggle_own_status(): void
    {
        $admin = User::factory()->admin()->create(['is_active' => true]);

        $response = $this->actingAs($admin)->patch(route('admin.users.toggle-status', $admin->id));
        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertTrue($admin->fresh()->is_active);
    }

    public function test_admin_can_update_user_role(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = User::factory()->customer()->create();

        $response = $this->actingAs($admin)->patch(route('admin.users.update-role', $customer->id), [
            'role' => RoleName::STAFF->value,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals(RoleName::STAFF, $customer->fresh()->role);
    }

    public function test_admin_cannot_demote_self_from_admin(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->patch(route('admin.users.update-role', $admin->id), [
            'role' => RoleName::CUSTOMER->value,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertEquals(RoleName::ADMIN, $admin->fresh()->role);
    }
}
