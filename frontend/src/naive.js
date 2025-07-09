import {
  create,
  NDataTable,
  NModal,
  NPagination,
  NMessageProvider,
  NDialogProvider
} from 'naive-ui'

export function createNaiveUI() {
  return create({
    components: [
      // NButton,
      // NInput,
      // NForm,
      // NFormItem,
      NDataTable,
      NModal,
      // NDatePicker,
      // NSelect,
      NPagination,
      NMessageProvider,
      // NLoadingBarProvider,
      NDialogProvider
    ]
  })
}
