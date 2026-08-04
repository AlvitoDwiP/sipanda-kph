<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: "Inter", sans-serif;
            background: linear-gradient(135deg, #022c22 0%, #064e3b 100%);
        }

        .tv-glass {
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
        }

        .tv-glass-subtle {
            border: 1px solid rgba(255, 255, 255, 0.05);
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
        }
    </style>
</head>
<body class="text-white min-h-screen flex flex-col justify-between">
    <div class="max-w-7xl w-full mx-auto px-6 py-10 flex-1 flex flex-col justify-between">
        <!-- HEADER -->
        <div class="flex items-center justify-between pb-6 border-b border-white/10 mb-8">
            <div>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-emerald-200 to-emerald-400">
                    SIPANDA-KPH
                </h1>
                <p class="text-emerald-300/80 text-sm sm:text-base font-semibold mt-0.5">Monitoring Layar Job Desk Harian</p>
            </div>
            <div class="text-right">
                <div id="clock" class="text-xl sm:text-2xl font-black text-white tracking-widest"></div>
                <div id="dateText" class="text-emerald-300 text-xs sm:text-sm font-semibold mt-0.5"></div>
            </div>
        </div>

        <!-- MAIN VIEWS CONTAINER -->
        <div class="flex-1 flex flex-col justify-center">
            <!-- STATE: IDLE -->
            <div id="stateIdle" class="tv-glass rounded-3xl p-10 sm:p-16 text-center space-y-6 max-w-3xl mx-auto w-full transition-all duration-500">
                <div class="mx-auto w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20 mb-2">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h.01M16 20h2M12 16h.01M8 12h.01M8 16h.01M8 20h2M4 12h2M4 16h2M4 20h2" />
                    </svg>
                </div>
                <h2 class="text-3xl sm:text-5xl font-black text-white leading-tight">Silakan Scan QR Pegawai Anda</h2>
                <p class="text-sm sm:text-lg text-emerald-200/70 max-w-xl mx-auto leading-relaxed">
                    Dekatkan kode QR pada kartu pegawai Anda ke mesin scanner untuk menampilkan rincian tugas harian saat ini.
                </p>
                <div class="inline-block px-4 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-300 text-xs font-semibold tracking-wider uppercase">
                    Bukan Absensi Kehadiran
                </div>
            </div>

            <!-- STATE: LOADING -->
            <div id="stateLoading" class="hidden tv-glass rounded-3xl p-16 text-center max-w-md mx-auto w-full">
                <div class="flex flex-col items-center justify-center gap-5">
                    <div class="w-12 h-12 border-4 border-emerald-400 border-t-transparent rounded-full animate-spin"></div>
                    <p class="text-xl sm:text-2xl font-bold tracking-wide">Memproses data QR...</p>
                </div>
            </div>

            <!-- STATE: RESULT -->
            <div id="stateResult" class="hidden space-y-6">
                <!-- PEGAWAI INFO -->
                <div class="tv-glass rounded-2xl p-6 sm:p-8 flex flex-col sm:flex-row items-center gap-6">
                    <img id="pegawaiFoto" class="w-20 h-20 sm:w-24 sm:h-24 rounded-full object-cover border-2 border-emerald-400/30 shadow-lg hidden" alt="Foto Pegawai">
                    <div class="text-center sm:text-left">
                        <h2 id="pegawaiNama" class="text-2xl sm:text-4xl font-black tracking-tight text-white"></h2>
                        <p id="pegawaiMeta" class="text-emerald-300 font-semibold text-sm sm:text-lg mt-1"></p>
                    </div>
                </div>

                <!-- METRICS GRID -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="p-5 rounded-2xl tv-glass-subtle text-center">
                        <p class="text-emerald-300 text-xs sm:text-sm font-semibold uppercase tracking-wider">Total Tugas</p>
                        <p id="sumTotal" class="text-3xl sm:text-4xl font-black mt-2 text-white">0</p>
                    </div>
                    <div class="p-5 rounded-2xl tv-glass-subtle text-center">
                        <p class="text-emerald-300 text-xs sm:text-sm font-semibold uppercase tracking-wider">Selesai</p>
                        <p id="sumDone" class="text-3xl sm:text-4xl font-black mt-2 text-emerald-400">0</p>
                    </div>
                    <div class="p-5 rounded-2xl tv-glass-subtle text-center">
                        <p class="text-emerald-300 text-xs sm:text-sm font-semibold uppercase tracking-wider">Belum Selesai</p>
                        <p id="sumPending" class="text-3xl sm:text-4xl font-black mt-2 text-amber-300">0</p>
                    </div>
                    <div class="p-5 rounded-2xl tv-glass-subtle text-center">
                        <p class="text-emerald-300 text-xs sm:text-sm font-semibold uppercase tracking-wider">Terlambat</p>
                        <p id="sumLate" class="text-3xl sm:text-4xl font-black mt-2 text-red-400">0</p>
                    </div>
                </div>

                <!-- TASKS CONTAINER -->
                <div class="tv-glass rounded-2xl p-6 sm:p-8">
                    <h3 class="text-xl sm:text-2xl font-black text-white mb-5 flex items-center gap-2">
                        <span>Daftar Job Desk Hari Ini</span>
                    </h3>
                    <div id="taskContainer" class="space-y-4"></div>
                </div>
            </div>

            <!-- STATE: MESSAGE -->
            <div id="stateMessage" class="hidden rounded-3xl border p-12 text-center max-w-2xl mx-auto w-full transition-all">
                <p id="messageTitle" class="text-3xl sm:text-4xl font-black mb-3"></p>
                <p id="messageBody" class="text-sm sm:text-lg text-emerald-200/80 leading-relaxed"></p>
            </div>
        </div>

        <!-- FOOTER INFO & BACK BUTTON -->
        <div class="mt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-6 border-t border-white/10">
            <div class="flex items-center gap-3">
                <a href="{{ $backRoute }}" class="px-5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs sm:text-sm font-bold transition">
                    Kembali Ke Dashboard
                </a>
            </div>
            <div class="text-left sm:text-right">
                <p class="text-emerald-300/60 text-[10px] sm:text-xs">
                    Layar display ini terhubung secara real-time ke sistem SIPANDA.
                </p>
                <p id="resetCountdown" class="text-amber-300 text-xs font-semibold mt-0.5"></p>
            </div>
        </div>
    </div>

    <!-- SCANNER FOCUS INPUT -->
    <input id="qrScannerInput" type="text" autocomplete="off" class="fixed -left-[9999px] opacity-0" autofocus>

