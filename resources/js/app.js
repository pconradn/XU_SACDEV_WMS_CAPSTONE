import './bootstrap'

import 'preline'

import { createIcons, icons } from 'lucide'

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