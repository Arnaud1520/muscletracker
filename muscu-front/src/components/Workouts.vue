<template>
  <div>
    <h2>Liste des Séances</h2>
    <div v-for="workout in workouts" :key="workout.id" style="margin-bottom: 1.5rem;">
      <h3>{{ workout.nom || 'Séance ' + workout.id }} ({{ workout.date }})</h3>
      <ul>
        <li v-for="ex in workout.exercises" :key="ex.exerciseId">
          {{ ex.nom }} - {{ ex.categorie }}
        </li>
      </ul>
      <form @submit.prevent="addExerciseToWorkout(workout.id)">
        <select v-model="selectedExercise[workout.id]" required>
          <option disabled value="">Choisir un exercice</option>
          <option v-for="ex in allExercises" :key="ex.id" :value="ex.id">
            {{ ex.nom }} ({{ ex.categorie }})
          </option>
        </select>
        <button type="submit">Ajouter exercice</button>
      </form>
    </div>

    <h3>Ajouter une Séance</h3>
    <form @submit.prevent="addWorkout">
      <input v-model="newWorkout.nom" placeholder="Nom de la séance" />
      <input v-model="newWorkout.date" type="date" required />
      <button type="submit">Créer séance</button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const workouts = ref([]);
const allExercises = ref([]);
const selectedExercise = ref({});
const newWorkout = ref({ nom: '', date: '' });

// TODO : adapter avec l'ID utilisateur connecté (ex: via un store, token...)
const USER_ID = 'ton_user_id_ici'; 

const fetchWorkouts = async () => {
  const res = await fetch('http://localhost:8000/api/workouts');
  workouts.value = await res.json();
};

const fetchExercises = async () => {
  const res = await fetch('http://localhost:8000/api/exercises');
  allExercises.value = await res.json();
};

const addWorkout = async () => {
  if (!newWorkout.value.date) {
    alert('La date est requise');
    return;
  }

  await fetch('http://localhost:8000/api/workouts', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      userId: USER_ID,
      nom: newWorkout.value.nom,
      date: newWorkout.value.date,
      exercises: [],
    }),
  });
  newWorkout.value = { nom: '', date: '' };
  fetchWorkouts();
};

const addExerciseToWorkout = async (workoutId) => {
  const exerciseId = selectedExercise.value[workoutId];
  if (!exerciseId) return;

  await fetch(`http://localhost:8000/api/workouts/${workoutId}/add-exercise`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ exerciseId }),
  });

  selectedExercise.value[workoutId] = '';
  fetchWorkouts();
};

onMounted(() => {
  fetchWorkouts();
  fetchExercises();
});
</script>
