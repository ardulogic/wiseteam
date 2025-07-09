<template>
  <section>
    <div class="box">
      <slot/>
    </div>
  </section>
</template>

<style scoped lang="scss">
section {
  background-color: $bg-light;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 2rem;

  .box {
    background: $bg-white;
    padding: 2.5rem 2rem;
    border-radius: 1rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    width: 100%;
    max-width: 400px;
    text-align: center;
  }
}
</style>
