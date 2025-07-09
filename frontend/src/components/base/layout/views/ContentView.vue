<template>
  <div class="content-view-wrapper">
    <div class="content-view">
      <slot/>
    </div>
  </div>
</template>

<style scoped lang="scss">
  .content-view-wrapper {
    display: flex;
    justify-content: center;
    width: 100%;
  }

  .content-view {
    max-width: 1024px;
    width: 100%;
    padding: 16px;
  }
</style>
