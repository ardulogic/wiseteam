<script setup>
import {Field, ErrorMessage} from 'vee-validate'
import FormItem from "@/components/base/form/FormItem.vue";

defineProps({
  name: String,
  label: String,
  type: {
    type: String,
    default: 'text',
  },
});
</script>

<template>
  <FormItem>
    <label :for="name">{{ label }}</label>
    <Field
      :id="name"
      :name="name"
      :type="type"
      class="form-input"
      :as="'input'"
    />
    <transition name="slide-fade">
      <ErrorMessage :name="name" class="error-message"/>
    </transition>
  </FormItem>
</template>

<style scoped lang="scss">

@use "@/styles/anims" as *;

$form-border: $color-border;
$form-focus: $color-primary;
$form-error: $color-error-light;

label {
  font-weight: 500;
  margin-bottom: 0.5rem;
  color: #111827;
}

.form-input {
  padding: 0.65rem 0.75rem;
  border: 1px solid $form-border;
  border-radius: 0.5rem;
  font-size: 1rem;

  &:focus {
    border-color: $form-focus;
    outline: none;
  }
}

.error-message {
  margin-top: 0.25rem;
  font-size: 0.875rem;
  color: $form-error;
}
</style>
