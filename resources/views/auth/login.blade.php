<x-guest-layout>
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-100 p-8">

        <!-- Header -->
        <div class="text-center mb-8">
            <img src="{{ asset('assets/images/avatar.png') }}" alt="Avatar" class="w-20 h-20 mx-auto mb-4 object-cover rounded-full">
            <h1 class="text-2xl font-bold text-slate-800">Login</h1>
            <p class="text-sm text-slate-500 mt-1">Silakan masuk untuk melanjutkan</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <label class="text-sm font-semibold text-slate-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full mt-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl
                           focus:ring-2 focus:ring-green-800 outline-none text-sm">
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div>
                <label class="text-sm font-semibold text-slate-700">Password</label>
                <input type="password" name="password" required
                    class="w-full mt-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl
                           focus:ring-2 focus:ring-green-800 outline-none text-sm">
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 text-slate-600">
                    <input type="checkbox" name="remember"
                        class="rounded border-slate-300 text-green-800 focus:ring-green-800">
                    Remember me
                </label>

                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                    class="text-green-800 hover:underline font-medium">
                    Lupa password?
                </a>
                @endif
            </div>

            <!-- Button -->
            <button
                class="w-full bg-green-800 hover:bg-green-900 text-white font-bold py-3 rounded-xl shadow-lg mt-4">
                Login
            </button>
        </form>

        <!-- Footer -->
        <div class="text-center mt-6 text-sm text-slate-500">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-sm font-medium text-green-800 hover:underline">
                Daftar
            </a>
        </div>
    </div>
</x-guest-layout>