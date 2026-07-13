<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin()
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_non_admin_cannot_access_admin()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $response = $this->get('/admin');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin()
    {
        $admin = User::factory()->create();
        $role = Role::create(['name' => 'admin', 'slug' => 'admin']);
        $admin->roles()->attach($role);
        
        $this->actingAs($admin);
        
        $response = $this->get('/admin');
        $response->assertStatus(200);
    }

    public function test_inactive_admin_cannot_access_admin()
    {
        $admin = User::factory()->create(['is_active' => false]);
        $role = Role::create(['name' => 'admin', 'slug' => 'admin']);
        $admin->roles()->attach($role);
        
        $this->actingAs($admin);
        
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
        $response->assertSessionHas('error');
    }
}