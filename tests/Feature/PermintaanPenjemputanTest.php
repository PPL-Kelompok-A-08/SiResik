<?php

namespace Tests\Feature;

use App\Models\KategoriSampah;
use App\Models\PermintaanPenjemputan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermintaanPenjemputanTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_permintaan_penjemputan_memerlukan_login(): void
    {
        $response = $this->get('/permintaan-penjemputan');

        $response->assertRedirect(route('login'));
    }

    public function test_masyarakat_bisa_membuka_halaman_permintaan_penjemputan(): void
    {
        $user = User::factory()->create([
            'role' => 'masyarakat',
        ]);
        $kategori = KategoriSampah::create([
            'nama' => 'Kertas & Karton',
            'deskripsi' => 'Kategori uji',
            'poin_per_kg' => 1200,
        ]);

        $response = $this->actingAs($user)->get('/permintaan-penjemputan');

        $response->assertOk();
        $response->assertSee('Ajukan Penjemputan');
        $response->assertSee($user->email);
        $response->assertSee($kategori->nama);
    }

    public function test_masyarakat_bisa_menyimpan_permintaan_penjemputan_milik_sendiri(): void
    {
        $user = User::factory()->create([
            'role' => 'masyarakat',
        ]);
        $kategori = KategoriSampah::create([
            'nama' => 'Kertas & Karton',
            'deskripsi' => 'Kategori uji',
            'poin_per_kg' => 1200,
        ]);

        $response = $this->actingAs($user)->post('/permintaan-penjemputan', [
            'tanggal' => '2026-04-20',
            'jadwal' => '08.00 - 10.00 WIB',
            'alamat' => 'Gedung A',
            'nomor_telepon' => '081234567890',
            'catatan' => 'Sampah organik dan plastik dipisah',
            'selected_categories' => [$kategori->id],
            'berat' => [
                $kategori->id => 2,
            ],
        ]);

        $permintaan = PermintaanPenjemputan::first();
        $response->assertRedirect(route('permintaan-penjemputan.success', $permintaan));

        $this->assertDatabaseHas('permintaan_penjemputan', [
            'pengguna_id' => $user->id,
            'tanggal' => '2026-04-20',
            'status' => 'Menunggu',
            'alamat' => 'Gedung A',
            'nomor_telepon' => '081234567890',
            'total_estimasi_poin' => 2400,
        ]);

        $this->assertDatabaseHas('permintaan_penjemputan_items', [
            'permintaan_penjemputan_id' => $permintaan->id,
            'kategori_sampah_id' => $kategori->id,
            'estimasi_poin' => 2400,
        ]);

        $this->assertSame(1, PermintaanPenjemputan::count());
    }

    public function test_petugas_tidak_bisa_membuat_permintaan_penjemputan(): void
    {
        $user = User::factory()->petugas()->create();
        $kategori = KategoriSampah::create([
            'nama' => 'Logam (Aluminium)',
            'deskripsi' => 'Kategori uji',
            'poin_per_kg' => 1800,
        ]);

        $response = $this->actingAs($user)->post('/permintaan-penjemputan', [
            'tanggal' => '2026-04-20',
            'jadwal' => '08.00 - 10.00 WIB',
            'alamat' => 'Gedung A',
            'nomor_telepon' => '081234567890',
            'catatan' => 'Tidak boleh membuat',
            'selected_categories' => [$kategori->id],
            'berat' => [
                $kategori->id => 1,
            ],
        ]);

        $response->assertForbidden();
    }
}
