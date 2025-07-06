<template>
  <div class="max-w-md mx-auto mt-10 p-6 bg-white shadow-md rounded-xl">
    <h2 class="text-2xl font-semibold mb-4 text-center">Inscription</h2>

    <form @submit.prevent="submitForm">
      <div class="mb-4">
        <label class="block mb-1 font-medium">Nom d'utilisateur</label>
        <input v-model="form.username" type="text" class="w-full p-2 border rounded" required />
      </div>

      <div class="mb-4">
        <label class="block mb-1 font-medium">Mot de passe</label>
        <input v-model="form.password" type="password" class="w-full p-2 border rounded" required />
      </div>

      <div class="mb-4">
        <label class="block mb-1 font-medium">Âge</label>
        <input v-model="form.age" type="number" class="w-full p-2 border rounded" required />
      </div>

      <div class="mb-4">
        <label class="block mb-1 font-medium">Poids (kg)</label>
        <input v-model="form.poids" type="number" step="0.1" class="w-full p-2 border rounded" required />
      </div>

      <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
        S'inscrire
      </button>

      <p v-if="message" class="mt-4 text-center text-green-600 font-medium">{{ message }}</p>
      <p v-if="error" class="mt-4 text-center text-red-600 font-medium">{{ error }}</p>
    </form>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'RegisterForm',
  data() {
    return {
      form: {
        username: '',
        password: '',
        age: '',
        poids: '',
      },
      message: '',
      error: '',
    }
  },
  methods: {
    async submitForm() {
      this.message = ''
      this.error = ''
      try {
        const response = await axios.post('http://localhost:8000/api/register', this.form)
        this.message = 'Inscription réussie ! Vous pouvez maintenant vous connecter.'
        this.form = { username: '', password: '', age: '', poids: '' }
      } catch (err) {
        this.error = err.response?.data?.message || "Une erreur s'est produite."
      }
    },
  },
}
</script>

<style scoped>
/* Tu peux ajouter du style ici si tu veux */
</style>
