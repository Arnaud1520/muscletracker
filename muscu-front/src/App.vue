<template>
  <router-view />
</template>

<script setup>
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

onMounted(() => {
  // Redirige vers la route nommée 'home' dès que App.vue est monté
  router.push({ name: 'home' })
})
</script>
