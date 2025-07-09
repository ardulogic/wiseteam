<!-- TableRowActions.vue -->
<script setup>
defineProps({
  onEdit: Function,
  onDelete: Function
})

import { Pencil, Trash2 } from 'lucide-vue-next'
import TableButton from './TableButton.vue'
</script>

<template>
  <div class="table-row-actions">
    <TableButton :icon="Pencil" @click="onEdit" />
    <TableButton :icon="Trash2" @click="onDelete" />
  </div>
</template>

<style scoped>
.table-row-actions {
  display: flex;
  gap: 0.5rem;
  align-items: center;
}
</style>
