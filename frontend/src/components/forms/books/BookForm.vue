<script setup>
import {computed} from 'vue'
import {useI18n} from 'vue-i18n'
import { format } from 'date-fns'
import api from '@/services/api'
import {bookSchema} from '@/validators/books.js'
import FormWrapper from '@/components/base/form/FormWrapper.vue'
import FormField from '@/components/base/form/FormField.vue'

const {t} = useI18n()

const props = defineProps({
  onSuccess: Function, // on create / update success
  item: {
    type: Object,
    default: null
  }
})

const isEditing = computed(() => !!props.item?.id)

const initialValues = computed(() => {
  if (props.item) {
    return {
      ...props.item,
      publicationDate: props.item.publicationDate
        ? format(new Date(props.item.publicationDate), 'yyyy-MM-dd')
        : ''
    }
  }

  return {
    title: '',
    author: '',
    isbn: '',
    publicationDate: '',
    genre: '',
    numberOfCopies: 1
  }
})

const submitHandler = async (values) => {
  /* Normalise date (“YYYY-MM-DD”) */
  const publicationDate = values.publicationDate
    ? new Date(values.publicationDate).toISOString().split('T')[0]
    : null

  const payload = {
    ...values,
    publicationDate,
    numberOfCopies: Number(values.numberOfCopies)
  }

  if (isEditing.value) {
    await api.put(`/books/${props.item.id}`, payload)
    return {message: t('books.update.success')}
  } else {
    await api.post('/books', payload)
    return {message: t('books.create.success')}
  }
}
</script>

<template>
  <FormWrapper
    :submit-handler="submitHandler"
    :submit-label="isEditing ? t('form.buttons.update') : t('form.buttons.create')"
    :val-schema="bookSchema"
    :initial-values="initialValues"
    @success="props.onSuccess"
  >
    <FormField name="title" :label="t('books.fields.title')"/>
    <FormField name="author" :label="t('books.fields.author')"/>
    <FormField name="isbn" :label="t('books.fields.isbn')"/>
    <FormField name="publicationDate" :label="t('books.fields.publicationDate')" type="date"/>
    <FormField name="genre" :label="t('books.fields.genre')"/>
    <FormField name="numberOfCopies" type="number" min="0"
               :label="t('books.fields.numberOfCopies')"/>
  </FormWrapper>
</template>