<script>
const scanRoute = @json($scanRoute);
const stateRoute = @json($stateRoute ?? null);
const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const scannerInput = document.getElementById('qrScannerInput');
const resetCountdown = document.getElementById('resetCountdown');
let timerHandle = null;
let countdownHandle = null;
let lastResetAt = null;
let displayDurationSeconds = Number(@json($displayDurationSeconds ?? 10));
let showEmployeePhoto = Boolean(@json($showEmployeePhoto ?? false));

function keepFocus() { scannerInput.focus(); }
window.addEventListener('click', keepFocus);
window.addEventListener('load', keepFocus);
setInterval(keepFocus, 800);

function updateClock() {
  const now = new Date();
  document.getElementById('clock').textContent = now.toLocaleTimeString('id-ID');
  document.getElementById('dateText').textContent = now.toLocaleDateString('id-ID', {weekday:'long', day:'2-digit', month:'long', year:'numeric'});
}
setInterval(updateClock, 1000); updateClock();

function setState(state) {
  ['stateIdle','stateLoading','stateResult','stateMessage'].forEach(id => document.getElementById(id).classList.add('hidden'));
  document.getElementById(state).classList.remove('hidden');
}

function resetToIdle() {
  if (timerHandle) clearTimeout(timerHandle);
  if (countdownHandle) clearInterval(countdownHandle);
  resetCountdown.textContent = '';
  setState('stateIdle');
  scannerInput.value = '';
  keepFocus();
}

function scheduleReset(seconds) {
  let left = seconds;
  resetCountdown.textContent = `Kembali ke halaman utama dalam ${left} detik.`;
  if (countdownHandle) clearInterval(countdownHandle);
  countdownHandle = setInterval(() => {
    left--;
    resetCountdown.textContent = left > 0 ? `Kembali ke halaman utama dalam ${left} detik.` : '';
  }, 1000);

  if (timerHandle) clearTimeout(timerHandle);
  timerHandle = setTimeout(resetToIdle, seconds * 1000);
}

