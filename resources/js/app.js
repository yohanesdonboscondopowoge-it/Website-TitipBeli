import './bootstrap';
import AOS from 'aos';
import 'aos/dist/aos.css';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

AOS.init({
    duration: 800,
    easing: 'ease-out-cubic',
    once: true,
    offset: 50,
});