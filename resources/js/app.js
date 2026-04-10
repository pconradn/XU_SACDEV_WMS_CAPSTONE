import './bootstrap'

import Alpine from 'alpinejs'

import collapse from '@alpinejs/collapse'

Alpine.plugin(collapse)

window.Alpine = Alpine

import 'preline'

//Alpine.start()

import { createIcons, icons } from 'lucide';

// run after DOM loads
document.addEventListener("DOMContentLoaded", () => {
    createIcons({ icons });
});