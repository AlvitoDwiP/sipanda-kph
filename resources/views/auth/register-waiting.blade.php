<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-100 p-8">

            <!-- Icon -->
            <div class="text-center mb-6">
                <div
                    class="w-14 h-14 mx-auto bg-yellow-500 rounded-xl flex items-center justify-center text-white text-3xl font-bold mb-4">
                    !
                </div>

                <h1 class="text-2xl font-bold text-slate-800">
                    Akun Belum Diverifikasi
                </h1>

                <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                    Akun Anda berhasil dibuat, namun <strong>belum dapat digunakan</strong>.
                    Silakan menunggu verifikasi dari administrator.
                </p>
            </div>

            <!-- Info Box -->
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm rounded-xl p-4 mb-6">
                Anda akan bisa login setelah status akun diaktifkan oleh admin.
            </div>

            <!-- Action -->
            <a href="{{ route('login') }}"
                class="block w-full text-center bg-green-600 hover:bg-green-700
                text-white font-bold py-3 rounded-xl shadow-lg transition">
                Ke Halaman Login
            </a>

        </div>
    </div>
</x-guest-layout>
