<?php

namespace Tests\Feature;

use App\Models\Dinas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // create dinas entries needed
        $bappeda = Dinas::factory()->create([
            'nama_dinas' => 'Bappeda',
            'kode_dinas' => 'bappeda',
        ]);
        $perdagangan = Dinas::factory()->create([
            'nama_dinas' => 'Dinas Perdagangan',
            'kode_dinas' => 'dinas-perdagangan',
        ]);

        // admin dinas user
        User::factory()->create([
            'name' => 'Admin Perdagangan',
            'email' => 'admin.perdagangan@kolakautara.go.id',
            'password' => Hash::make('dinas123'),
            'role' => 'admin_dinas',
            'dinas_id' => $perdagangan->id,
        ]);

        // super admin
        User::factory()->create([
            'name' => 'Admin Bappeda',
            'email' => 'admin.bappeda@kolakautara.go.id',
            'password' => Hash::make('sipandu2025'),
            'role' => 'super_admin',
            'dinas_id' => $bappeda->id,
        ]);
    }

    public function test_login_requires_role_and_email_password(): void
    {
        $resp = $this->post('/login', [
            'email' => '',
            'password' => '',
            'role' => '',
        ]);
        $resp->assertSessionHasErrors(['email', 'password', 'role']);
    }

    public function test_admin_dinas_requires_matching_dinas_selection(): void
    {
        // missing dinas
        $resp = $this->post('/login', [
            'email' => 'admin.perdagangan@kolakautara.go.id',
            'password' => 'dinas123',
            'role' => 'dinas',
        ]);
        $resp->assertSessionHasErrors(['dinas']);

        // wrong dinas
        $resp2 = $this->post('/login', [
            'email' => 'admin.perdagangan@kolakautara.go.id',
            'password' => 'dinas123',
            'role' => 'dinas',
            'dinas' => 'dpmptsp',
        ]);
        $resp2->assertSessionHasErrors(['dinas']);

        // correct dinas
        $resp3 = $this->post('/login', [
            'email' => 'admin.perdagangan@kolakautara.go.id',
            'password' => 'dinas123',
            'role' => 'dinas',
            'dinas' => 'dinas-perdagangan',
        ]);
        $resp3->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_super_admin_does_not_require_dinas_selection(): void
    {
        $resp = $this->post('/login', [
            'email' => 'admin.bappeda@kolakautara.go.id',
            'password' => 'sipandu2025',
            'role' => 'admin',
        ]);
        $resp->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }
}
