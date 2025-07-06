import { createApp } from 'vue';
import App from './App.vue';
import { router } from './router'; // adapte le chemin si nécessaire

createApp(App)
  .use(router)
  .mount('#app');
