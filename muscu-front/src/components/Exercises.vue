<template>
  <div>
    <Navbar />
    <h1>Mes exercices</h1>

    <!-- Formulaire de création -->
    <form @submit.prevent="createExercise">
      <input v-model="form.nom" type="text" placeholder="Nom de l'exercice" required />
      <input v-model="form.categorie" type="text" placeholder="Catégorie" required />
      <input v-model="form.description" type="text" placeholder="Description" />
      <button type="submit">Ajouter</button>
    </form>

    <!-- Liste des exercices -->
    <ul v-if="exercises.length > 0">
      <li v-for="exercise in exercises" :key="exercise.id">
        <h3>{{ exercise.nom }}</h3>
        <p><strong>Catégorie:</strong> {{ exercise.categorie }}</p>
        <p><strong>Description:</strong> {{ exercise.description || 'Aucune description' }}</p>
        
        <!-- Modification -->
        <button @click="editExercise(exercise)">Modifier</button>

        <!-- Suppression -->
        <button @click="deleteExercise(exercise.id)">Supprimer</button>
      </li>
    </ul>

    <p v-else>Aucun exercice trouvé.</p>

    <!-- Formulaire de modification -->
    <div v-if="editingId">
      <h2>Modifier l'exercice</h2>
      <form @submit.prevent="updateExercise">
        <input v-model="form.nom" type="text" required />
        <input v-model="form.categorie" type="text" required />
        <input v-model="form.description" type="text" />
        <button type="submit">Enregistrer</button>
        <button type="button" @click="cancelEdit">Annuler</button>
      </form>
    </div>
  </div>
</template>

<script setup>
import axios from 'axios';
import { onMounted, ref } from 'vue';
import Navbar from '../components/NavBar.vue';

const exercises = ref([])
const form = ref({
  nom: '',
  categorie: '',
  description: ''
})
const editingId = ref(null)

const apiUrl = 'http://localhost:8000/api/exercises'
const authHeader = {
  headers: {
    Authorization: `Bearer ${localStorage.getItem('token')}`
  }
}

async function fetchExercises() {
  try {
    const response = await axios.get(apiUrl, authHeader)
    exercises.value = response.data
  } catch (error) {
    console.error('Erreur récupération exercices :', error)
  }
}

async function createExercise() {
  try {
    await axios.post(apiUrl, form.value, authHeader)
    await fetchExercises()
    resetForm()
  } catch (error) {
    console.error('Erreur création exercice :', error)
  }
}

function editExercise(exercise) {
  editingId.value = exercise.id
  form.value = { ...exercise }
}

function cancelEdit() {
  editingId.value = null
  resetForm()
}

async function updateExercise() {
  try {
    await axios.put(`${apiUrl}/${editingId.value}`, form.value, authHeader)
    await fetchExercises()
    editingId.value = null
    resetForm()
  } catch (error) {
    console.error('Erreur modification exercice :', error)
  }
}

async function deleteExercise(id) {
  try {
    await axios.delete(`${apiUrl}/${id}`, authHeader)
    await fetchExercises()
  } catch (error) {
    console.error('Erreur suppression exercice :', error)
  }
}

function resetForm() {
  form.value = {
    nom: '',
    categorie: '',
    description: ''
  }
}

onMounted(fetchExercises)
</script>

<style scoped>
body {
  background-color: #121212;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #f4f4f4;
  margin: 0;
  padding: 0;
}

h1, h2 {
  text-align: center;
  color: #ffcc00;
  margin-bottom: 1rem;
}

form {
  background-color: #1f1f1f;
  padding: 1.5rem;
  margin: 2rem auto;
  border-radius: 10px;
  width: 90%;
  max-width: 600px;
  box-shadow: 0 0 15px rgba(255, 204, 0, 0.2);
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

form input {
  padding: 0.75rem;
  border-radius: 8px;
  border: none;
  background-color: #2c2c2c;
  color: #fff;
  font-size: 1rem;
}

form button {
  padding: 0.75rem;
  background-color: #ffcc00;
  border: none;
  border-radius: 8px;
  color: #000;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.3s;
}

form button:hover {
  background-color: #e6b800;
}

ul {
  list-style: none;
  padding: 0;
  margin: 2rem auto;
  width: 90%;
  max-width: 800px;
}

li {
  background-color: #1f1f1f;
  border-left: 5px solid #ffcc00;
  margin-bottom: 1.5rem;
  padding: 1.5rem;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(255, 204, 0, 0.1);
  position: relative;
}

li h3 {
  margin: 0 0 0.5rem 0;
  color: #ffffff;
}

li p {
  margin: 0.25rem 0;
  color: #ccc;
}

li button {
  margin-right: 10px;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: bold;
  transition: background 0.3s;
}

li button:first-of-type {
  background-color: #2196f3;
  color: #fff;
}

li button:first-of-type:hover {
  background-color: #1976d2;
}

li button:last-of-type {
  background-color: #f44336;
  color: #fff;
}

li button:last-of-type:hover {
  background-color: #d32f2f;
}

p {
  text-align: center;
  margin-top: 2rem;
  font-size: 1.1rem;
  color: #888;
}
</style>
