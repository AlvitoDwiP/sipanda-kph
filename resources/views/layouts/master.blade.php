<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Web Kepegawaian</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Custom scrollbar untuk sidebar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased">

    <div class="flex h-screen overflow-hidden" id="app-layout">


        {{-- SIDEBAR --}}
        @include('layouts.components.sidebar')
        <!-- Overlay for mobile sidebar -->
        <div id="overlay" onclick="toggleSidebar()" class="fixed inset-0 z-40 hidden bg-black/50 lg:hidden"></div>

        <!-- MAIN WRAPPER (HEADER + CONTENT + FOOTER) -->
        <div class="flex flex-col flex-1 w-full overflow-hidden">

            {{-- HEADER --}}
            @include('layouts.components.header')
            <!-- ==========================================
                 MAIN CONTENT AREA
                 ========================================== -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @yield('content')
                @stack('scripts')

            </main>

            {{-- FOOTER --}}
            @include('layouts.components.footer')
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div id="logout-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div id="modal-overlay" class="fixed inset-0 transition-opacity" onclick="hideLogoutModal()">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Modal panel -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Logout Confirmation</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Are you sure you want to logout? You'll need to sign in again to access your account.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                    </form>
                    <button type="button"
                        onclick="document.getElementById('logout-form').submit()"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Logout
                    </button>
                    <button type="button"
                        onclick="hideLogoutModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script to Handle Mobile Sidebar Toggle and Logout Modal -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');

            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function toggleSidebarDropdown(id, arrowId = null) {
            const dropdown = document.getElementById(id);
            const arrow = arrowId ? document.getElementById(arrowId) : null;

            if (!dropdown) return;

            dropdown.classList.toggle('hidden');

            if (arrow) {
                arrow.classList.toggle('rotate-180');
            }
        }

        function toggleProfileDropdown() {
            const dropdown = document.getElementById('dropdown-menu');
            dropdown.classList.toggle('hidden');
        }

        document.addEventListener('click', function(e) {
            const profile = document.getElementById('user-profile');
            const dropdown = document.getElementById('dropdown-menu');

            if (profile && dropdown && !profile.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        function openLogoutModal() {
            const modal = document.getElementById('logout-modal');
            modal.classList.remove('hidden');
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function hideLogoutModal() {
            const modal = document.getElementById('logout-modal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    </script>



</body>

</html>