<script setup>
import {useI18n} from 'vue-i18n'
import { useAuthStore } from '@/stores/auth.js'
import {useRouter} from "vue-router";
const router = useRouter()
const {t} = useI18n()

import FormField from '@/components/base/form/FormField.vue'
import FormWrapper from '@/components/base/form/FormWrapper.vue'
import {loginSchema} from '@/validators/auth.js'
import api from "@/services/api.js";
const auth = useAuthStore()

const submitHandler = async (values) => {
  const response = await api.post('/login', values, { skipAuth: true })
  auth.login(response.data.token)
  await router.push({ name: 'dashboard' })

  return { message: t('auth.login.success'), pageMessage: t('auth.login.successPage') }
};
</script>

<template>
  <FormWrapper
    :submit-label="t('auth.login.submit')"
    :submit-handler="submitHandler"
    :val-schema="loginSchema"
  >
    <FormField name="email" :label="t('auth.fields.email')" type="email"/>
    <FormField name="password" :label="t('auth.fields.password')" type="password"/>
  </FormWrapper>
</template>
