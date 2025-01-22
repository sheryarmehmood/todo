import { createApp } from 'vue';
import App from './components/App.vue'; // Main App component
import router from './router'; // Vue Router setup

const app = createApp(App);
app.use(router);
app.mount('#app');
