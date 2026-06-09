import React, { useState } from 'react';

interface TaskAllocation {
  id: string;
  residentName: string;
  address: string;
  wasteType: string;
  weight: number;
  status: 'Pending' | 'Assigned';
  assignedOfficer?: string;
}

export default function AdminPanel() {
  const [officers] = useState(['Budi Santoso', 'Andi Wijaya', 'Siti Rahma']);
  const [tasks, setTasks] = useState<TaskAllocation[]>([
    { id: '1', residentName: 'Ahmad Fauzi', address: 'Sukapura No. 45', wasteType: 'Plastik PET', weight: 12, status: 'Pending' },
    { id: '2', residentName: 'Dewi Lestari', address: 'Paturuman Regensi B-3', wasteType: 'Kertas & Karton', weight: 25, status: 'Pending' },
  ]);

  const handleAssignOfficer = (taskId: string, officerName: string) => {
    setTasks(prev => prev.map(task => 
      task.id === taskId ? { ...task, status: 'Assigned', assignedOfficer: officerName } : task
    ));
    alert(`Petugas ${officerName} berhasil dialokasikan untuk tugas ini!`);
  };

  return (
    <div className="space-y-6 p-6 bg-slate-50 min-h-screen">
      {/* HEADER */}
      <div className="flex flex-col justify-between gap-4 border-b border-slate-200 pb-4 sm:flex-row sm:items-center">
        <div>
          <h1 className="text-2xl font-black text-slate-900">Panel Administrator</h1>
          <p className="text-sm text-slate-500">Pantau metrik, kelola jadwal, dan alokasikan tim lapangan.</p>
        </div>
        <div className="text-xs bg-emerald-100 text-emerald-800 font-bold px-3 py-1.5 rounded-full self-start">
          Mode Supervisor Beraktifitas
        </div>
      </div>

      {/* STATS COUNTER GRID */}
      <div className="grid gap-4 sm:grid-cols-3">
        <div className="rounded-2xl bg-white p-5 shadow-xs border border-slate-200">
          <p className="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Masukan Hari Ini</p>
          <p className="text-3xl font-black text-emerald-950 mt-1">1,240 <span className="text-sm font-medium text-slate-500">Kg</span></p>
        </div>
        <div className="rounded-2xl bg-white p-5 shadow-xs border border-slate-200">
          <p className="text-xs font-bold text-slate-400 uppercase tracking-wider">Permintaan Penjemputan</p>
          <p className="text-3xl font-black text-amber-600 mt-1">14 <span className="text-sm font-medium text-slate-500">Antrean</span></p>
        </div>
        <div className="rounded-2xl bg-white p-5 shadow-xs border border-slate-200">
          <p className="text-xs font-bold text-slate-400 uppercase tracking-wider">Petugas Aktif di Lapangan</p>
          <p className="text-3xl font-black text-blue-600 mt-1">8 / 10 <span className="text-sm font-medium text-slate-500">Personel</span></p>
        </div>
      </div>

      {/* MAIN TWO COLUMN LAYOUT */}
      <div className="grid gap-6 lg:grid-cols-3">
        {/* ALOKASI JADWAL PETUGAS */}
        <div className="rounded-2xl bg-white p-6 shadow-xs border border-slate-200 lg:col-span-2">
          <h2 className="text-lg font-bold text-slate-900 mb-4">Alokasi Penjemputan Logistik</h2>
          <div className="overflow-x-auto">
            <table className="w-full text-left text-sm">
              <thead>
                <tr className="border-b border-slate-100 text-slate-400 font-bold text-xs uppercase tracking-wider">
                  <th className="pb-3">Warga / Alamat</th>
                  <th className="pb-3">Komoditas</th>
                  <th className="pb-3">Bobot</th>
                  <th className="pb-3">Aksi Tugaskan</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100">
                {tasks.map(task => (
                  <tr key={task.id} className="text-slate-700">
                    <td className="py-3.5">
                      <p className="font-bold text-slate-900">{task.residentName}</p>
                      <p className="text-xs text-slate-400 mt-0.5">{task.address}</p>
                    </td>
                    <td className="py-3.5 font-medium">{task.wasteType}</td>
                    <td className="py-3.5 font-bold text-slate-900">{task.weight} Kg</td>
                    <td className="py-3.5">
                      {task.status === 'Pending' ? (
                        <select 
                          onChange={(e) => handleAssignOfficer(task.id, e.target.value)}
                          defaultValue=""
                          className="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1.5 text-xs font-semibold focus:border-emerald-500 focus:bg-white focus:outline-hidden transition"
                        >
                          <option value="" disabled>Pilih Petugas...</option>
                          {officers.map(off => <option key={off} value={off}>{off}</option>)}
                        </select>
                      ) : (
                        <span className="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md">
                          ✓ {task.assignedOfficer}
                        </span>
                      )}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>

        {/* SIMULATOR PETA PENGAWASAN */}
        <div className="rounded-2xl bg-white p-6 shadow-xs border border-slate-200">
          <h2 className="text-lg font-bold text-slate-900 mb-2">Peta Pengawasan Tim</h2>
          <p className="text-xs text-slate-400 mb-4">Visualisasi koordinat tim lapangan secara real-time.</p>
          <div className="relative overflow-hidden rounded-xl bg-slate-900 aspect-square flex flex-col items-center justify-center p-6 text-center text-white">
            {/* Grid background simulation */}
            <div className="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
            <span className="text-4xl mb-2 animate-bounce">📍</span>
            <p className="font-bold text-sm">Simulator Radar SiResik</p>
            <p className="text-xs text-slate-400 mt-1 max-w-xs">3 Petugas terdeteksi bergerak di sekitar area Bojongsoang & Sukapura.</p>
            <div className="mt-4 flex gap-2 text-[10px] font-mono">
              <span className="bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded border border-emerald-500/30">BUDI: -6.9744, 107.6315</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}