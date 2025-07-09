<template>
  <header class="header">
    <div class="container">
      <div class="logo">
        <router-link :to="{ name: 'home' }" class="nav-link"><img :src="logo" id="header-logo"
                                                                  alt="Wiseteam Logo"/>
        </router-link>
      </div>

      <nav class="nav-wrapper">
        <template v-if="!auth.isAuthenticated">
          <router-link :to="{ name: 'login' }" class="nav-link">Sign In</router-link>
        </template>
        <template v-else>
          <a href="#" @click="logout" class="nav-link">Logout</a>
        </template>
      </nav>
    </div>
  </header>
</template>

<script setup>
import {ref} from 'vue'
import {useAuthStore} from '@/stores/auth.js'
import {useRouter} from 'vue-router'
import logo from '@/assets/logo.svg'

const auth = useAuthStore()
const router = useRouter()
const isOpen = ref(false)

function logout() {
  auth.logout()
  router.push({name: 'login'})
  isOpen.value = false
}
</script>

<style lang="scss" scoped>

$header-mobile-breakpoint: 430px;
$header-height: 40px;

.header {
  padding: 16px;
  border-bottom: 1px solid $border-color;
  background-color: $bg-white;
  box-shadow: $shadow-sm;

  .container {
    max-width: 1024px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
  }

  .logo {
    display: flex;
    justify-content: center;

    img {
      max-height: $header-height;
    }

    @media (max-width: $header-mobile-breakpoint) {
      flex: 1 1 100%;
    }
  }

  .nav-wrapper {
    display: flex;
    height: $header-height;
    gap: 1.5rem;
    align-items: center;

    @media (max-width: $header-mobile-breakpoint) {
      flex: 1 1 100%;
      justify-content: space-between;
    }

    .nav-link {
      color: $color-text;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.2s ease;

      &:hover {
        color: $color-primary;
      }
    }

    .welcome-text {
      color: $color-text-muted;
      font-size: 0.95rem;
    }

  }
}
</style>
