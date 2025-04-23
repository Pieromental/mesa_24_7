<template>
  <div>
    <div class="row items-center q-gutter-sm">
      <q-btn
        unelevated
        no-caps
        icon="search"
        label="Buscar"
        color="primary"
        @click="aplicarFiltros"
      />
      <q-btn
        outline
        color="primary"
        rounded
        icon="filter_alt"
        label="Filtros"
        @click="mostrar = !mostrar"
      />

      <div v-show="!mostrar">
        <div class="row q-gutter-sm">
          <q-chip
            v-for="[key, val] in visibleChips"
            :key="key"
            color="secondary"
            text-color="white"
            removable
            @remove="quitarFiltro(key)"
          >
            {{ obtenerEtiqueta(key) }}: {{ val }}
          </q-chip>
        </div>
      </div>
    </div>

    <q-slide-transition>
      <q-form
        ref="formRef"
        @submit.prevent="aplicarFiltros"
        v-show="mostrar"
        class="q-pa-md"
      >
        <div class="row q-gutter-md wrap">
          <div
            v-for="filter in filters"
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
                        <q-btn
                          v-close-popup
                          label="Cerrar"
                          color="primary"
                          flat
                        />
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
                        <q-btn
                          v-close-popup
                          label="Close"
                          color="primary"
                          flat
                        />
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
    </q-slide-transition>
  </div>
</template>

<script setup lang="ts">
/****************************************************************************/
/*                             IMPORTS                                      */
/****************************************************************************/
import { ref, reactive, computed, watch } from 'vue';
import { DynamicFilter } from 'src/types/components/props';

/****************************************************************************/
/*                             PROPS                                         */
/****************************************************************************/
const props = defineProps<{
  filters: DynamicFilter[];
}>();
/****************************************************************************/
/*                             DATA                                         */
/****************************************************************************/
const emit = defineEmits<{
  (e: 'apply'): void;
}>();
const formRef = ref<any>(null);
const mostrar = ref(false);
const values = reactive({} as Record<string, string>);
const filters = ref(props.filters);
/****************************************************************************/
/*                             COMPUTED                                      */
/****************************************************************************/
const visibleChips = computed(() =>
  filters.value.filter((f) => !!f.value).map((f) => [f.key, f.value])
);

const aplicarFiltros = async () => {
  try {
    const isValidForm = await formRef.value.validate();
    if (isValidForm) {
      const result: Record<string, string> = {};
      filters.value.forEach((f) => {
        if (f.value) result[f.key] = f.value;
      });
      emit('apply');
      mostrar.value = false;
    }
  } catch (error) {
    console.error(error);
  }
};
/****************************************************************************/
/*                             METHODS                                      */
/****************************************************************************/
const quitarFiltro = (key: string) => {
  const filtro = filters.value.find((f) => f.key === key);
  if (filtro) filtro.value = '';
  emit('apply');
};

const obtenerEtiqueta = (key: string): string => {
  const found = props.filters.find((f) => f.key === key);
  return found?.label || key;
};
/****************************************************************************/
/*                             WATCHERS                                     */
/****************************************************************************/

watch(
  () => props.filters,
  (v: any) => {
    filters.value = v;
  },
  { deep: true }
);
</script>
