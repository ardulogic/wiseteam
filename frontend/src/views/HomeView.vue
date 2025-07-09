<script setup>
import {useRouter} from 'vue-router'
import {useAuthStore} from '@/stores/auth.js'
import heroImage from '@/assets/hero-image.jpg' // Optional image
import { useI18n } from 'vue-i18n'
const { t } = useI18n()

const router = useRouter()
const auth = useAuthStore()

// Redirect logged-in users
if (auth.isAuthenticated) {
  router.push({name: 'dashboard'})
}

function goToLogin() {
  router.push({name: 'login'})
}
</script>

<template>
  <section class="home">
    <div class="content">
      <h1>{{ t('home.title') }}</h1>
      <p class="description">
        {{ t('home.description') }}
      </p>
      <button @click="goToLogin" class="cta-button">{{ t('home.cta') }}</button>
    </div>
    <img :src="heroImage" alt="BookManager Hero Image" class="hero-image"/>
  </section>
</template>

<style scoped lang="scss">

.home {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 3rem 1rem;
  text-align: center;
  background-color: $bg-light;
  min-height: 100vh;

  .content {
    max-width: 600px;

    h1 {
      font-size: 2.5rem;
      color: $text-dark;
      margin-bottom: 1rem;
    }

    .description {
      font-size: 1.125rem;
      color: $text-light;
      margin-bottom: 2rem;
    }

    .cta-button {
      padding: 0.75rem 1.5rem;
      font-size: 1rem;
      font-weight: 600;
      background-color: $color-primary;
      color: white;
      border: none;
      border-radius: 0.5rem;
      cursor: pointer;
      transition: background-color 0.2s ease;

      &:hover {
        background-color: $color-primary-darken;
      }
    }
  }

  .hero-image {
    margin-top: 3rem;
    max-width: 500px;
    width: 100%;
  }
}
</style>
