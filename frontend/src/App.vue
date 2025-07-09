<script setup>
import {RouterView} from 'vue-router'
import HeaderNav from "@/components/base/layout/Header.vue";
</script>

<template>
  <HeaderNav></HeaderNav>

  <main>
    <n-message-provider>
      <n-loading-bar-provider>
        <n-dialog-provider>
          <transition name="fade-desaturate" mode="out-in">
            <RouterView />
          </transition>
        </n-dialog-provider>
      </n-loading-bar-provider>
    </n-message-provider>
  </main>
</template>

<style scoped>
@use "@/styles/anims" as *;

.fade-desaturate-enter-active,
.fade-desaturate-leave-active {
  transition: opacity 0.5s ease, filter 0.5s ease;
}

.fade-desaturate-enter-from,
.fade-desaturate-leave-to {
  opacity: 0;
  filter: grayscale(100%);
}

</style>
