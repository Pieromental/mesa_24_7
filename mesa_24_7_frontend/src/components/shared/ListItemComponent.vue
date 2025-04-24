<template>
  <div class="row q-col-gutter-md q-pa-md">
    <div
      class="col-xs-12 col-sm-6 col-md-4 col-lg-3"
      v-for="item in props.items"
      :key="item.id"
    >
      <CardItemComponent :item="item" @delete="onDelete" @edit="onEdit" />
    </div>
  </div>
</template>

<script setup lang="ts">
/****************************************************************************/
/*                             IMPORTS                                      */
/****************************************************************************/
import CardItemComponent from './CardItemComponent.vue';
import { GenericCardItem } from 'src/types/components/props';
/****************************************************************************/
/*                             PROPS                                        */
/****************************************************************************/
const props = defineProps<{
  items: GenericCardItem[];
}>();
/****************************************************************************/
/*                             EMITS                                        */
/****************************************************************************/
const emit = defineEmits<{
  (e: 'delete', id: string | number | undefined): void;
  (e: 'edit', id: string | number | undefined): void;
}>();

function onDelete(id: string | number | undefined) {
  emit('delete', id);
}
function onEdit(id: string | number | undefined) {
  emit('edit', id);
}
</script>
