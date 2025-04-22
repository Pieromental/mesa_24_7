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
import { endPoints } from '../api/comensalEndPoints';
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
  title: 'Comensales',
  subtitle: 'Controla la información de tus clientes',
  optionsHeader: [
    {
      text: 'Agregar',
      color: 'primary',
      icon: 'add',
      method: () => {
        console.log('Agregando comensal...');
      },
    },
  ],
};

const filters: DynamicFilter[] = [
  { key: 'nombre', label: 'Nombre', type: 'text' },
  { key: 'correo', label: 'Correo', type: 'text', rules: [rules.email] },
  { key: 'telefono', label: 'Teléfono', type: 'text' },
  { key: 'direccion', label: 'Dirección', type: 'text' },
];
/****************************************************************************/
/*                             METHODS                                      */
/****************************************************************************/

const filtrar = async (valores: Record<string, string>) => {
  try {
    const resource = endPoints.getComensales;
    resource.params = {
      ...valores,
    };
    const response = await fetchHttpResource(endPoints.getComensales);
  } catch (error) {
    console.error(error);
  }
  console.log('Valores filtrados:', valores);
};
</script>
