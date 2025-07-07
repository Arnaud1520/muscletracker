<template>
  <div class="login-container">
    <div class="login-card">
      <h2>Connexion</h2>
      <form @submit.prevent="login">
        <div class="form-group">
          <label for="username">Nom d'utilisateur :</label>
          <input type="text" id="username" v-model="username" required />
        </div>
        <div class="form-group">
          <label for="password">Mot de passe :</label>
          <input type="password" id="password" v-model="password" required />
        </div>
        <button type="submit" class="btn-login">Se connecter</button>
      </form>
      <p v-if="error" class="error">{{ error }}</p>
      <p class="register-link">
        Pas encore de compte ?
        <button class="btn-register" @click="goToRegister">Créer un compte</button>
      </p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Login',
  data() {
    return {
      username: '',
      password: '',
      error: null,
    };
  },
  methods: {
    async login() {
      this.error = null;
      try {
        const response = await axios.post('http://localhost:8000/api/login', {
          username: this.username,
          password: this.password,
        });

        localStorage.setItem('token', response.data.token);
        this.$router.push('/home');
      } catch (err) {
        this.error =
          err.response?.data?.message || 'Erreur lors de la connexion';
      }
    },
    goToRegister() {
      this.$router.push('/register');
    },
  },
};
</script>

<style scoped>
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background: linear-gradient(135deg, #1e1e2f 0%, #2c2c44 100%);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  padding: 1rem;
}

.login-card {
  background-color: #292b3d;
  padding: 2.5rem 3rem;
  border-radius: 16px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.7);
  width: 100%;
  max-width: 420px;
  color: #ddd;
  text-align: center;
  transition: transform 0.3s ease;
}

.login-card:hover {
  transform: translateY(-5px);
}

h2 {
  margin-bottom: 2rem;
  color: #ff5722;
  font-weight: 700;
  font-size: 2rem;
}

.form-group {
  margin-bottom: 1.5rem;
  text-align: left;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: #bbb;
}

input {
  width: 100%;
  padding: 0.8rem 1rem;
  border-radius: 10px;
  border: none;
  font-size: 1rem;
  background-color: #3a3d57;
  color: white;
  box-shadow: inset 0 0 8px rgba(0, 0, 0, 0.6);
  transition: background-color 0.3s ease;
}

input:focus {
  outline: none;
  background-color: #484c6b;
  box-shadow: 0 0 8px #ff5722;
}

.btn-login {
  width: 100%;
  padding: 0.9rem;
  background-color: #ff5722;
  color: white;
  font-weight: 700;
  font-size: 1.1rem;
  border: none;
  border-radius: 12px;
  cursor: pointer;
  box-shadow: 0 5px 15px rgba(255, 87, 34, 0.7);
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
}

.btn-login:hover {
  background-color: #e64a19;
  box-shadow: 0 7px 20px rgba(230, 74, 25, 0.85);
}

.error {
  margin-top: 1.5rem;
  color: #ff4444;
  font-weight: 600;
  text-shadow: 0 0 5px #ff4444aa;
}

.register-link {
  margin-top: 2rem;
  font-size: 1rem;
  color: #bbb;
}

.btn-register {
  background: none;
  border: none;
  color: #ff5722;
  font-weight: 700;
  cursor: pointer;
  text-decoration: underline;
  padding: 0 0.3rem;
  transition: color 0.3s ease;
}

.btn-register:hover {
  color: #e64a19;
}

/* Responsive */
@media (max-width: 480px) {
  .login-card {
    padding: 2rem 1.5rem;
  }
}
</style>

