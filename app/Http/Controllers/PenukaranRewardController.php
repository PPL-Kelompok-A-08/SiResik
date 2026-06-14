<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use App\Models\PenukaranPoin;
use App\Models\Poin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenukaranRewardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Calculate current points
        $poins = $user->poins;
        $totalPoin = $poins->reduce(function ($carry, $poin) {
            return $carry + ($poin->tipe === 'masuk' ? $poin->jumlah : -$poin->jumlah);
        }, 0);

        // Get available rewards
        $rewards = Reward::where('stok', '>', 0)
            ->where('aktif', true)
            ->orderBy('poin_diperlukan', 'asc')
            ->get();

        return view('reward.index', compact('user', 'totalPoin', 'rewards'));
    }

    public function redeem(Request $request, $id)
    {
        $user = Auth::user();

        try {
            DB::transaction(function () use ($id, $user) {
                // Lock the reward row to prevent race conditions when checking stock
                $reward = Reward::where('aktif', true)->lockForUpdate()->findOrFail($id);

                if ($reward->stok <= 0) {
                    throw new \Exception('Maaf, stok reward ini sudah habis.');
                }

                // Calculate current points securely inside transaction
                $totalPoin = $user->poins()->selectRaw(
                    "COALESCE(SUM(CASE WHEN tipe = 'masuk' THEN jumlah ELSE 0 END), 0) - COALESCE(SUM(CASE WHEN tipe = 'keluar' THEN jumlah ELSE 0 END), 0) as total"
                )->value('total');

                if ($totalPoin < $reward->poin_diperlukan) {
                    throw new \Exception('Poin Anda tidak mencukupi untuk menukar reward ini.');
                }

                // 1. Decrease reward stock
                $reward->decrement('stok', 1);

                // 2. Create PenukaranPoin record
                PenukaranPoin::create([
                    'user_id' => $user->id,
                    'reward_id' => $reward->id,
                    'status_penukaran' => 'menunggu_verifikasi',
                    'tanggal_penukaran' => now(),
                ]);

                // 3. Create Poin record (keluar)
                Poin::create([
                    'user_id' => $user->id,
                    'jumlah' => $reward->poin_diperlukan,
                    'tipe' => 'keluar',
                    'keterangan' => 'Penukaran Reward: ' . $reward->nama,
                    'tanggal' => now(),
                ]);
            });

            return redirect()->route('reward.index')->with('success', 'Berhasil melakukan penukaran reward! Silakan cek riwayat poin Anda.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
