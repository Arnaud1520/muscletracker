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
  padding: 1rem;
  background-color: #222;
  color: white;
}

.nav-list {
  list-style: none;
  display: flex;
  gap: 1rem;
}

.nav-list li a {
  color: white;
  text-decoration: none;
}

.nav-list li a.router-link-exact-active {
  font-weight: bold;
  border-bottom: 2px solid white;
}

.btn {
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  cursor: pointer;
  border-radius: 4px;
  font-weight: bold;
}

/* Déconnexion = rouge */
.btn-logout {
  background-color: #e63946;
}
.btn-logout:hover {
  background-color: #d62828;
}

/* Connexion = bleu */
.btn-login {
  background-color: #007BFF;
}
.btn-login:hover {
  background-color: #0056b3;
}

</style>
