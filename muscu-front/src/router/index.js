import Exercises from '@/components/Exercises.vue';
import Users from '@/components/UserProfile.vue';
import Workouts from '@/components/Workouts.vue';
import Home from '@/views/Homes.vue';
import Login from '@/views/Login.vue';
import Register from '@/views/RegisterForm.vue';
import { createRouter, createWebHistory } from 'vue-router';

const routes = [
  { path: '/', redirect: '/home' },

  { path: '/login', name: 'login', component: Login },

  { path: '/register', name: 'register', component: Register },

  { 
    path: '/home', 
    name: 'home',
    component: Home,
    // plus besoin de meta.requiresAuth ici
  },

  {
    path: '/users',
    name: 'users',
    component: Users,
    meta: { requiresAuth: true },
  },
  {
    path: '/exercises',
    name: 'exercises',
    component: Exercises,
    meta: { requiresAuth: true },
  },
  {
    path: '/workouts',
    name: 'workouts',
    component: Workouts,
    meta: { requiresAuth: true },
  },
];

export const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');
  const requiresAuth = to.meta.requiresAuth === true;

  console.log(`to=${to.path}, token=${token}, requiresAuth=${requiresAuth}`);

  if (requiresAuth && !token) {
    // Cas 1 : route protégée et pas connecté → redirection vers login
    next('/login');
  } else if (token && (to.path === '/login' || to.path === '/register')) {
    // Cas 2 : connecté → ne pas retourner à login/register
    next('/home');
  } else {
    // Cas 3 : accès autorisé
    next();
  }
});
