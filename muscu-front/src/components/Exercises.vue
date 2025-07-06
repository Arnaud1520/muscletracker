<template>
  <div>
    <h2>Liste des Exercices</h2>
    <ul>
      <li v-for="exercise in exercises" :key="exercise.id">
        {{ exercise.nom }} - {{ exercise.categorie }} <br/>
        <small>Description: {{ exercise.description || 'Aucune' }}</small>
        <br/>
        <button @click="deleteExercise(exercise.id)">Supprimer</button>
      </li>
    </ul>

    <h3>Ajouter un Exercice</h3>
    <form @submit.prevent="addExercise">
      <input v-model="newExercise.nom" placeholder="Nom de l'exercice" required />
      <input v-model="newExercise.categorie" placeholder="Catégorie (ex: pectoraux)" />
      <input v-model="newExercise.description" placeholder="Description" />
      <button type="submit">Ajouter</button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const exercises = ref([]);
const newExercise = ref({
  nom: '',
  categorie: '',
  description: ''
});

const fetchExercises = async () => {
  const res = await fetch('http://localhost:8000/api/exercises');
  exercises.value = await res.json();
};

const addExercise = async () => {
  await fetch('http://localhost:8000/api/exercises', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(newExercise.value),
  });
  newExercise.value = {
    nom: '',
    categorie: '',
    description: ''
  };
  fetchExercises();
};

const deleteExercise = async (id) => {
  await fetch(`http://localhost:8000/api/exercises/${id}`, {
    method: 'DELETE'
  });
  fetchExercises();
};

onMounted(fetchExercises);
</script>
