<template>
  <div>
    <Navbar />
    <main class="home-container">
      <h1>Bienvenue sur MuscleTracker</h1>
      <p>Gérez vos utilisateurs, exercices et séances d’entraînement facilement.</p>
    </main>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import Navbar from '../components/NavBar.vue';

const router = useRouter();

</script>

<style scoped>
.home-container {
  padding: 2rem;
  text-align: center;
}
</style>
