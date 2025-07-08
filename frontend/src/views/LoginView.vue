<template>
  <div class="login">
    <h2>Login</h2>
    <form @submit.prevent="login">
      <div>
        <label>Email:</label>
        <input v-model="email" type="email" required />
      </div>
      <div>
        <label>Password:</label>
        <input v-model="password" type="password" required />
      </div>
      <button type="submit">Login</button>
    </form>

    <p v-if="error" style="color: red">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import {useAuthStore} from "@/stores/auth.js";

const email = ref('')
const password = ref('')
const error = ref(null)
const router = useRouter()
const auth = useAuthStore()

async function login() {
  error.value = null
  try {
    const res = await fetch('/api/login_check', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email: email.value, password: password.value }),
    })

    const data = await res.json()

    if (!res.ok) {
      throw new Error(data.message || 'Invalid credentials')
    }

    auth.login(data.token);
    router.push('/dashboard') // Or wherever you want to go after login
  } catch (err) {
    error.value = err.message
  }
}
</script>
