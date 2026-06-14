import React, { useState } from 'react';

export default function EducationPanel() {
  const [quizStarted, setQuizStarted] = useState(false);
  const [selectedAnswer, setSelectedAnswer] = useState<number | null>(null);
  const [quizFinished, setQuizFinished] = useState(false);

  const quizQuestion = {
    text: "Manakah jenis plastik di bawah ini yang paling mudah dan memiliki nilai ekonomi paling tinggi untuk diproses kembali di ekosistem daur ulang?",
    options: [
      "Plastik Sedotan Kantong (Kresek)",
      "Botol Bening PET (Polyethylene Terephthalate)",
      "Kemasan Saset Kopi / Alumunium Foil Multi-layer",
      "Pipa Konstruksi Paralon PVC"
    ],
    correctIndex: 1,
    prizePoints: 250
  };

  const handleSubmittingAnswer = () => {
    if (selectedAnswer === null) return;
    setQuizFinished(true);
  };

  return (
    <div className="max-w-3xl mx-auto p-6 space-y-8 bg-white min-h-screen">
      {/* HEADER SECTION */}
      <div>
        <h1 className="text-2xl font-black text-slate-900">Pusat Edukasi & Literasi Resik</h1>
        <p className="text-sm text-slate-500">Tingkatkan pemahaman lingkungan Anda dan klaim poin insentif tambahan.</p>
      </div>

      {/* ARTIKEL LITERASI PILIHAN */}
      <div className="space-y-4">
        <h2 className="text-lg font-bold text-slate-900 border-b border-slate-100 pb-2">Artikel Wawasan Lingkungan</h2>
        <div className="grid gap-4 sm:grid-cols-2">
          <div className="group rounded-2xl border border-slate-200 p-5 hover:border-emerald-500 hover:shadow-xs transition cursor-pointer">
            <span className="text-2xl bg-slate-100 p-2 rounded-xl inline-block group-hover:bg-emerald-50">🍂</span>
            <h3 className="font-bold text-slate-900 mt-3 group-hover:text-emerald-700 transition">Panduan Pemisahan Sisa Organik Rumah Tangga</h3>
            <p className="text-xs text-slate-500 mt-1.5 leading-relaxed">Pelajari taktik kompos mandiri skala mikro untuk mereduksi bau tidak sedap di dapur keluarga.</p>
          </div>
          <div className="group rounded-2xl border border-slate-200 p-5 hover:border-emerald-500 hover:shadow-xs transition cursor-pointer">
            <span className="text-2xl bg-slate-100 p-2 rounded-xl inline-block group-hover:bg-emerald-50">⚡</span>
            <h3 className="font-bold text-slate-900 mt-3 group-hover:text-emerald-700 transition">Bahaya Terselubung Limbah Elektronik B3</h3>
            <p className="text-xs text-slate-500 mt-1.5 leading-relaxed">Kenapa baterai bekas dan komponen gawai rusak tidak boleh dibuang bersama sampah domestik?</p>
          </div>
        </div>
      </div>

      {/* MODUL KUIS INTERAKTIF BERHADIAH */}
      <div className="rounded-3xl bg-emerald-950 p-6 text-white shadow-md relative overflow-hidden">
        <div className="absolute top-0 right-0 p-8 text-7xl font-bold opacity-10 pointer-events-none select-none">🎯</div>
        
        {!quizStarted ? (
          <div className="space-y-3">
            <span className="bg-emerald-800 text-emerald-200 text-[10px] font-bold tracking-widest uppercase px-2.5 py-1 rounded-md inline-block">Kuis Harian</span>
            <h3 className="text-xl font-bold">Uji Komitmen Pengetahuan Hijau Anda!</h3>
            <p className="text-xs text-emerald-200/80 max-w-xl">Jawab pertanyaan kuis berkala seputar tata kelola limbah dengan benar dan dapatkan bonus tambahan <span className="text-amber-400 font-bold">+{quizQuestion.prizePoints} Poin</span> instan.</p>
            <button 
              onClick={() => setQuizStarted(true)}
              className="mt-2 rounded-xl bg-amber-400 px-5 py-2.5 text-xs font-bold text-emerald-950 shadow-sm hover:bg-amber-300 transition"
            >
              Mulai Sesi Kuis Sekarang
            </button>
          </div>
        ) : (
          <div className="space-y-4 animate-fade-in">
            <div className="flex justify-between items-center border-b border-white/10 pb-2">
              <span className="text-xs font-bold text-amber-300 uppercase tracking-wider">Pertanyaan Aktif</span>
              <span className="text-[10px] bg-white/10 px-2 py-0.5 rounded">Hadiah: {quizQuestion.prizePoints} Pts</span>
            </div>
            <p className="text-sm font-semibold leading-relaxed text-slate-100">{quizQuestion.text}</p>
            
            <div className="space-y-2 pt-2">
              {quizQuestion.options.map((option, idx) => (
                <button
                  key={idx}
                  disabled={quizFinished}
                  onClick={() => setSelectedAnswer(idx)}
                  className={`w-full text-left p-3 rounded-xl text-xs font-medium transition flex items-center justify-between ${
                    quizFinished
                      ? idx === quizQuestion.correctIndex
                        ? 'bg-emerald-600 border border-white text-white'
                        : selectedAnswer === idx ? 'bg-red-900/60 border border-red-500 text-red-200' : 'bg-emerald-900/20 text-emerald-300/50 opacity-50'
                      : selectedAnswer === idx
                        ? 'bg-emerald-800 border-2 border-amber-400 text-white shadow-xs'
                        : 'bg-emerald-900/40 border border-emerald-800 hover:bg-emerald-900/80 text-emerald-100'
                  }`}
                >
                  <span>{idx + 1}. {option}</span>
                  {quizFinished && idx === quizQuestion.correctIndex && <span className="font-bold">✓ Kunci Jawaban</span>}
                </button>
              ))}
            </div>

            {/* ACTION FOOTER */}
            <div className="pt-2 flex justify-end">
              {!quizFinished ? (
                <button 
                  onClick={handleSubmittingAnswer}
                  disabled={selectedAnswer === null}
                  className={`rounded-xl px-5 py-2.5 text-xs font-bold transition ${
                    selectedAnswer !== null ? 'bg-white text-emerald-950 hover:bg-slate-100' : 'bg-white/10 text-white/30 cursor-not-allowed'
                  }`}
                >
                  Kirim Jawaban ➔
                </button>
              ) : (
                <div className="text-right w-full">
                  <p className="text-sm font-bold">
                    {selectedAnswer === quizQuestion.correctIndex 
                      ? `🎉 Jawaban Tepat! +${quizQuestion.prizePoints} Poin ditambahkan.` 
                      : "❌ Jawaban kurang tepat, coba lagi di materi esok hari!"}
                  </p>
                  <button 
                    onClick={() => { setQuizStarted(false); setQuizFinished(false); setSelectedAnswer(null); }}
                    className="mt-3 text-xs underline text-emerald-300 font-semibold"
                  >
                    Kembali ke Beranda Edukasi
                  </button>
                </div>
              )}
            </div>
          </div>
        )}
      </div>
    </div>
  );
}