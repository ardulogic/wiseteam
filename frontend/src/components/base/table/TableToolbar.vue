<script setup>
import { ref } from 'vue'
const emit = defineEmits(['update:search', 'add'])

const searchQuery = ref('')

const onInput = (e) => {
  searchQuery.value = e.target.value
  emit('update:search', searchQuery.value)
}

const onAddClick = () => {
  emit('add')
}
</script>

<template>
  <div class="toolbar">
    <div class="search-wrapper">
      <svg class="search-icon" viewBox="0 0 24 24">
        <path
          fill="currentColor"
          d="M9.5 3A6.5 6.5 0 0 1 16 9.5c0 1.61-.59 3.08-1.56 4.22l4.41 4.41-1.41 1.41-4.41-4.41A6.5 6.5 0 1 1 9.5 3m0 2a4.5 4.5 0 1 0 0 9a4.5 4.5 0 0 0 0-9Z"
        />
      </svg>
      <input
        type="text"
        :value="searchQuery"
        @input="onInput"
        placeholder="Search..."
        class="search-input"
      />
    </div>

    <button class="add-button" @click="onAddClick">
      <svg class="plus-icon" viewBox="0 0 24 24">
        <path
          fill="currentColor"
          d="M19 13H13V19H11V13H5V11H11V5H13V11H19V13Z"
        />
      </svg>
      <span>Add New</span>
    </button>
  </div>
</template>

<style scoped lang="scss">
.toolbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
  margin: 1rem 0;
}

.search-wrapper {
  position: relative;
  flex: 1;
  max-width: 300px;
}

.search-input {
  width: 100%;
  height: 32px;
  padding: 0.5rem 0.5rem 0.5rem 2rem;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 1rem;
  box-sizing: border-box;
}

.search-icon {
  position: absolute;
  top: 50%;
  left: 0.5rem;
  width: 1rem;
  height: 1rem;
  transform: translateY(-50%);
  color: #888;
}

.add-button {
  display: flex;
  align-items: center;
  height: 32px;
  gap: 0.4rem;
  background-color: #3b82f6;
  color: white;
  border: none;
  border-radius: 6px;
  padding: 0.5rem 1rem;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.add-button:hover {
  background-color: #2563eb;
}

.plus-icon {
  width: 1rem;
  height: 1rem;
  fill: currentColor;
}
</style>
