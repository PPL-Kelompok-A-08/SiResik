import './bootstrap';
import React, { useState } from 'react';
import { createRoot } from 'react-dom/client';
import AdminPanel from './components/AdminPanel';
import OfficerPanel from './components/OfficerPanel';
import EducationPanel from './components/EducationPanel';

const WASTE_CATEGORIES = [
    { id: 'kertas', name: 'KERTAS', icon: '📄', color: 'border-amber-400 text-amber-500' },
    { id: 'plastik', name: 'PLASTIK', icon: '⬡', color: 'border-emerald-500 text-emerald-500' },
    { id: 'aluminium', name: 'ALUMINIUM', icon: '🥞', color: 'border-purple-500 text-purple-500' },
    { id: 'besi', name: 'BESI & LOGAM', icon: '🔨', color: 'border-red-500 text-red-500' },
    { id: 'elektronik', name: 'ELEKTRONIK', icon: '⚡', color: 'border-blue-500 text-blue-500' },
    { id: 'kaca', name: 'BOTOL KACA', icon: '🍷', color: 'border-orange-400 text-orange-500' },
    { id: 'merek', name: 'MEREK', icon: '◯', color: 'border-pink-500 text-pink-500' },
    { id: 'khusus', name: 'KHUSUS', icon: '🍂', color: 'border-amber-700 text-amber-800' },
];

