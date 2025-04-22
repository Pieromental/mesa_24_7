<template>
  <div class="q-pa-lg">
    <div class="q-mb-md">
      <ListHeaderComponent v-bind="headerProps" />
    </div>
    <q-separator spaced />
    <div class="q-mt-md">
      <ListFilterComponent :filters="filters" @apply="filtrar" />
    </div>
  </div>
</template>
<script setup lang="ts">
/****************************************************************************/
/*                             IMPORTS                                      */
/****************************************************************************/
import { ListHeaderProps, DynamicFilter } from 'src/types/components/props';
import ListHeaderComponent from 'src/components/shared/ListHeaderComponent.vue';
import ListFilterComponent from 'src/components/shared/ListFilterComponent.vue';
import { rulesValidation } from 'app/composable/inputRules/useRules';
import { mesaEndpoints } from '../api/mesaEndpoints';
import { useFetchHttp } from 'app/composable/fetch/useFetch';
/****************************************************************************/
/*                             COMPOSABLE                                    */
/****************************************************************************/
const { rules } = rulesValidation();
const { fetchHttpResource } = useFetchHttp();
/****************************************************************************/
/*                             DATA                                         */
/****************************************************************************/
const headerProps: ListHeaderProps = {
  title: 'Mesas',
  subtitle: 'Controla la información de tus mesas',
  optionsHeader: [
    {
      text: 'Agregar',
      color: 'primary',
      icon: 'add',
      method: () => {
        console.log('Agregando mesa...');
      },
    },
  ],
};

const filters: DynamicFilter[] = [
  {
    key: 'numero_mesa',
    label: 'Número de Mesa',
    type: 'text',
    rules: [rules.alfanumerico],
  },
  { key: 'capacidad', label: 'Capacidad', type: 'text', rules: [rules.entero] },
  { key: 'ubicacion', label: 'Ubicación', type: 'text' },
];
/****************************************************************************/
/*                             METHODS                                      */
/****************************************************************************/

const filtrar = async (valores: Record<string, string>) => {
  try {
    const resource = mesaEndpoints.getMesas;
    resource.params = {
      ...valores,
    };
    const response = await fetchHttpResource(mesaEndpoints.getMesas);
  } catch (error) {
    console.error(error);
  }
  console.log('Valores filtrados:', valores);
};
</script>
