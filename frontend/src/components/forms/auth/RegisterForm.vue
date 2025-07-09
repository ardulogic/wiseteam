<script setup>
import {useI18n} from 'vue-i18n'
const {t} = useI18n()

import FormField from '@/components/base/form/FormField.vue'
import FormWrapper from '@/components/base/form/FormWrapper.vue'
import {registerSchema} from '@/validators/auth.js'
import api from "@/services/api.js";

const submitHandler = async (values) => {
  await api.post('/register', values, {skipAuth: true})

  return { message: t('auth.register.success'), pageMessage: t('auth.register.successPage') }
};
</script>

<template>
  <FormWrapper
    :submit-label="t('auth.register.submit')"
    :submit-handler="submitHandler"
    :val-schema="registerSchema"
  >
    <FormField name="name" :label="t('auth.fields.name')"/>
    <FormField name="surname" :label="t('auth.fields.surname')"/>
    <FormField name="email" :label="t('auth.fields.email')" type="email"/>
    <FormField name="password" :label="t('auth.fields.password')" type="password"/>
  </FormWrapper>
</template>
