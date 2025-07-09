import * as yup from 'yup'

// Field-level rules
const nameRule = yup
  .string()
  .required('First name is required')
  .min(2, 'Too short')
  .matches(/^[\p{L}\s'-]+$/u, 'Only letters are allowed')

const surnameRule = yup
  .string()
  .required('Last name is required')
  .min(2, 'Too short')
  .matches(/^[\p{L}\s'-]+$/u, 'Only letters are allowed')

const emailRule = yup
  .string()
  .required('Email is required')
  .email('Invalid email')

const passwordRegisterRule = yup
  .string()
  .required('Password is required')
  .min(6, 'At least 6 characters')

const passwordLoginRule = yup
  .string()
  .required('Password is required')

// Schema-level exports
export const loginSchema = yup.object({
  email: emailRule,
  password: passwordLoginRule
})

export const registerSchema = yup.object({
  name: nameRule,
  surname: surnameRule,
  email: emailRule,
  password: passwordRegisterRule
})
