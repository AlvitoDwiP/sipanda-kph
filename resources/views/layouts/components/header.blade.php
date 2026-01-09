<header class="flex items-center justify-between h-16 px-6 bg-white border-b border-gray-200">
    <div class="flex items-center">
        <button onclick="toggleSidebar()"
            class="p-1 mr-4 text-gray-600 rounded-md lg:hidden hover:bg-gray-100 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    <div class="flex items-center space-x-4">

        {{-- SEARCH --}}
        <div class="hidden md:block relative">
            <input type="text" placeholder="Cari..."
                class="px-4 py-1.5 text-sm bg-gray-100 rounded-full focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none w-64">
        </div>

        {{-- NOTIFICATION --}}
        <button class="p-2 text-gray-400 hover:text-gray-600 border-r pr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11
                    a6.002 6.002 0 00-4-5.659V5
                    a2 2 0 10-4 0v.341
                    C7.67 6.165 6 8.388 6 11v3.159
                    c0 .538-.214 1.055-.595 1.436L4 17h5
                    m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                </path>
            </svg>
        </button>

        {{-- USER DROPDOWN --}}
        <div class="relative" id="user-profile">
            <button type="button"
                onclick="toggleProfileDropdown()"
                class="flex items-center gap-2 hover:bg-gray-50 p-1 rounded-lg">

                <div class="hidden md:block text-right">
                    <p class="text-xs font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-gray-500">{{ Auth::user()->email }}</p>
                </div>

                <div class="w-8 h-8 rounded-full overflow-hidden border border-gray-300 bg-gray-100 flex items-center justify-center">
                    @if (Auth::user()->avatar)
                    <img src="{{ asset('storage/'.Auth::user()->avatar) }}">
                    @else
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    @endif
                </div>

            </button>

            {{-- DROPDOWN MENU --}}
            <div id="dropdown-menu"
                class="absolute right-0 mt-2 w-44 bg-white rounded-md shadow-lg border hidden z-50">

                <button type="button"
                    onclick="openLogoutModal()"
                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Logout
                </button>

            </div>
        </div>

    </div>
</header>