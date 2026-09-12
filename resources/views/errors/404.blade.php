<x-guest-layout>
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-100 p-8 text-center">

        <!-- Error Icon/Image -->
        <div class="mb-6">
            <div class="w-24 h-24 mx-auto bg-slate-50 rounded-full flex items-center justify-center border-4 border-slate-100">
                <i data-lucide="map-pin-off" class="w-12 h-12 text-green-800"></i>
            </div>
        </div>

        <!-- Error Message -->
        <h1 class="text-6xl font-extrabold text-slate-800 mb-2">404</h1>
        <h2 class="text-xl font-bold text-slate-700 mb-3">Halaman Tidak Ditemukan</h2>
        <p class="text-sm text-slate-500 mb-8 leading-relaxed">
            Maaf, halaman yang Anda cari mungkin telah dihapus, diubah namanya, atau tidak pernah ada.
        </p>

        <!-- Back Button -->
        <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 w-full bg-green-800 hover:bg-green-900 text-white font-bold py-3 px-4 rounded-xl shadow-lg transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Kembali ke Beranda
        </a>

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-slate-100">
            <p class="text-xs text-slate-400">
                &copy; {{ date('Y') }} SIPANDA-KPH PERHUTANI
            </p>
        </div>
    </div>
</x-guest-layout>
