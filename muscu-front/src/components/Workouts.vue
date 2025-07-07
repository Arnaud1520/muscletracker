<template>
  <div>
    <Navbar />
    <h1>{{ editMode ? 'Modifier' : 'Créer' }} une séance</h1>

    <form @submit.prevent="editMode ? updateWorkout() : createWorkout()">
      <div>
        <label>Nom de la séance :</label>
        <input v-model="workout.name" required />
      </div>

      <h2>Exercices</h2>
      <div
        v-for="(ex, index) in workout.exercises"
        :key="index"
        class="exercise-input"
      >
        <select v-model="ex.exercise_id" required>
          <option value="">-- Choisir un exercice --</option>
          <option v-for="exercise in allExercises" :key="exercise.id" :value="exercise.id">
            {{ exercise.nom }}
          </option>
        </select>
        <input type="number" v-model.number="ex.repetitions" placeholder="Répétitions" min="1" required />
        <input type="number" v-model.number="ex.series" placeholder="Séries" min="1" required />
        <input type="number" v-model.number="ex.weight" placeholder="Poids (kg)" min="0" step="0.5" required />
        <button type="button" @click="removeExercise(index)">🗑️</button>
      </div>

      <button type="button" @click="addExercise">+ Ajouter un exercice</button>
      <br /><br />
      <button type="submit">{{ editMode ? 'Mettre à jour' : 'Créer la séance' }}</button>
    </form>

    <div v-if="message">{{ message }}</div>

    <hr />
    <h2>Liste des séances</h2>
    <ul>
      <li v-for="w in allWorkouts" :key="w.id">
        <strong>{{ w.name }}</strong>
        <button @click="editWorkout(w)">✏️</button>
        <button @click="deleteWorkout(w.id)">🗑️</button>
      </li>
    </ul>
  </div>
</template>

<script setup>
import axios from 'axios'
import { onMounted, ref } from 'vue'
import Navbar from '../components/NavBar.vue'

const workout = ref({
  name: '',
  exercises: []
})
const editMode = ref(false)
const editingId = ref(null)

const allExercises = ref([])
const allWorkouts = ref([])
const message = ref('')

const apiWorkoutUrl = 'http://localhost:8000/api/workouts'
const apiExercisesUrl = 'http://localhost:8000/api/exercises'

const authHeader = {
  headers: {
    Authorization: `Bearer ${localStorage.getItem('token')}`
  }
}

async function fetchAllExercises() {
  try {
    const response = await axios.get(apiExercisesUrl, authHeader)
    allExercises.value = response.data
  } catch (err) {
    console.error('Erreur récupération exercices:', err)
  }
}

async function fetchAllWorkouts() {
  try {
    const response = await axios.get(apiWorkoutUrl, authHeader)
    allWorkouts.value = response.data
  } catch (err) {
    console.error('Erreur récupération séances:', err)
  }
}

function addExercise() {
  workout.value.exercises.push({
    exercise_id: '',
    repetitions: 0,
    series: 0,
    weight: 0
  })
}

function removeExercise(index) {
  workout.value.exercises.splice(index, 1)
}

function resetForm() {
  workout.value = { name: '', exercises: [] }
  editMode.value = false
  editingId.value = null
}

async function createWorkout() {
  try {
    const payload = {
      name: workout.value.name,
      exercises: workout.value.exercises.map(ex => ({
        exercise_id: ex.exercise_id,
        repetitions: ex.repetitions,
        series: ex.series,
        weight: ex.weight
      }))
    }

    await axios.post(apiWorkoutUrl, payload, authHeader)
    message.value = '✅ Séance créée !'
    resetForm()
    fetchAllWorkouts()
  } catch (error) {
    console.error(error)
    message.value = '❌ Erreur création séance.'
  }
}

async function updateWorkout() {
  try {
    const payload = {
      name: workout.value.name,
      exercises: workout.value.exercises.map(ex => ({
        exercise_id: ex.exercise_id,
        repetitions: ex.repetitions,
        series: ex.series,
        weight: ex.weight
      }))
    }

    await axios.put(`${apiWorkoutUrl}/${editingId.value}`, payload, authHeader)
    message.value = '✏️ Séance mise à jour.'
    resetForm()
    fetchAllWorkouts()
  } catch (error) {
    console.error(error)
    message.value = '❌ Erreur mise à jour séance.'
  }
}

async function deleteWorkout(id) {
  if (!confirm('Supprimer cette séance ?')) return
  try {
    await axios.delete(`${apiWorkoutUrl}/${id}`, authHeader)
    message.value = '🗑️ Séance supprimée.'
    fetchAllWorkouts()
  } catch (error) {
    console.error(error)
    message.value = '❌ Erreur suppression séance.'
  }
}

function editWorkout(w) {
  editMode.value = true
  editingId.value = w.id
  workout.value.name = w.name
  workout.value.exercises = w.exercises.map(e => ({
    exercise_id: e.exercise.id,
    repetitions: e.repetitions,
    series: e.series,
    weight: e.weight
  }))
}

onMounted(() => {
  fetchAllExercises()
  fetchAllWorkouts()
})
</script>

<style scoped>
.exercise-input {
  margin-bottom: 1rem;
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}
</style>
