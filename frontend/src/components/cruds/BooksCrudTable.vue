<script setup>
import {format} from 'date-fns'
import {useI18n} from 'vue-i18n'
import BookForm from '@/components/forms/books/BookForm.vue'
import CrudTable from "@/components/base/crud/CrudTable.vue";

const {t} = useI18n()

const bookColumns = [
  {title: t('books.fields.title'), key: 'title'},
  {title: t('books.fields.author'), key: 'author'},
  {title: t('books.fields.isbn'), key: 'isbn'},
  {
    title: t('books.fields.publicationDate'),
    key: 'publicationDate'
  },
  {title: t('books.fields.genre'), key: 'genre'},
  {title: t('books.fields.numberOfCopies'), key: 'numberOfCopies'}
]

const formatItem = (item) => ({
  ...item,
  publicationDate: item.publicationDate
    ? format(new Date(item.publicationDate), 'yyyy-MM-dd')
    : '-'
})
</script>

<template>
  <CrudTable
    resource="/books"
    :columns="bookColumns"
    :form-component="BookForm"
    :format-item="formatItem"
    :dialogs="dialogs"/>
</template>
