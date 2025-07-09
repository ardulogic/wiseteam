<script setup>
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
  label: {
    type: String,
    default: null
  },
  isDisabled: {
    type: Boolean,
    default: false
  },
  icon: Object,
})
const emit = defineEmits(['click'])

function handleClick(event) {
  if (props.isSubmitting) return // ignore clicks while submitting
  emit('click', event)
}
</script>

<template>
  <button
    class="table-btn"
    :disabled="props.isDisabled"
    @click="handleClick"
  >
    <component v-if="icon" :is="icon" class="icon" />
    <span v-if="label" v-html="label"></span>
  </button>
</template>

<style scoped lang="scss">
.table-btn {
  background-color: #007bff;
  color: #fff;
  font-size: .75em;
  font-weight: 600;
  padding: .5rem .5rem;
  margin: 0 4px;
  border: none;
  border-radius: .5rem;
  transition: background-color .2s ease;
  cursor: pointer;

  svg {
    width: 16px;
    height: 16px;
  }

  &:hover {
    background-color: $color-primary-darken;
  }

  &:disabled {
    background-color: $color-primary-darken;
    cursor: not-allowed;
  }
}
</style>
