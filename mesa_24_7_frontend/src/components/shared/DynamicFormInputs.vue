<template>
  <q-form ref="formRef" class="q-pa-md">
    <div class="row q-gutter-md wrap">
      <div
        v-for="filter in props.filters"
        :key="filter.key"
        class="col-auto"
        style="min-width: 200px"
      >
        <q-input
          v-if="filter.type === 'text'"
          v-model="filter.value"
          :label="filter.label"
          filled
          dense
          :rules="filter.rules"
        />
        <q-input
          v-else-if="filter.type === 'date'"
          filled
          v-model="filter.value"
          readonly
          dense
          :label="filter.label"
        >
          <template v-slot:append>
            <q-icon name="event" class="cursor-pointer">
              <q-popup-proxy
                cover
                transition-show="scale"
                transition-hide="scale"
              >
                <q-date v-model="filter.value" mask="YYYY-MM-DD">
                  <div class="row items-center justify-end">
                    <q-btn v-close-popup label="Cerrar" color="primary" flat />
                  </div>
                </q-date>
              </q-popup-proxy>
            </q-icon>
          </template>
          <template v-slot:prepend v-if="filter.value ?? ''">
            <q-icon
              size="xs"
              name="close"
              class="cursor-pointer"
              @click="filter.value = ''"
            />
          </template>
        </q-input>
        <q-input
          v-else-if="filter.type === 'time'"
          filled
          dense
          readonly
          v-model="filter.value"
          :label="filter.label"
        >
          <template v-slot:prepend v-if="filter.value ?? ''">
            <q-icon
              size="xs"
              name="close"
              class="cursor-pointer"
              @click="filter.value = ''"
            />
          </template>
          <template v-slot:append>
            <q-icon name="access_time" class="cursor-pointer">
              <q-popup-proxy
                cover
                transition-show="scale"
                transition-hide="scale"
              >
                <q-time v-model="filter.value">
                  <div class="row items-center justify-end">
                    <q-btn v-close-popup label="Close" color="primary" flat />
                  </div>
                </q-time>
              </q-popup-proxy>
            </q-icon>
          </template>
        </q-input>

        <q-select
          v-else-if="filter.type === 'select'"
          v-model="filter.value"
          :label="filter.label"
          :options="filter.options"
          filled
          dense
          :rules="filter.rules"
        />
      </div>
    </div>
  </q-form>
</template>
<script setup lang="ts">
import { ref } from 'vue';
import { DynamicFilter } from 'src/types/components/props';

const props = defineProps<{
  filters: DynamicFilter[];
}>();
const formRef = ref<any>(null);
defineExpose({ formRef });

</script>
