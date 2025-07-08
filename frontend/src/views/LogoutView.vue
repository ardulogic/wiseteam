<template>
  <div>
    <p>Logging you out...</p>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

onMounted(() => {
  // 1. Remove the JWT token from storage
  localStorage.removeItem('token')

  // 2. Optional: clear any user info in state
  // (e.g., via Pinia, Vuex, or a global store)

  // 3. Redirect to login page
  router.push('/login')
})
</script>
