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
import { reservaEndpoints } from '../api/reservaEndpoints';
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
  title: 'Reservas',
  subtitle: 'Controla la información de tus reservas',
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
    key: 'fecha',
    label: 'Fecha',
    type: 'date',
  },
  { key: 'hora', label: 'Hora', type: 'time' },
  {
    key: 'numero_de_personas',
    label: 'Comensales',
    type: 'text',
    rules: [rules.entero],
  },
];
/****************************************************************************/
/*                             METHODS                                      */
/****************************************************************************/

const filtrar = async (valores: Record<string, string>) => {
  try {
    const resource = reservaEndpoints.getReservas;
    resource.params = {
      ...valores,
    };
    const response = await fetchHttpResource(reservaEndpoints.getReservas);
  } catch (error) {
    console.error(error);
  }
  console.log('Valores filtrados:', valores);
};
</script>
