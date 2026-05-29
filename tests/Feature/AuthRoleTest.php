<?php

namespace Tests\Feature;

use App\Models\KategoriSampah;
use App\Models\PermintaanPenjemputan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengguna_bisa_login_dan_diarahkan_ke_dashboard_sesuai_role(): void
    {
        $user = User::factory()->create([
            'role' => 'masyarakat',
            'password' => 'password',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $dashboardResponse = $this->followingRedirects()->get(route('dashboard'));

        $dashboardResponse->assertSee('Dashboard Masyarakat');
    }

    public function test_admin_bisa_mengakses_dashboard_petugas_dan_masyarakat(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('dashboard.admin'))->assertOk();
        $this->actingAs($admin)->get(route('dashboard.petugas'))->assertOk();
        $this->actingAs($admin)->get(route('dashboard.masyarakat'))->assertOk();
    }

    public function test_masyarakat_tidak_bisa_mengakses_dashboard_admin(): void
    {
        $masyarakat = User::factory()->create([
            'role' => 'masyarakat',
        ]);

        $response = $this->actingAs($masyarakat)->get(route('dashboard.admin'));

        $response->assertForbidden();
    }

    public function test_admin_bisa_menjadwalkan_permintaan_ke_petugas(): void
    {
        $admin = User::factory()->admin()->create();
        $petugas = User::factory()->petugas()->create();
        $masyarakat = User::factory()->create([
            'role' => 'masyarakat',
        ]);
        $kategori = KategoriSampah::create([
            'nama' => 'Plastik (PET)',
            'deskripsi' => 'Kategori uji',
            'poin_per_kg' => 800,
        ]);

        $permintaan = PermintaanPenjemputan::create([
            'pengguna_id' => $masyarakat->id,
            'tanggal' => '2026-04-20',
            'jadwal' => '08.00 - 10.00 WIB',
            'status' => 'Menunggu',
            'alamat' => 'Jl. Melati 1',
            'nomor_telepon' => '081234567890',
            'catatan' => '-',
            'total_estimasi_poin' => 800,
        ]);

        $permintaan->items()->create([
            'kategori_sampah_id' => $kategori->id,
            'berat_kg' => 1,
            'estimasi_poin' => 800,
        ]);

        $response = $this->actingAs($admin)->post(route('dashboard.admin.schedule', $permintaan), [
            'scheduled_at' => '2026-04-21T09:30',
            'petugas_id' => $petugas->id,
        ]);

        $response->assertRedirect(route('dashboard.admin'));
        $permintaan->refresh();

        $this->assertSame($petugas->id, $permintaan->petugas_id);
        $this->assertSame('Diproses', $permintaan->status);
        $this->assertSame('2026-04-21 09:30:00', $permintaan->scheduled_at?->format('Y-m-d H:i:s'));
    }
}
