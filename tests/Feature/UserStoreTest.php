<?php

namespace Tests\Feature;

use App\Models\Dinas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_create_admin_dinas_with_dinas_id(): void
    {
        $super = User::factory()->create(['role' => 'super_admin']);
        $dinas = Dinas::factory()->create(['nama_dinas' => 'DPMPTSP']);

        $payload = [
            'name' => 'Admin DPMPTSP',
            'email' => 'admin.dpmptsp@example.com',
            'role' => 'admin_dinas',
            'dinas_id' => $dinas->id,
            'password' => 'secret123',
        ];

        $res = $this->actingAs($super)->postJson('/settings/users', $payload);
        $res->assertStatus(201);
        $id = $res->json('id');
        $this->assertNotEmpty($id);
        $this->assertDatabaseHas('users', ['id' => $id, 'email' => $payload['email'], 'role' => 'admin_dinas', 'dinas_id' => $dinas->id]);
    }

    public function test_admin_dinas_requires_dinas_id(): void
    {
        $super = User::factory()->create(['role' => 'super_admin']);

        $payload = [
            'name' => 'Admin Tanpa Dinas',
            'email' => 'admin.nodinas@example.com',
            'role' => 'admin_dinas',
            'password' => 'secret123',
        ];

        $res = $this->actingAs($super)->postJson('/settings/users', $payload);
        $res->assertStatus(422);
        $this->assertDatabaseMissing('users', ['email' => $payload['email']]);
    }

    public function test_fallback_mapping_dinas_via_opd_name(): void
    {
        $super = User::factory()->create(['role' => 'super_admin']);
        $dinas = Dinas::factory()->create(['nama_dinas' => 'Dinas Perdagangan']);

        $payload = [
            'name' => 'User Perdagangan',
            'email' => 'user.perdagangan@example.com',
            'role' => 'user',
            'opd' => 'Dinas Perdagangan',
            'password' => 'secret123',
        ];

        $res = $this->actingAs($super)->postJson('/settings/users', $payload);
        $res->assertStatus(201);
        $id = $res->json('id');
        $this->assertNotEmpty($id);
        $this->assertDatabaseHas('users', ['id' => $id, 'email' => $payload['email'], 'role' => 'user', 'dinas_id' => $dinas->id]);
    }
}
