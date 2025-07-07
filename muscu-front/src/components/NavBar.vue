<template>
  <nav class="navbar">
    <ul class="nav-list">
      <li><router-link to="/users">Users</router-link></li>
      <li><router-link to="/exercises">Exercises</router-link></li>
      <li><router-link to="/workouts">Workouts</router-link></li>
    </ul>

    <!-- Bouton conditionnel avec couleur différente -->
    <button
      v-if="isLoggedIn"
      @click="logout"
      class="btn btn-logout"
    >
      Déconnexion
    </button>
    <button
      v-else
      @click="goToLogin"
      class="btn btn-login"
    >
      Connexion
    </button>
  </nav>
</template>


<script setup>
import { onMounted, ref, watchEffect } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const router = useRouter()
const route = useRoute()

const isLoggedIn = ref(false)

function checkLogin() {
  isLoggedIn.value = !!localStorage.getItem('token')
}

onMounted(checkLogin)
watchEffect(() => {
  checkLogin()
})

function logout() {
  localStorage.removeItem('token')
  checkLogin()
  router.push({ name: 'login' })
  //window.location.reload()
}

function goToLogin() {
  router.push({ name: 'login' })
}
</script>

<style scoped>
.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  background-color: #1e1e2f; /* fond sombre */
  box-shadow: 0 4px 10px rgba(0,0,0,0.7);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.nav-list {
  list-style: none;
  display: flex;
  gap: 2rem;
  margin: 0;
  padding: 0;
}

.nav-list li a {
  color: #ddd;
  text-decoration: none;
  font-weight: 600;
  font-size: 1.05rem;
  transition: color 0.3s ease, border-bottom 0.3s ease;
  padding-bottom: 3px;
  border-bottom: 2px solid transparent;
}

.nav-list li a.router-link-exact-active {
  color: #ff5722;
  border-bottom-color: #ff5722;
  font-weight: 700;
}

.nav-list li a:hover {
  color: #ff7043;
  border-bottom-color: #ff7043;
}

.btn {
  color: white;
  border: none;
  padding: 10px 20px;
  cursor: pointer;
  border-radius: 12px;
  font-weight: 700;
  font-size: 1rem;
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
  box-shadow: 0 3px 8px rgba(0,0,0,0.4);
}

/* Déconnexion = rouge vif fitness */
.btn-logout {
  background-color: #e63946;
  box-shadow: 0 4px 12px rgba(230, 57, 70, 0.7);
}

.btn-logout:hover {
  background-color: #d62828;
  box-shadow: 0 6px 14px rgba(214, 40, 40, 0.8);
}

/* Connexion = bleu vif fitness */
.btn-login {
  background-color: #007BFF;
  box-shadow: 0 4px 12px rgba(0, 123, 255, 0.7);
}

.btn-login:hover {
  background-color: #0056b3;
  box-shadow: 0 6px 14px rgba(0, 86, 179, 0.8);
}

/* Responsive */
@media (max-width: 600px) {
  .navbar {
    flex-direction: column;
    gap: 1rem;
  }
  .nav-list {
    gap: 1.2rem;
  }
  .btn {
    width: 100%;
  }
}
</style>

