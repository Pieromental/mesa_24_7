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
      <DynamicFormInputs
        v-show="mostrar"
        :fields="props.filters"
        ref="dynamicInputsRef"
      />
    </q-slide-transition>
  </div>
</template>

<script setup lang="ts">
/****************************************************************************/
/*                             IMPORTS                                      */
/****************************************************************************/
import { ref, computed } from 'vue';
import { DynamicInput } from 'src/types/components/props';
import DynamicFormInputs from './DynamicFormInputs.vue';
/****************************************************************************/
/*                             PROPS                                         */
/****************************************************************************/
const props = defineProps<{
  filters: DynamicInput[];
}>();
/****************************************************************************/
/*                             DATA                                         */
/****************************************************************************/
const emit = defineEmits<{
  (e: 'apply'): void;
}>();
const dynamicInputsRef = ref<any>(null);
const mostrar = ref(false);

/****************************************************************************/
/*                             COMPUTED                                      */
/****************************************************************************/
const visibleChips = computed(() =>
  props.filters.filter((f) => !!f.value).map((f) => [f.key, f.value])
);

const aplicarFiltros = async () => {
  try {
    const isValidForm = await dynamicInputsRef.value.formRef.validate();
    if (isValidForm) {
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
  const filtro = props.filters.find((f) => f.key === key);
  if (filtro) filtro.value = '';
  emit('apply');
};

const obtenerEtiqueta = (key: string): string => {
  const found = props.filters.find((f) => f.key === key);
  return found?.label || key;
};
</script>