function MainApp() {
    const [currentView, setCurrentView] = useState<'landing' | 'login' | 'admin' | 'officer' | 'education'>('landing');
    const [loginTab, setLoginTab] = useState<'masyarakat' | 'admin' | 'petugas'>('masyarakat');
    
    // State Simulasi Input Form Login
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [loginError, setLoginError] = useState('');

    const handleLoginSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        setLoginError('');

        // Simulasi Kredensial Validasi Sesuai Gambar Contoh Gagal Petugas
        if (loginTab === 'petugas' && email !== 'petugas@siresik.id') {
            setLoginError('KREDENSIAL SALAH. GUNAKAN PETUGAS@SIRESIK.ID / PETUGAS123 UNTUK DEMO.');
            return;
        }

        // Alur Berpindah Halaman Berdasarkan Tab yang Sukses Diklik
        if (loginTab === 'admin') setCurrentView('admin');
        else if (loginTab === 'petugas') setCurrentView('officer');
        else setCurrentView('education');
    };

    return (
        <div className="min-h-screen bg-white font-sans text-slate-800 flex flex-col">
            
            {/* BILAH SIMULATOR ASISTEN (Hanya Muncul untuk Pengujian Kelompok) */}
            <div className="bg-slate-900 px-6 py-2 text-center text-[11px] text-slate-400 flex flex-wrap items-center justify-center gap-3 border-b border-slate-800">
                <span className="font-bold text-amber-400 tracking-wider">🛠️ CONTROLLER ACCESSIBILITY:</span>
                <button onClick={() => setCurrentView('landing')} className={`px-2 py-0.5 rounded transition ${currentView === 'landing' ? 'bg-emerald-600 text-white font-bold' : 'hover:text-white'}`}>Landing</button>
                <button onClick={() => setCurrentView('login')} className={`px-2 py-0.5 rounded transition ${currentView === 'login' ? 'bg-emerald-600 text-white font-bold' : 'hover:text-white'}`}>Login Screen</button>
                <button onClick={() => setCurrentView('admin')} className={`px-2 py-0.5 rounded transition ${currentView === 'admin' ? 'bg-emerald-600 text-white font-bold' : 'hover:text-white'}`}>Admin View</button>
                <button onClick={() => setCurrentView('officer')} className={`px-2 py-0.5 rounded transition ${currentView === 'officer' ? 'bg-emerald-600 text-white font-bold' : 'hover:text-white'}`}>Officer View</button>
                <button onClick={() => setCurrentView('education')} className={`px-2 py-0.5 rounded transition ${currentView === 'education' ? 'bg-emerald-600 text-white font-bold' : 'hover:text-white'}`}>Education Panel</button>
            </div>

            {/* HEADBAR NAVIGASI UTAMA (PRESERVED ACCORDING TO SCREENSHOT) */}
            {currentView !== 'login' && (
                <nav className="sticky top-0 z-50 bg-white border-b border-slate-100">
                    <div className="mx-auto flex max-w-7xl items-center justify-between px-6 py-3.5">
                        <div className="flex items-center gap-2 cursor-pointer" onClick={() => setCurrentView('landing')}>
                            <div className="flex h-7 w-7 items-center justify-center rounded-md bg-emerald-500 text-white font-bold text-sm">🌱</div>
                            <span className="text-base font-black tracking-wider text-slate-900">SIRESIK</span>
                        </div>

                        {currentView === 'landing' && (
                            <div className="hidden space-x-8 text-xs font-bold tracking-wider text-slate-400 uppercase md:flex">
                                <a href="#tentang" className="hover:text-emerald-500 transition text-emerald-500">Tentang</a>
                                <a href="#layanan" className="hover:text-emerald-500 transition">Layanan</a>
                                <a href="#jenis-sampah" className="hover:text-emerald-500 transition">Jenis Sampah</a>
                                <a href="#solusi" className="hover:text-emerald-500 transition">Solusi</a>
                            </div>
                        )}

                        <div>
                            {currentView === 'landing' ? (
                                <button 
                                    onClick={() => setCurrentView('login')}
                                    className="rounded-full bg-emerald-500 px-5 py-2 text-xs font-bold text-white shadow-xs hover:bg-emerald-600 transition"
                                >
                                    Masuk Sekarang
                                </button>
                            ) : (
                                <button 
                                    onClick={() => setCurrentView('landing')} 
                                    className="text-xs font-bold text-emerald-600 hover:underline"
                                >
                                    ← Kembali ke Beranda
                                </button>
                            )}
                        </div>
                    </div>
                </nav>
            )}

            {/* AREA UTAMA APLIKASI */}
            <div className="grow">
                {/* 1. VIEW LANDING PAGE */}
                {currentView === 'landing' && (
                    <div className="animate-fade-in bg-slate-50/50">
                        
                        {/* HERO SECTION */}
                        <section id="tentang" className="mx-auto max-w-7xl px-6 py-16 lg:py-24 grid gap-12 md:grid-cols-2 items-center">
                            <div className="relative flex justify-center order-2 md:order-1">
                                <div className="overflow-hidden rounded-3xl shadow-xl aspect-square w-full max-w-md flex flex-col justify-center text-white bg-slate-400 relative">
                                    <div className="absolute inset-0 bg-cover bg-center opacity-40" style={{ backgroundImage: "url('https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?auto=format&fit=crop&w=600&q=80')" }}></div>
                                    <div className="relative z-10 p-12 text-center flex flex-col items-center justify-center h-full bg-emerald-950/80">
                                        <p className="text-lg font-bold">We are The</p>
                                        <h3 className="text-3xl font-black italic tracking-tight mt-1">Recycling Network</h3>
                                        <p className="text-xs tracking-widest text-emerald-300 mt-4 font-mono">#ubahjadikebaikan</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div className="space-y-6 order-1 md:order-2">
                                <h1 className="text-3xl font-black tracking-tight text-slate-900 sm:text-5xl leading-tight">
                                    Misi Kami Menyediakan <span className="text-emerald-500">Akses Daur Ulang</span> Bagi Semua Orang
                                </h1>
                                <p className="text-sm text-slate-500 leading-relaxed font-medium">
                                    Teknologi SiResik didesain untuk menangkap limbah langsung dari sumber timbulnya, dengan menggunakan jejaring pengepul dan petugas lokal sebagai kunci dari rantai daur ulang di Indonesia.
                                </p>
                                
                                <div className="grid grid-cols-4 gap-2 rounded-2xl bg-emerald-800 p-5 text-white shadow-md">
                                    <div className="text-center border-r border-emerald-700/50">
                                        <p className="text-base sm:text-xl font-black">1jt Kg+</p>
                                        <p className="text-[9px] text-emerald-200 mt-0.5 leading-tight">SAMPAH DI DAUR<br/>ULANG</p>
                                    </div>
                                    <div className="text-center border-r border-emerald-700/50">
                                        <p className="text-base sm:text-xl font-black">100+</p>
                                        <p className="text-[9px] text-emerald-200 mt-0.5 leading-tight">GUDANG SORTIR</p>
                                    </div>
                                    <div className="text-center border-r border-emerald-700/50">
                                        <p className="text-base sm:text-xl font-black">500+</p>
                                        <p className="text-[9px] text-emerald-200 mt-0.5 leading-tight">KOLEKTOR LOKAL</p>
                                    </div>
                                    <div className="text-center">
                                        <p className="text-base sm:text-xl font-black">30rb+</p>
                                        <p className="text-[9px] text-emerald-200 mt-0.5 leading-tight">PENGGUNA</p>
                                    </div>
                                </div>
                                <div className="pt-2">
                                    <a href="#tentang" className="text-xs font-bold text-emerald-600 flex items-center gap-1 hover:underline">
                                        Semua Tentang Kami di sini <span>→</span>
                                    </a>
                                </div>
                            </div>
                        </section>

                        {/* SECTION LAYANAN */}
                        <section id="layanan" className="bg-white py-16 border-t border-slate-100">
                            <div className="mx-auto max-w-7xl px-6">
                                <div className="mb-10">
                                    <h2 className="text-2xl font-black text-slate-900">Layanan</h2>
                                    <p className="text-xs font-semibold text-slate-400 mt-1">Revolusi daur ulang dari SiResik untuk semua orang.</p>
                                </div>
                                <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                                    {[
                                        { title: 'Pick Up', color: 'bg-emerald-50 text-emerald-500', icon: '🚚', desc: 'Foto sampah daur ulangmu, upload ke Aplikasi SiResik, kolektor terdekat akan menjemput, menimbang dan membayar sampahmu.' },
                                        { title: 'Drop Off', color: 'bg-blue-50 text-blue-500', icon: '📍', desc: 'Antar langsung sampahmu ke Recycling Centre terdekat, kamu bisa mendaur ulang dengan ukuran kecil seperti satu botol plastik.' },
                                        { title: 'Company', color: 'bg-orange-50 text-orange-500', icon: '🏢', desc: 'Daur ulang berlangganan untuk bisnis dan kantor, menciptakan bisnis ramah lingkungan bukan sesuatu yang mahal lagi.' },
                                        { title: 'Event', color: 'bg-red-50 text-red-500', icon: '📅', desc: 'Daftarkan eventmu di fitur ini untuk mengakses layanan daur ulang yang didesain khusus untuk event atau layanan satu waktu.' }
                                    ].map((item, idx) => (
                                        <div key={idx} className="rounded-2xl border border-slate-100 bg-white p-6 shadow-xs hover:shadow-md transition">
                                            <div className={`h-10 w-10 ${item.color} flex items-center justify-center rounded-xl text-lg font-bold`}>{item.icon}</div>
                                            <h3 className="text-base font-black text-slate-900 mb-2 mt-4">{item.title}</h3>
                                            <p className="text-xs text-slate-400 leading-relaxed font-medium">{item.desc}</p>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </section>

                        {/* SECTION JENIS SAMPAH */}
                        <section id="jenis-sampah" className="bg-slate-50/50 py-16 border-t border-slate-100">
                            <div className="mx-auto max-w-7xl px-6 text-center">
                                <h2 className="text-2xl font-black text-slate-900">Jenis Sampah</h2>
                                <p className="text-xs font-semibold text-slate-400 mt-1 mb-10">Lihat semua jenis sampah yang kami daur ulang.</p>
                                
                                <div className="grid grid-cols-2 gap-4 sm:grid-cols-4 max-w-4xl mx-auto">
                                    {WASTE_CATEGORIES.map((cat) => (
                                        <div key={cat.id} className="bg-white rounded-2xl border border-slate-200/60 p-6 flex flex-col items-center justify-center hover:shadow-xs transition cursor-pointer aspect-4/3">
                                            <span className={`text-3xl font-light ${cat.color.split(' ')[1]}`}>{cat.icon}</span>
                                            <span className="text-[10px] font-black tracking-widest text-slate-800 mt-3 uppercase">{cat.name}</span>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </section>

                        {/* SECTION SOLUSI */}
                        <section id="solusi" className="bg-white py-16 border-t border-slate-100">
                            <div className="mx-auto max-w-7xl px-6">
                                <div className="mb-10">
                                    <h2 className="text-2xl font-black text-slate-900">Solusi Kami</h2>
                                    <p className="text-xs font-semibold text-slate-400 mt-1">Sebuah teknologi untuk mengakhiri sampah.</p>
                                </div>
                                <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                                    {[
                                        { title: 'For Everyone', img: 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=400&q=80', desc: 'Daur ulang sampah yang Kamu hasilkan melalui aplikasi SiResik.' },
                                        { title: 'For Business', img: 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=400&q=80', desc: 'Ciptakan bisnis dan kantor ramah lingkungan dengan mendaur ulang sampah yang Anda hasilkan.' },
                                        { title: 'For Corporate & Brand', img: 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=400&q=80', desc: 'Teknologi Kami membantu perusahaan/brand untuk mengumpulkan dan memulihkan produk pasca konsumsi mereka.' },
                                        { title: 'For Government', img: 'https://images.unsplash.com/photo-1577495508048-b635879837f1?auto=format&fit=crop&w=400&q=80', desc: 'SiResik menyediakan solusi teknologi pengelolaan sampah dan daur ulang bagi pemerintah kota dan desa.' }
                                    ].map((sol, index) => (
                                        <div key={index} className="rounded-2xl border border-slate-100 bg-white shadow-xs overflow-hidden flex flex-col h-full">
                                            <div className="h-40 bg-slate-200 bg-cover bg-center" style={{ backgroundImage: `url('${sol.img}')` }}></div>
                                            <div className="p-5 flex flex-col grow justify-between">
                                                <div>
                                                    <h3 className="text-sm font-black text-slate-900 mb-1.5">{sol.title}</h3>
                                                    <p className="text-[11px] text-slate-400 font-medium leading-relaxed mb-4">{sol.desc}</p>
                                                </div>
                                                <button className="w-full border border-slate-200 text-[10px] font-black tracking-wider text-emerald-600 rounded-lg py-2 hover:bg-slate-50 transition uppercase">Cari Tahu</button>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </section>

                        {/* FOOTER AREA (PRESERVED ACCORDING TO SCREENSHOT) */}
                        <footer className="bg-slate-950 text-slate-400 py-16 border-t border-slate-900">
                            <div className="mx-auto max-w-7xl px-6 grid gap-10 sm:grid-cols-2 lg:grid-cols-4 text-xs font-semibold">
                                <div className="space-y-4">
                                    <div className="flex items-center gap-2 text-white">
                                        <div className="flex h-6 w-6 items-center justify-center rounded-md bg-emerald-500 font-bold text-xs text-white">🌱</div>
                                        <span className="text-sm font-black tracking-wider">SIRESIK</span>
                                    </div>
                                    <p className="text-[11px] text-slate-500 leading-relaxed font-medium">Membangun ekosistem daur ulang digital yang berkelanjutan di Indonesia. Mengubah limbah menjadi berkah bagi lingkungan dan ekonomi lokal.</p>
                                    <div className="flex gap-2 pt-2">
                                        <span className="h-7 w-7 rounded-lg bg-slate-900 flex items-center justify-center border border-slate-800 text-slate-400 cursor-pointer hover:text-white">📸</span>
                                        <span className="h-7 w-7 rounded-lg bg-slate-900 flex items-center justify-center border border-slate-800 text-slate-400 cursor-pointer hover:text-white">🐦</span>
                                        <span className="h-7 w-7 rounded-lg bg-slate-900 flex items-center justify-center border border-slate-800 text-slate-400 cursor-pointer hover:text-white">📱</span>
                                    </div>
                                </div>
                                <div className="space-y-2.5">
                                    <h4 className="text-[10px] uppercase font-black tracking-wider text-slate-500">Eksplorasi</h4>
                                    <p className="hover:text-white cursor-pointer italic">Beranda</p>
                                    <p className="hover:text-white cursor-pointer italic">Layanan Utama</p>
                                    <p className="hover:text-white cursor-pointer italic">Pusat Informasi</p>
                                    <p className="hover:text-white cursor-pointer italic">Karir & Relawan</p>
                                </div>
                                <div className="space-y-2.5">
                                    <h4 className="text-[10px] uppercase font-black tracking-wider text-slate-500">Bantuan</h4>
                                    <p className="hover:text-white cursor-pointer italic">Pusat Bantuan</p>
                                    <p className="hover:text-white cursor-pointer italic">Syarat & Ketentuan</p>
                                    <p className="hover:text-white cursor-pointer italic">Kebijakan Privasi</p>
                                    <p className="hover:text-white cursor-pointer italic">Panduan Daur Ulang</p>
                                </div>
                                <div className="space-y-3">
                                    <h4 className="text-[10px] uppercase font-black tracking-wider text-slate-500">Kontak Kami</h4>
                                    <div>
                                        <p className="text-[9px] text-emerald-400 font-black uppercase tracking-wider">Email Support</p>
                                        <p className="text-white font-bold mt-0.5">hi@siresik.id</p>
                                    </div>
                                    <div>
                                        <p className="text-[9px] text-emerald-400 font-black uppercase tracking-wider">Kantor Pusat</p>
                                        <p className="text-white font-medium leading-relaxed mt-0.5">Pintu Masuk Tech Hub, Lt. 3<br/>Jakarta Selatan, Indonesia</p>
                                    </div>
                                </div>
                            </div>
                            <div className="mx-auto max-w-7xl px-6 mt-12 pt-6 border-t border-slate-900 text-[10px] font-bold tracking-widest text-slate-600 flex justify-between uppercase">
                                <span>© 2024 SIRESIK — DIGITAL TRANSFORMATION FOR CIRCULAR ECONOMY</span>
                                <div className="flex gap-4">
                                    <span>Status Sistem</span>
                                    <span>Developer API</span>
                                </div>
                            </div>
                        </footer>

                    </div>
                )}

                {/* 2. VIEW LOGIN PAGE (SOLID ACCORDING TO SCREENSHOT 2026-06-05 132707 & 132730) */}
                {currentView === 'login' && (
                    <div className="animate-fade-in min-h-[90vh] bg-emerald-950 flex items-center justify-center px-4 py-12 relative overflow-hidden">
                        {/* Radial Glow Effect */}
                        <div className="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-emerald-400 via-transparent to-transparent"></div>
                        
                        <div className="w-full max-w-sm bg-white rounded-3xl p-8 shadow-2xl border border-slate-100 z-10 text-center relative">
                            
                            <div className="mb-6 flex flex-col items-center">
                                <div className="h-12 w-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-xl font-bold shadow-md shadow-emerald-500/20">🌱</div>
                                <h2 className="text-xl font-black text-slate-900 mt-3 tracking-wider uppercase">SIRESIK</h2>
                                <p className="text-[9px] uppercase tracking-widest text-slate-400 font-black mt-0.5">Pintu Masuk Ecosystem</p>
                            </div>

                            {/* TAB SELECTION SYSTEM */}
                            <div className="grid grid-cols-3 gap-1 rounded-xl bg-slate-100 p-1 mb-5 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                {(['masyarakat', 'admin', 'petugas'] as const).map((tab) => (
                                    <button
                                        key={tab}
                                        type="button"
                                        onClick={() => { setLoginTab(tab); setLoginError(''); }}
                                        className={`rounded-lg py-2 transition ${loginTab === tab ? 'bg-white text-slate-900 shadow-xs font-black' : 'hover:text-slate-700'}`}
                                    >
                                        {tab}
                                    </button>
                                ))}
                            </div>

                            {/* CONDITIONAL ERROR EMBED (ACCORDING TO SCREENSHOT 2026-06-05 132730) */}
                            {loginError && (
                                <div className="rounded-xl bg-red-50 border border-red-100 p-3 mb-4 text-left">
                                    <p className="text-[9px] font-black uppercase tracking-wider text-red-600 leading-normal">{loginError}</p>
                                </div>
                            )}

                            {/* LOGIN FILL FORM */}
                            <form onSubmit={handleLoginSubmit} className="space-y-4 text-left">
                                <div>
                                    <label className="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Email / ID Pengguna</label>
                                    <input 
                                        type="text" 
                                        value={email}
                                        onChange={(e) => setEmail(e.target.value)}
                                        placeholder="name@example.com"
                                        className="w-full rounded-xl border border-slate-100 bg-slate-50 px-4 py-2.5 text-xs focus:border-emerald-500 focus:bg-white focus:outline-hidden font-medium text-slate-800 transition"
                                        required
                                    />
                                </div>
                                <div>
                                    <div className="flex justify-between items-center mb-1">
                                        <label className="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Kata Sandi</label>
                                        <span className="text-[10px] text-emerald-500 font-bold italic cursor-pointer hover:underline">Lupa Sandi?</span>
                                    </div>
                                    <input 
                                        type="password" 
                                        value={password}
                                        onChange={(e) => setPassword(e.target.value)}
                                        placeholder="••••••••" 
                                        className="w-full rounded-xl border border-slate-100 bg-slate-50 px-4 py-2.5 text-xs focus:border-emerald-500 focus:bg-white focus:outline-hidden text-slate-800 transition"
                                        required
                                    />
                                </div>

                                <button 
                                    type="submit"
                                    className="w-full rounded-xl bg-emerald-950 py-3 text-xs font-black tracking-widest text-white shadow-md hover:bg-emerald-900 transition uppercase flex items-center justify-center gap-1 mt-5"
                                >
                                    Masuk Sekarang <span>→</span>
                                </button>
                            </form>
                            
                            <p className="text-center text-[10px] font-bold text-slate-400 mt-6 uppercase tracking-wider">
                                Belum punya akun? <span className="text-emerald-500 font-black cursor-pointer hover:underline">Daftar Masyarakat</span>
                            </p>
                        </div>
                    </div>
                )}

                {/* 3. DYNAMIC RENDERING PANEL COMPONENT */}
                {currentView === 'admin' && <AdminPanel />}
                {currentView === 'officer' && <OfficerPanel />}
                {currentView === 'education' && <EducationPanel />}
            </div>
        </div>
    );
}

const container = document.getElementById('app');
if (container) {
    const root = createRoot(container);
    root.render(<MainApp />);
}