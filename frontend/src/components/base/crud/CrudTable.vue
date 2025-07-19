<script setup>
import {ref, watch, onMounted, h} from 'vue'
import {debounce} from 'lodash'
import {useDialog, useMessage} from 'naive-ui'
import api from '@/services/api.js'
import TableToolbar from '@/components/base/table/TableToolbar.vue'
import TableRowActions from '@/components/base/table/TableRowActions.vue'
import {useI18n} from 'vue-i18n'

const {t} = useI18n()

const props = defineProps({
  resource: {type: String, required: true},
  columns: {type: Array, required: true},
  formComponent: {type: [Object, Function], required: true},
  itemKey: {type: String, default: 'id'},
  defaultItem: {type: Object, default: () => ({})},
  dialogs: {type: Object, default: () => ({})},
  formatItem: {type: Function, default: null},
  pageSizes: {type: Array, default: () => [10, 20, 50]},
})

const rDialogs = {
  create() {
    const title = props.dialogs?.create?.title
    return {
      title: title || t('crud.dialogs.create.title')
    }
  },
  edit(item) {
    const title = props.dialogs?.edit?.title
    return {
      title: typeof title === 'function' ? title(item) : (title || t('crud.dialogs.edit.title'))
    }
  },
  delete(item) {
    const title = props.dialogs?.delete?.title
    const content = props.dialogs?.delete?.content
    return {
      title: typeof title === 'function' ? title(item) : (title || t('crud.dialogs.delete.title')),
      content: typeof content === 'function' ? content(item) : (content || t('crud.dialogs.delete.content'))
    }
  }
}

const dialog = useDialog()
const message = useMessage()

const showForm = ref(false)
const selectedItem = ref(null)
const data = ref([])
const loading = ref(false)
const searchQuery = ref('')
const page = ref(1)
const pageSize = ref(10)
const total = ref(0)

const fetchData = async () => {
  loading.value = true
  try {
    const {data: res} = await api.get(props.resource, {
      params: {q: searchQuery.value, page: page.value, perPage: pageSize.value}
    })
    data.value = props.formatItem ? res.items.map(props.formatItem) : res.items
    total.value = res.meta.total
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

const deleteItem = (item) => {
  const {title, content} = rDialogs.delete(item)
  dialog.warning({
    title,
    content,
    positiveText: t('crud.dialogs.delete.buttonYes'),
    negativeText: t('crud.dialogs.delete.buttonNo'),
    positiveButtonProps: {type: 'error'},
    onPositiveClick: async () => {
      try {
        await api.delete(`${props.resource}/${item[props.itemKey]}`)
        message.success(t('crud.dialogs.delete.success'))
        fetchData()
      } catch (e) {
        console.error(e)
        message.error(t('crud.dialogs.delete.error'),)
      }
    }
  })
}

const openCreate = () => {
  selectedItem.value = null
  showForm.value = true
}

const openEdit = (item) => {
  selectedItem.value = item
  showForm.value = true
}

const onSuccess = () => {
  showForm.value = false
  fetchData()
}

const search = debounce((q) => {
  searchQuery.value = q
  page.value = 1
  fetchData()
}, 300)

watch([page, pageSize], fetchData)
onMounted(fetchData)
</script>

<template>
  <n-modal
    v-model:show="showForm"
    :title="selectedItem ? rDialogs.edit(selectedItem).title : rDialogs.create().title"
    preset="dialog"
  >
    <component
      :is="formComponent"
      :item="selectedItem"
      :onSuccess="onSuccess"
    />
  </n-modal>

  <TableToolbar
    @add="openCreate"
    @update:search="search"
  />

  <n-data-table
    class="table-container"
    :columns="[
      ...columns,
      {
        title: t('crud.columns.actions'),
        key: 'actions',
        render: (row) => h(TableRowActions, {
          onEdit: () => openEdit(row),
          onDelete: () => deleteItem(row)
        })
      }
    ]"
    :data="data"
    :loading="loading"
    :pagination="false"
  />

  <div class="pagination-wrapper">
    <n-pagination
      v-model:page="page"
      v-model:page-size="pageSize"
      :item-count="total"
      :page-sizes="pageSizes"
      show-size-picker
    />
  </div>
</template>

<style scoped lang="scss">
.table-container {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;

  table {
    min-width: 800px;
  }
}

.pagination-wrapper {
  display: flex;
  margin: 16px;
  width: 100%;
  justify-content: center;
}

</style>
