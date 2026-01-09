import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

window.toggleProfileDropdown = function () {
    const dropdown = document.getElementById('profileDropdown')
    if (!dropdown) return

    dropdown.classList.toggle('hidden')
}

// klik di luar dropdown → tutup
document.addEventListener('click', function (event) {
    const dropdown = document.getElementById('profileDropdown')
    const button = event.target.closest('button[onclick="toggleProfileDropdown()"]')

    if (!dropdown) return

    if (!button && !dropdown.contains(event.target)) {
        dropdown.classList.add('hidden')
    }
})

