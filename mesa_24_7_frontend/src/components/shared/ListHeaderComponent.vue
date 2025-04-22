<template>
  <div class="row justify-between">
    <div class="col-6 column q-gutter-xs ">
      <span class="text-h4 text-bold">{{ props.title }}</span>
      <span class="text-subtitle2 text-grey">{{ props.subtitle }}</span>
    </div>

    <div class="col-6 q-mt-lg flex justify-end items-start q-pr-lg q-gutter-sm">
      <q-btn
        v-for="(option, index) in props.optionsHeader"
        :key="index"
        :label="option.text"
        :color="option.color"
        :icon="option.icon"
        @click="option.method"
        unelevated
        no-caps
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ListHeaderProps } from 'src/types/components/props';

const props = defineProps<ListHeaderProps>();
</script>
