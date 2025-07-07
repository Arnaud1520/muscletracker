<template>
  <div class="register-container">
    <h2>Inscription</h2>
    <form @submit.prevent="submitForm">
      <label>Nom d'utilisateur</label>
      <input v-model="form.username" type="text" required />

      <label>Mot de passe</label>
      <input v-model="form.password" type="password" required />

      <label>Âge</label>
      <input v-model="form.age" type="number" required />

      <label>Poids (kg)</label>
      <input v-model="form.poids" type="number" step="0.1" required />

      <button type="submit">S'inscrire</button>
    </form>

    <!-- Bouton pour aller à la connexion -->
    <button class="btn-login-redirect" @click="goToLogin">
      Déjà un compte ? Se connecter
    </button>

    <p v-if="message" class="message">{{ message }}</p>
    <p v-if="error" class="error">{{ error }}</p>
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
    goToLogin() {
      this.$router.push('/login')
    },
  },
}
</script>

<style scoped>
.register-container {
  max-width: 420px;
  margin: 3rem auto;
  padding: 2.5rem 3rem;
  background-color: #292b3d;
  border-radius: 16px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.7);
  color: #ddd;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

h2 {
  text-align: center;
  margin-bottom: 2rem;
  color: #ff5722;
  font-weight: 700;
  font-size: 2rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: #bbb;
}

input[type="text"],
input[type="password"],
input[type="number"] {
  width: 100%;
  padding: 0.8rem 1rem;
  border-radius: 12px;
  border: none;
  background-color: #3a3d57;
  color: white;
  box-shadow: inset 0 0 8px rgba(0, 0, 0, 0.6);
  font-size: 1rem;
  margin-bottom: 1.5rem;
  transition: background-color 0.3s ease;
}

input:focus {
  outline: none;
  background-color: #484c6b;
  box-shadow: 0 0 8px #ff5722;
}

button[type="submit"] {
  width: 100%;
  padding: 1rem;
  background-color: #ff5722;
  color: white;
  font-weight: 700;
  font-size: 1.2rem;
  border: none;
  border-radius: 14px;
  cursor: pointer;
  box-shadow: 0 5px 15px rgba(255, 87, 34, 0.7);
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
  margin-bottom: 1.5rem;
}

button[type="submit"]:hover {
  background-color: #e64a19;
  box-shadow: 0 7px 20px rgba(230, 74, 25, 0.85);
}

.btn-login-redirect {
  width: 100%;
  background: none;
  border: none;
  color: #ff5722;
  font-weight: 700;
  font-size: 1rem;
  text-decoration: underline;
  cursor: pointer;
  padding: 0;
  text-align: center;
}

.btn-login-redirect:hover {
  color: #e64a19;
}

.message {
  margin-top: 1.5rem;
  text-align: center;
  font-weight: 600;
  color: #4caf50;
  text-shadow: 0 0 5px #4caf5080;
}

.error {
  margin-top: 1.5rem;
  text-align: center;
  font-weight: 600;
  color: #ff4444;
  text-shadow: 0 0 5px #ff4444aa;
}

/* Responsive */
@media (max-width: 480px) {
  .register-container {
    margin: 2rem 1rem;
    padding: 2rem 1.5rem;
  }
}
</style>
