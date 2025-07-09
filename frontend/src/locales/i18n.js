import {createI18n} from 'vue-i18n'
import auth from '@/locales/en/base/auth.js'
import crud from '@/locales/en/base/crud.js'
import form from '@/locales/en/base/form.js'
import home from '@/locales/en/home.js'
import books from '@/locales/en/books.js'

const messages = {
  en: {
    ...auth,
    ...form,
    ...crud,
    ...home,
    ...books,
  }
}

const i18n = createI18n({
  legacy: false,
  locale: 'en',
  fallbackLocale: 'en',
  messages
})

export default i18n
