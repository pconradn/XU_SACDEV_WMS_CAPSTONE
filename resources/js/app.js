import './bootstrap'

import Alpine from 'alpinejs'
import collapse from '@alpinejs/collapse'

import 'preline'

import { createIcons, icons } from 'lucide'

Alpine.plugin(collapse)

window.Alpine = Alpine

window.lucide = {
    createIcons,
    icons,
}

window.renderLucideIcons = function () {
    window.lucide.createIcons({
        icons: window.lucide.icons,
    })
}

document.addEventListener('DOMContentLoaded', () => {
    window.renderLucideIcons()
})

Alpine.start()