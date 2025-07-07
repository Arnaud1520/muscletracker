<template>
  <div>
    <Navbar /> 

    <div class="user-profile">
      <h2>Mon profil</h2>

      <div v-if="loading">Chargement...</div>
      <div v-if="error" class="error">{{ error }}</div>

      <form v-if="!loading" @submit.prevent="updateUser">
        <div>
          <label for="username">Nom d'utilisateur :</label>
          <input id="username" v-model="user.username" required />
        </div>

        <div>
          <label for="password">Mot de passe (laisser vide pour ne pas changer) :</label>
          <input
            id="password"
            type="password"
            v-model="password"
            placeholder="Nouveau mot de passe"
            autocomplete="new-password"
          />
        </div>

        <div>
          <label for="age">Âge :</label>
          <input id="age" type="number" v-model.number="user.age" min="0" />
        </div>

        <div>
          <label for="poids">Poids (kg) :</label>
          <input id="poids" type="number" step="0.1" v-model.number="user.poids" min="0" />
        </div>

        <button type="submit">Mettre à jour</button>
      </form>

      <button
        @click="deleteAccount"
        class="btn-delete"
        style="margin-top:20px; color: red;"
      >
        Supprimer mon compte
      </button>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import Navbar from '../components/NavBar.vue';

export default {
  name: "UserProfile",
  components: {
    Navbar,
  },
  setup() {
    const user = ref({
      id: null,
      username: "",
      age: null,
      poids: null,
    });
    const password = ref("");
    const loading = ref(true);
    const error = ref(null);

    const router = useRouter();

    const apiBaseUrl = "http://localhost:8000/api/users";

    const fetchUser = async () => {
      loading.value = true;
      error.value = null;
      try {
        const token = localStorage.getItem("token");
        const response = await axios.get(`${apiBaseUrl}/me`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        user.value.id = response.data.id;
        user.value.username = response.data.username || "";
        user.value.age = response.data.age || null;
        user.value.poids = response.data.poids || null;
      } catch (err) {
        error.value = "Impossible de récupérer les données de l'utilisateur.";
      } finally {
        loading.value = false;
      }
    };

    const updateUser = async () => {
      error.value = null;
      try {
        const token = localStorage.getItem("token");
        const payload = {
          username: user.value.username,
          age: user.value.age,
          poids: user.value.poids,
        };
        if (password.value.trim() !== "") {
          payload.password = password.value.trim();
        }

        await axios.put(`${apiBaseUrl}/${user.value.id}`, payload, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        alert("Profil mis à jour avec succès !");
        password.value = "";
      } catch (err) {
        error.value = "Erreur lors de la mise à jour du profil.";
      }
    };

    const deleteAccount = async () => {
      if (
        !confirm(
          "Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible."
        )
      ) {
        return;
      }
      try {
        const token = localStorage.getItem("token");
        await axios.delete(`${apiBaseUrl}/${user.value.id}`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        alert("Compte supprimé avec succès.");
        localStorage.removeItem("token");
        router.push({ path: "/login" });
      } catch (err) {
        error.value = "Erreur lors de la suppression du compte.";
      }
    };

    onMounted(() => {
      fetchUser();
    });

    return {
      user,
      password,
      loading,
      error,
      updateUser,
      deleteAccount,
    };
  },
};
</script>

<style scoped>
.user-profile {
  max-width: 400px;
  margin: auto;
}

label {
  display: block;
  margin-top: 15px;
}

input {
  width: 100%;
  padding: 6px;
  box-sizing: border-box;
}

button {
  margin-top: 15px;
  padding: 8px 12px;
}

.btn-delete {
  background: none;
  border: none;
  cursor: pointer;
  font-weight: bold;
}
.error {
  color: red;
  margin-bottom: 10px;
}
</style>
