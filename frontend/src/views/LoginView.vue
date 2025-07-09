<script setup>
import {ref} from 'vue'
import LoginForm from '@/components/forms/auth/LoginForm.vue'
import RegisterForm from '@/components/forms/auth/RegisterForm.vue'
import SectionCenteredBox from "@/components/base/layout/sections/SectionBox.vue";

const showLogin = ref(true)

function toggleForm() {
  showLogin.value = !showLogin.value
}
</script>

<template>
  <SectionCenteredBox class="full-page">
    <h2 class="form-title">{{ showLogin ? 'Sign In' : 'Sign Up' }}</h2>
    <LoginForm v-if="showLogin"/>
    <RegisterForm v-else/>

    <div class="form-toggle">
        <span v-if="showLogin">
          Don't have an account?
          <a @click.prevent="toggleForm" href="#">Sign Up</a>
        </span>
      <span v-else>
          Already have an account?
          <a @click.prevent="toggleForm" href="#">Sign In</a>
        </span>
    </div>
  </SectionCenteredBox>
</template>

<style scoped lang="scss">
.form-title {
  font-size: 1.75rem;
  font-weight: 600;
  margin-bottom: 1.5rem;
  color: $text-dark;
}

.form-toggle {
  margin-top: 1.5rem;
  font-size: 0.95rem;
  color: $text-light;

  a {
    color: $color-primary;
    margin-left: 0.25rem;
    text-decoration: none;
    font-weight: 500;
    cursor: pointer;

    &:hover {
      text-decoration: underline;
    }
  }
}
</style>
