<template>
  <q-card flat bordered class="q-pa-md" square>
    <q-item>
      <q-item-section avatar v-if="item.avatarVisible">
        <q-avatar>
          <img :src="item.avatarUrl" />
        </q-avatar>
      </q-item-section>
      <q-item-section>
        <q-item-label class="text-h6 truncate-text">{{ item.title }}</q-item-label>
        <q-item-label caption>{{ item.subtitle }}</q-item-label>
      </q-item-section>
    </q-item>

    <q-separator />

    <q-card-section>
      <q-list>
        <q-item v-for="(info, index) in item.details" :key="index">
          <q-item-section avatar>
            <q-icon :name="info.icon" color="primary" />
          </q-item-section>
          <q-item-section style="max-width: 100%">
            <div class="text-caption text-weight-bold">{{ info.label }}</div>
            <div class="text-caption truncate-text" :title="info.value">{{ info.value }}</div>
          </q-item-section>
        </q-item>
      </q-list>
    </q-card-section>

    <q-separator />

    <q-card-actions align="center">
      <q-btn flat round color="negative" icon="delete" @click="onDelete" />
      <q-btn flat round color="primary" icon="edit" @click="onEdit" />
    </q-card-actions>
  </q-card>
</template>

<script setup lang="ts">
import { GenericCardItem } from 'src/types/components/props';

const props = defineProps<{
  item: GenericCardItem;
}>();

function onDelete() {
  console.log('Eliminar clicado', props.item.id);
}

function onEdit() {
  console.log('Editar clicado', props.item.id);
}
</script>
<style scoped>
.truncate-text {
  display: block;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 100%;
}
</style>
