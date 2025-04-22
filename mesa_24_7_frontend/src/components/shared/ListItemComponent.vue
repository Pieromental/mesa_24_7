<template>
  <div class="row q-col-gutter-md q-pa-md">
    <div
      class="col-xs-12 col-sm-6 col-md-4 col-lg-3"
      v-for="item in props.items"
      :key="item.id"
    >
      <CardItemComponent :item="item" />
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
</script>