function esc(str){ return (str || '').replace(/[&<>\"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c])); }

function renderTasks(tasks) {
  const container = document.getElementById('taskContainer');
  if (!tasks.length) {
    container.innerHTML = '<div class="text-center py-6 text-emerald-200/60 font-semibold text-sm sm:text-base">Tidak ada job desk harian terdaftar hari ini.</div>';
    return;
  }

  container.innerHTML = tasks.map(task => {
    const prioClass = task.prioritas === 'tinggi' ? 'bg-red-500/20 text-red-200 border-red-500/20' : (task.prioritas === 'sedang' ? 'bg-amber-500/20 text-amber-200 border-amber-500/20' : 'bg-emerald-500/20 text-emerald-200 border-emerald-500/20');
    const lateBadge = task.is_terlambat ? '<span class="px-2 py-0.5 rounded-full text-[10px] sm:text-xs bg-red-600/35 border border-red-500/30 text-red-100 font-bold uppercase">Terlambat</span>' : '';
    return `<div class="rounded-xl border border-white/10 bg-white/5 p-5 hover:bg-white/10 transition-colors">
      <div class="flex justify-between items-start gap-4">
        <div class="space-y-1">
          <div class="text-lg sm:text-xl font-bold tracking-tight text-white">${esc(task.judul)}</div>
          <div class="text-emerald-200/80 text-xs sm:text-sm leading-relaxed">${esc(task.instruksi)}</div>
        </div>
        <div class="text-right shrink-0 flex items-center gap-1.5 mt-0.5">
          <span class="px-2.5 py-0.5 border rounded-full text-[10px] sm:text-xs font-bold uppercase tracking-wider ${prioClass}">${esc(task.prioritas)}</span>
          ${lateBadge}
        </div>
      </div>
      <div class="mt-4 pt-3 border-t border-white/5 text-[11px] sm:text-xs text-emerald-300 font-semibold flex items-center gap-3">
        <span>Batas Akhir: ${esc(task.deadline)}</span>
        <span class="opacity-30">•</span>
        <span class="capitalize">Status: ${esc(task.status_label)}</span>
      </div>
    </div>`;
  }).join('');
}

function renderSuccess(data) {
  setState('stateResult');
  document.getElementById('pegawaiNama').textContent = data.pegawai.nama;
  document.getElementById('pegawaiMeta').textContent = `${data.pegawai.jabatan} • ${data.pegawai.unit_kerja}`;

  const fotoEl = document.getElementById('pegawaiFoto');
  if (showEmployeePhoto && data.pegawai.foto_url) {
    fotoEl.src = data.pegawai.foto_url;
    fotoEl.classList.remove('hidden');
  } else {
    fotoEl.classList.add('hidden');
  }

  document.getElementById('sumTotal').textContent = data.summary.total_tugas;
  document.getElementById('sumDone').textContent = data.summary.selesai;
  document.getElementById('sumPending').textContent = data.summary.belum_selesai;
  document.getElementById('sumLate').textContent = data.summary.terlambat;
  renderTasks(data.tugas || []);
}

function renderMessage(title, message, danger=false) {
  setState('stateMessage');
  const box = document.getElementById('stateMessage');
  box.className = `rounded-3xl border p-12 text-center max-w-2xl mx-auto w-full transition-all ${danger ? 'border-red-500/30 bg-red-950/40 text-red-100' : 'border-amber-500/30 bg-amber-950/40 text-amber-100'}`;
  document.getElementById('messageTitle').textContent = title;
  document.getElementById('messageBody').textContent = message;
}

async function pollState() {
  if (!stateRoute) return;
  try {
    const res = await fetch(stateRoute, { headers: { 'Accept': 'application/json' } });
    if (!res.ok) return;
    const data = await res.json();
    displayDurationSeconds = Number(data.display_duration_seconds || displayDurationSeconds);
    showEmployeePhoto = Boolean(data.show_employee_photo);

    if (data.reset_requested_at && data.reset_requested_at !== lastResetAt) {
      lastResetAt = data.reset_requested_at;
      resetToIdle();
    }
  } catch (e) {}
}

async function submitToken(token) {
  setState('stateLoading');
  try {
    const res = await fetch(scanRoute, {
      method: 'POST',
      headers: {'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},
      body: JSON.stringify({token})
    });
    if (res.status === 429) {
      renderMessage('Terlalu Banyak Scan', 'Terdapat terlalu banyak scan dalam waktu singkat. Silakan tunggu beberapa saat.', true);
      scheduleReset(5);
      return;
    }

    const data = await res.json();

    if (data.status === 'success' || data.status === 'empty_task') {
      renderSuccess(data);
      scheduleReset(displayDurationSeconds);
      return;
    }

    if (data.status === 'inactive_employee') {
      renderMessage('Pegawai Tidak Aktif', data.message || 'Akun pegawai ini sudah tidak aktif lagi. Silakan hubungi admin.', true);
      scheduleReset(5);
      return;
    }

    if (data.status === 'invalid_format') {
      renderMessage('QR Tidak Terbaca', data.message || 'Pastikan posisi QR presisi terhadap scanner.', true);
      scheduleReset(5);
      return;
    }

    renderMessage('QR Tidak Valid', data.message || 'Data pegawai tidak ditemukan dalam sistem.', true);
    scheduleReset(5);
  } catch (e) {
    renderMessage('Terjadi Kesalahan', 'Gagal memproses data. Silakan ulangi pemindaian.', true);
    scheduleReset(5);
  }
}

scannerInput.addEventListener('keydown', (e) => {
  if (e.key === 'Enter') {
    e.preventDefault();
    const token = scannerInput.value.trim().toUpperCase();
    scannerInput.value = '';
    submitToken(token);
  }
});

setInterval(pollState, 5000);
pollState();
resetToIdle();
</script>
</body>
</html>
