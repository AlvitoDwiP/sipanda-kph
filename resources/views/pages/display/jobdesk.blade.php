<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-white min-h-screen">
    <div class="max-w-7xl mx-auto px-6 py-8">
        <div class="flex items-start justify-between mb-8">
            <div>
                <h1 class="text-4xl font-black tracking-tight">SIPANDA-KPH</h1>
                <p class="text-slate-300 text-xl">Display Job Desk Harian</p>
            </div>
            <div class="text-right">
                <div id="clock" class="text-2xl font-bold"></div>
                <div id="dateText" class="text-slate-300"></div>
            </div>
        </div>

        <div id="stateIdle" class="rounded-2xl border border-cyan-700/40 bg-cyan-900/20 p-10 text-center">
            <p class="text-5xl font-extrabold mb-5">Silakan scan QR pegawai Anda</p>
            <p class="text-xl text-slate-300">Scanner siap. Fitur ini bukan absensi.</p>
        </div>

        <div id="stateLoading" class="hidden rounded-2xl border border-blue-700/40 bg-blue-900/20 p-10 text-center">
            <p class="text-4xl font-bold">Memproses QR...</p>
        </div>

        <div id="stateResult" class="hidden mt-6 space-y-6">
            <div class="rounded-2xl border border-emerald-700/40 bg-emerald-900/20 p-6">
                <div class="flex items-center gap-6">
                    <img id="pegawaiFoto" class="w-24 h-24 rounded-full object-cover border border-slate-700 hidden" alt="Foto Pegawai">
                    <div>
                        <p id="pegawaiNama" class="text-4xl font-extrabold"></p>
                        <p id="pegawaiMeta" class="text-slate-200 text-lg"></p>
                    </div>
                </div>
            </div>

            <div class="grid md:grid-cols-4 gap-4">
                <div class="p-4 rounded-xl bg-slate-800 border border-slate-700"><p class="text-slate-300">Total</p><p id="sumTotal" class="text-3xl font-bold">0</p></div>
                <div class="p-4 rounded-xl bg-slate-800 border border-slate-700"><p class="text-slate-300">Selesai</p><p id="sumDone" class="text-3xl font-bold text-green-400">0</p></div>
                <div class="p-4 rounded-xl bg-slate-800 border border-slate-700"><p class="text-slate-300">Belum Selesai</p><p id="sumPending" class="text-3xl font-bold text-amber-400">0</p></div>
                <div class="p-4 rounded-xl bg-slate-800 border border-slate-700"><p class="text-slate-300">Terlambat</p><p id="sumLate" class="text-3xl font-bold text-red-400">0</p></div>
            </div>

            <div class="rounded-2xl border border-slate-700 bg-slate-900/80 p-5">
                <h2 class="text-2xl font-bold mb-3">Job Desk Hari Ini</h2>
                <div id="taskContainer" class="space-y-3"></div>
            </div>
        </div>

        <div id="stateMessage" class="hidden rounded-2xl border p-10 text-center mt-6">
            <p id="messageTitle" class="text-4xl font-extrabold mb-2"></p>
            <p id="messageBody" class="text-xl text-slate-200"></p>
        </div>

        <div class="mt-6 flex justify-between items-center">
            <p class="text-slate-400 text-sm">QR Display Job Desk Harian hanya untuk informasi pekerjaan, bukan absensi.</p>
            <p id="resetCountdown" class="text-slate-300 text-sm"></p>
        </div>

        <div class="mt-6">
            <a href="{{ $backRoute }}" class="inline-block px-4 py-2 rounded bg-slate-700 hover:bg-slate-600 text-sm">Kembali</a>
        </div>
    </div>

    <input id="qrScannerInput" type="text" autocomplete="off" class="fixed -left-[9999px] opacity-0" autofocus>

<script>
const scanRoute = @json($scanRoute);
const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const scannerInput = document.getElementById('qrScannerInput');
const resetCountdown = document.getElementById('resetCountdown');
let timerHandle = null;
let countdownHandle = null;

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
  resetCountdown.textContent = `Tampilan akan kembali dalam ${left} detik.`;
  if (countdownHandle) clearInterval(countdownHandle);
  countdownHandle = setInterval(() => {
    left--;
    resetCountdown.textContent = left > 0 ? `Tampilan akan kembali dalam ${left} detik.` : '';
  }, 1000);

  if (timerHandle) clearTimeout(timerHandle);
  timerHandle = setTimeout(resetToIdle, seconds * 1000);
}

function esc(str){ return (str || '').replace(/[&<>\"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c])); }

function renderTasks(tasks) {
  const container = document.getElementById('taskContainer');
  if (!tasks.length) {
    container.innerHTML = '<div class="text-slate-300">Tidak ada job desk hari ini. Silakan konfirmasi ke admin/KPH jika ada pekerjaan yang belum tercatat.</div>';
    return;
  }

  container.innerHTML = tasks.map(task => {
    const prioClass = task.prioritas === 'tinggi' ? 'bg-red-700/30 text-red-200' : (task.prioritas === 'sedang' ? 'bg-amber-700/30 text-amber-200' : 'bg-emerald-700/30 text-emerald-200');
    const lateBadge = task.is_terlambat ? '<span class="px-2 py-1 rounded text-xs bg-red-800 text-red-100">Terlambat</span>' : '';
    return `<div class="rounded-lg border border-slate-700 bg-slate-800 p-4">
      <div class="flex justify-between items-start gap-3">
        <div>
          <div class="text-xl font-bold">${esc(task.judul)}</div>
          <div class="text-slate-300 text-sm mt-1">${esc(task.instruksi)}</div>
        </div>
        <div class="text-right shrink-0">
          <span class="px-2 py-1 rounded text-xs ${prioClass}">${esc(task.prioritas)}</span>
          ${lateBadge}
        </div>
      </div>
      <div class="mt-3 text-sm text-slate-300">Deadline: ${esc(task.deadline)} | Status: ${esc(task.status_label)}</div>
    </div>`;
  }).join('');
}

function renderSuccess(data) {
  setState('stateResult');
  document.getElementById('pegawaiNama').textContent = data.pegawai.nama;
  document.getElementById('pegawaiMeta').textContent = `${data.pegawai.jabatan} • ${data.pegawai.unit_kerja}`;

  const fotoEl = document.getElementById('pegawaiFoto');
  if (data.pegawai.foto_url) {
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
  box.className = `rounded-2xl border p-10 text-center mt-6 ${danger ? 'border-red-600 bg-red-900/20' : 'border-amber-600 bg-amber-900/20'}`;
  document.getElementById('messageTitle').textContent = title;
  document.getElementById('messageBody').textContent = message;
}

async function submitToken(token) {
  setState('stateLoading');
  try {
    const res = await fetch(scanRoute, {
      method: 'POST',
      headers: {'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},
      body: JSON.stringify({token})
    });
    const data = await res.json();

    if (data.status === 'success') {
      renderSuccess(data);
      scheduleReset(15);
      return;
    }

    if (data.status === 'empty_task') {
      renderSuccess(data);
      scheduleReset(10);
      return;
    }

    if (data.status === 'inactive_employee') {
      renderMessage('Pegawai Tidak Aktif', data.message || 'Silakan hubungi admin/KPH.', true);
      scheduleReset(5);
      return;
    }

    renderMessage('QR Tidak Valid', data.message || 'Silakan gunakan QR pegawai yang terdaftar.', true);
    scheduleReset(5);
  } catch (e) {
    renderMessage('Terjadi Kesalahan', 'Silakan scan ulang.', true);
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

resetToIdle();
</script>
</body>
</html>
