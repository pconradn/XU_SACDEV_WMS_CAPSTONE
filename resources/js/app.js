import './bootstrap'

import Alpine from 'alpinejs'

window.Alpine = Alpine

import 'preline'

Alpine.start()

import { createIcons, icons } from 'lucide';

// run after DOM loads
document.addEventListener("DOMContentLoaded", () => {
    createIcons({ icons });
});