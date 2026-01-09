<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-100 p-8">

            <!-- Header -->
            <div class="text-center mb-8">
                <div
                    class="w-12 h-12 mx-auto bg-green-600 rounded-lg flex items-center justify-center text-white text-2xl font-bold mb-4">
                    A
                </div>
                <h1 class="text-2xl font-bold text-slate-800">Buat Akun</h1>
                <p class="text-sm text-slate-500 mt-1">Silakan daftar untuk melanjutkan</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="text-sm font-semibold text-slate-700">Nama</label>
                    <input name="name" value="{{ old('name') }}" required
                        class="w-full mt-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-green-600 outline-none text-sm">
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">NIP</label>
                    <input name="nip" value="{{ old('nip') }}" required pattern="\d*" oninput="this.value = this.value.replace(/\D/g,'')" class="w-full mt-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-green-600 outline-none text-sm">
                    <x-input-error :messages="$errors->get('nip')" class="mt-1" />
                </div>


                <div>
                    <label class="text-sm font-semibold text-slate-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full mt-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-green-600 outline-none text-sm">
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Password</label>
                    <input type="password" name="password" required
                        class="w-full mt-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-green-600 outline-none text-sm">
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full mt-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-green-600 outline-none text-sm">
                </div>

                <button
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl shadow-lg mt-4">
                    Daftar
                </button>
            </form>

            <div class="text-center mt-6 text-sm text-slate-500">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-sm font-medium text-green-600 hover:underline">
                    Login
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>