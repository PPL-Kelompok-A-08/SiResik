import React, { useState } from 'react';

interface ActiveJob {
  id: string;
  name: string;
  address: string;
  category: string;
  weight: number;
  status: 'Assigned' | 'On Route' | 'Completed';
}

export default function OfficerPanel() {
  const [job, setJob] = useState<ActiveJob | null>({
    id: 'TSK-992',
    name: 'Ahmad Fauzi',
    address: 'Sukapura No. 45, Dekat Kampus Telkom',
    category: 'Plastik PET',
    weight: 12,
    status: 'Assigned'
  });

  const [hasPhoto, setHasPhoto] = useState(false);

  const updateStatus = (nextStatus: 'On Route' | 'Completed') => {
    if (nextStatus === 'Completed' && !hasPhoto) {
      alert('⚠️ Anda wajib mengambil/mengunggah foto bukti fisik sampah terlebih dahulu!');
      return;
    }
    if (job) setJob({ ...job, status: nextStatus });
  };

  return (
    <div className="max-w-md mx-auto p-4 bg-slate-50 min-h-screen space-y-4">
      {/* STATUS HEADER BAR */}
      <div className="flex items-center justify-between rounded-2xl bg-slate-900 p-4 text-white">
        <div className="flex items-center gap-2">
          <span className="text-2xl">👷‍♂️</span>
          <div>
            <p className="text-xs text-slate-400 font-medium">Tim Lapangan</p>
            <p className="text-sm font-bold">Rian Hermawan</p>
          </div>
        </div>
        <span className="text-xs bg-emerald-600 font-bold px-2.5 py-1 rounded-md uppercase tracking-wider">On Duty</span>
      </div>

      <h2 className="text-lg font-black text-slate-900 px-1 mt-2">Tugas Penjemputan Hari Ini</h2>

      {job ? (
        <div className="rounded-2xl border border-slate-200 bg-white p-5 shadow-xs space-y-4">
          <div className="flex items-start justify-between">
            <div>
              <span className="text-xs font-mono font-bold text-slate-400">{job.id}</span>
              <h3 className="text-lg font-bold text-slate-900 mt-0.5">{job.name}</h3>
              <p className="text-xs text-slate-600 mt-1 bg-slate-100 p-2 rounded-lg">{job.address}</p>
            </div>
            <span className={`text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-sm ${
              job.status === 'Assigned' ? 'bg-amber-100 text-amber-800' :
              job.status === 'On Route' ? 'bg-blue-100 text-blue-800' : 'bg-emerald-100 text-emerald-800'
            }`}>
              {job.status}
            </span>
          </div>

          <div className="grid grid-cols-2 gap-2 text-center text-sm border-y border-slate-100 py-3">
            <div>
              <p className="text-xs text-slate-400 font-medium">Komoditas</p>
              <p className="font-bold text-slate-800 mt-0.5">📦 {job.category}</p>
            </div>
            <div>
              <p className="text-xs text-slate-400 font-medium">Estimasi Bobot</p>
              <p className="font-black text-emerald-700 mt-0.5">{job.weight} Kg</p>
            </div>
          </div>

          {/* SIMULATOR KAMERA BUKTI FISIK */}
          {job.status === 'On Route' && (
            <div className="space-y-2">
              <label className="text-xs font-bold text-slate-500 uppercase tracking-wider block">Verifikasi Kamera Bukti</label>
              <button 
                type="button"
                onClick={() => setHasPhoto(true)}
                className={`w-full border-2 border-dashed rounded-xl py-4 text-xs font-semibold flex flex-col items-center justify-center transition ${
                  hasPhoto ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : 'border-slate-300 bg-slate-50 text-slate-500 hover:bg-slate-100'
                }`}
              >
                {hasPhoto ? (
                  <><span>📸 BUKTI_SAMPAH_TERUNGGAH.JPG</span><span className="text-[10px] text-emerald-500 mt-0.5">(Klik untuk ganti foto)</span></>
                ) : (
                  <><span>📷 Klik untuk Ambil Foto Timbangan</span><span className="text-[10px] text-slate-400 mt-0.5">Maksimal resolusi 5MB</span></>
                )}
              </button>
            </div>
          )}

          {/* TOMBOL AKSI ALUR KERJA */}
          <div className="pt-2">
            {job.status === 'Assigned' && (
              <button 
                onClick={() => updateStatus('On Route')}
                className="w-full rounded-xl bg-emerald-700 py-3 text-xs font-bold text-white shadow-xs hover:bg-emerald-800 transition"
              >
                Mulai Perjalanan Menuju Lokasi ➔
              </button>
            )}
            {job.status === 'On Route' && (
              <button 
                onClick={() => updateStatus('Completed')}
                className="w-full rounded-xl bg-slate-900 py-3 text-xs font-bold text-white shadow-xs hover:bg-black transition"
              >
                Selesaikan & Konfirmasi Poin Warga ✓
              </button>
            )}
            {job.status === 'Completed' && (
              <div className="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold p-3 text-center rounded-xl">
                🎉 Kerja Bagus! Tugas diselesaikan dengan bersih.
              </div>
            )}
          </div>
        </div>
      ) : (
        <div className="text-center py-12 text-slate-400 text-sm">Tidak ada tugas penjemputan aktif saat ini.</div>
      )}
    </div>
  );
}