<script setup>
import {defineProps} from 'vue'
import {useForm} from 'vee-validate'
import {ref} from 'vue'
import {useMessage} from "naive-ui";
import {useI18n} from 'vue-i18n'
import FormButton from "@/components/base/form/FormButton.vue";

const {t} = useI18n()

const props = defineProps({
    submitHandler: {type: Function, required: true},
    valSchema: Object,
    hideFormOnSuccess: {type: Boolean, default: true},
    submitLabel: {
      type: String,
      default: () => 'Submit'
    },
    initialValues: {
      type: Object,
      default: () => ({})
    },
  }
)

const emit = defineEmits(['success'])
const errorMessage = ref('')
const successMessage = ref('')
const pageMessage = useMessage()

const {
  handleSubmit,
  isSubmitting,
  setErrors,
  resetForm
} = useForm({
  validationSchema: props.valSchema,
  initialValues: props.initialValues
})
const onSubmit = handleSubmit(async (values) => {
  errorMessage.value = ''
  successMessage.value = ''

  try {
    const result = await props.submitHandler(values)
    emit('success', result)
    successMessage.value = result?.message ?? ''

    if (result?.pageMessage) {
      pageMessage.success(result?.pageMessage)
    }
  } catch (err) {
    const response = err.response?.data

    if (response?.errors) {
      setErrors(response.errors)
    }

    errorMessage.value = response?.message ?? t('form.error.generic')
  }
})

defineExpose({
  resetForm,
  setErrors
})
</script>

<template>
  <form @submit.prevent="onSubmit" novalidate>
    <template v-if="!successMessage && hideFormOnSuccess">
      <!-- Form Fields Slot -->
      <slot/>

      <slot name="buttons">
        <FormButton :is-submitting="isSubmitting" :label="submitLabel"/>
      </slot>
    </template>

    <transition name="slide-fade">
      <p v-if="errorMessage" class="form-error">{{ errorMessage }}</p>
    </transition>

    <transition name="slide-fade">
      <p v-if="successMessage" class="form-success">{{ successMessage }}</p>
    </transition>
  </form>
</template>


<style scoped lang="scss">
@use "@/styles/anims" as *;

.form-error {
  color: $color-error-light;
  margin-top: 1rem;
  font-size: 0.95rem;
  text-align: center;
}

.form-success {
  color: $color-success;
  margin-top: 1rem;
  font-size: 0.95rem;
  text-align: center;
}
</style>
