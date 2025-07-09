<script setup>
import { defineProps, defineEmits } from 'vue'
import { useI18n } from 'vue-i18n'
const { t } = useI18n()

const props = defineProps({
  name: {
    type: String,
    default: 'submit',
  },
  label: {
    type: String,
    default: 'Submit'
  },
  isSubmitting: {
    type: Boolean,
    default: false
  }
})
const emit = defineEmits(['click'])

function handleClick(event) {
  if (props.isSubmitting) return // ignore clicks while submitting
  emit('click', event)
}
</script>

<template>
  <button
    type="submit"
    class="submit-btn"
    :name="name"
    :disabled="props.isSubmitting"
    :aria-busy="props.isSubmitting"
    @click="handleClick"
  >
    {{ props.isSubmitting ? t('form.buttons.submitWait') : props.label }}
  </button>
</template>

<style scoped lang="scss">
.submit-btn {
  background-color: $color-primary;
  color: white;
  font-weight: 600;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 0.5rem;
  width: 100%;
  cursor: pointer;
  transition: background-color 0.2s ease;

  &:hover {
    background-color: $color-primary-darken;
  }

  &:disabled {
    background-color: $color-primary-darken;
    cursor: not-allowed;
  }
}
</style>
