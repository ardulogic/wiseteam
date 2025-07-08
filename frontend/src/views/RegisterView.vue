<template>
  <div class="register">
    <h2>Register</h2>
    <form @submit.prevent="register">
      <div>
        <label>Name:</label>
        <input v-model="name" type="text" required />
      </div>
      <div>
        <label>Surname:</label>
        <input v-model="surname" type="text" required />
      </div>
      <div>
        <label>Email:</label>
        <input v-model="email" type="email" required />
      </div>
      <div>
        <label>Password:</label>
        <input v-model="password" type="password" required />
      </div>
      <button type="submit">Register</button>
    </form>

    <p v-if="error" style="color:red">{{ error }}</p>
    <p v-if="success" style="color:green">{{ success }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const email = ref('')
const password = ref('')
const name = ref('')
const surname = ref('')
const error = ref(null)
const success = ref(null)

async function register() {
  error.value = null
  success.value = null

  try {
    const res = await fetch('/api/register', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        name: name.value,
        surname: surname.value,
        email: email.value,
        password: password.value,
      }),
    })

    const data = await res.json()

    if (!res.ok) {
      throw new Error(data.error || 'Registration failed')
    }

    success.value = 'Registration successful! You can now log in.'
  } catch (err) {
    error.value = err.message
  }
}
</script>
