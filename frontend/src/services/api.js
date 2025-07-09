// src/services/api.js
import axios from 'axios'
import router from '@/router'
import { useAuthStore } from '@/stores/auth.js'

const api = axios.create({
  baseURL: '/api',          // adjust if your backend lives elsewhere
  timeout: 10_000,
})

/**
 * 1. Inject the access token on every request
 */
api.interceptors.request.use(config => {
  const { token } = useAuthStore()

  // Only add Authorization header if not explicitly skipped
  if (!config.skipAuth && token) {
    config.headers.Authorization = `Bearer ${token}`
  }

  return config
})

/**
 * 2. Global “401 handler”
 *    – clears auth store
 *    – kicks the user to /login and remembers where they were
 */
api.interceptors.response.use(
  response => response,       // ← all good, just pass through
  error => {
    const { response } = error
    if (response && response.status === 401) {
      const auth = useAuthStore()
      auth.logout?.()                         // Pinia action that wipes token/user
      router.push({
        name: 'login',
        query: { redirect: router.currentRoute.value.fullPath },
      })
    }
    return Promise.reject(error)              // still bubble the error up
  }
)

export default api
