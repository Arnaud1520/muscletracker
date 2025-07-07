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
