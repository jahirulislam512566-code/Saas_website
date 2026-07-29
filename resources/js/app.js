/**
 * AgencyPro - Main JavaScript Entry
 */

// Import Bootstrap
import './bootstrap';

// Import Alpine.js
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import focus from '@alpinejs/focus';
import intersect from '@alpinejs/intersect';

// Import Alpine Components
import './alpine/app';
import './alpine/navbar';
import './alpine/dropdown';
import './alpine/modal';
import './alpine/tabs';
import './alpine/accordion';
import './alpine/theme';
import './alpine/toast';
import './alpine/search';
import './alpine/clipboard';

// Import UI Components
import './components/slider';
import './components/counter';
import './components/animation';
import './components/gallery';
import './components/lazyload';

// Import Page Specific
import './pages/home';
import './pages/pricing';
import './pages/portfolio';
import './pages/blog';
import './pages/contact';

// Register Alpine.js Plugins
Alpine.plugin(collapse);
Alpine.plugin(focus);
Alpine.plugin(intersect);

// Start Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Enable hot module replacement
if (import.meta.hot) {
    import.meta.hot.accept();
}