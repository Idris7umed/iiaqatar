import './bootstrap';
import { createApp } from 'vue';
import Alpine from 'alpinejs';

// Import Vue components
import EventsList from './components/EventsList.vue';
import CoursesList from './components/CoursesList.vue';
import ProductsList from './components/ProductsList.vue';

// Make Alpine available on window for existing code
window.Alpine = Alpine;
Alpine.start();

// Initialize Vue for specific components
const app = createApp({});

// Register global components
app.component('events-list', EventsList);
app.component('courses-list', CoursesList);
app.component('products-list', ProductsList);

// Mount Vue if there's a vue-app element
if (document.getElementById('vue-app')) {
    app.mount('#vue-app');
}
