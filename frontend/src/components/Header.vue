<!-- src/components/Header.vue -->
<template>
  <header class="p-4 border-b flex justify-between items-center">
    <h1 class="text-xl font-bold">MyApp</h1>
    <nav class="space-x-4">
      <template v-if="!auth.isAuthenticated">
        <router-link to="/login">Login</router-link>
        <router-link to="/register">Register</router-link>
      </template>
      <template v-else>
        <span class="text-gray-600">Logged in</span>
        <button @click="logout" class="text-red-600 hover:underline">Logout</button>
      </template>
    </nav>
  </header>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth.js'
import { useRouter } from 'vue-router'

const auth = useAuthStore()
const router = useRouter()

function logout() {
  auth.logout()
  router.push('/login')
}
</script>
