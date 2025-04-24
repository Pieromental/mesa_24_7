<template>
  <div class="q-pa-lg">
    <div class="q-mb-md">
      <ListHeaderComponent v-bind="headerProps" />
    </div>
    <q-separator spaced />
    <div class="q-mt-md q-mb-md">
      <ListFilterComponent :filters="filters" @apply="filterReservas" />
    </div>
    <ListItemComponent :items="reservaCardList" @delete="deleteReserva" />
  </div>
</template>
<script setup lang="ts">
/****************************************************************************/
/*                             IMPORTS                                      */
/****************************************************************************/
import { ListHeaderProps, DynamicInput } from 'src/types/components/props';
import ListHeaderComponent from 'src/components/shared/ListHeaderComponent.vue';
import ListFilterComponent from 'src/components/shared/ListFilterComponent.vue';
import ListItemComponent from 'src/components/shared/ListItemComponent.vue';
import { rulesValidation } from 'app/composable/inputRules/useRules';
import { reservaEndpoints } from '../api/reservaEndpoints';
import { useFetchHttp } from 'app/composable/fetch/useFetch';
import { GenericCardItem } from 'src/types/components/props';
import { mapReservaToCardItem } from '../helpers/reservaMapper';
import { ref, onMounted ,computed} from 'vue';
import { useAlert } from 'app/composable/alert/useAlert';
import { useLoading } from 'app/composable/loading/useLoading';
/****************************************************************************/
/*                             COMPOSABLE                                    */
/****************************************************************************/
const { rules } = rulesValidation();
const { fetchHttpResource } = useFetchHttp();
const { showAlertFromResponse, confirmAlert } = useAlert();
const { showLoading, hideLoading } = useLoading();
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

const filters = ref<DynamicInput[]>([
  {
    key: 'fecha',
    label: 'Fecha',
    type: 'date',
    value: null,
  },
  { key: 'hora', label: 'Hora', type: 'time',  value:null },
  {
    key: 'numero_de_personas',
    label: 'Comensales',
    type: 'text',
    rules: [rules.entero],
    value: null,
  },
]);

const reservaCardList = ref<GenericCardItem[]>([]);
/****************************************************************************/
/*                             COMPUTED                                      */
/****************************************************************************/
const activeFilters = computed<Record<string, string>>(() => {
  const obj: Record<string, string> = {};
  filters.value.forEach((f) => {
    if (f.value) {
      obj[f.key] = f.value;
    }
  });
  return obj;
});

/****************************************************************************/
/*                             METHODS                                      */
/****************************************************************************/

const filterReservas = async () => {
  try {
    const resource = reservaEndpoints.getReservas;
    resource.params = {
      ...activeFilters.value,
    };
    const response = await fetchHttpResource(reservaEndpoints.getReservas);
    if (response.status) {
      reservaCardList.value = response.data.map(mapReservaToCardItem);
      console.log(reservaCardList.value);
    }else{
      await showAlertFromResponse(response);
    }
  } catch (error) {
    console.error(error);
  }
  console.log('Valores filtrados:', activeFilters.value);
};
const deleteReserva = async (id: string | number | undefined) => {
  try {
    const canContinue = await confirmAlert(
      { type: 'warning' },
      '¿Está seguro de eliminar la reserva?'
    );
    if (canContinue) {
      showLoading();
      const resource = reservaEndpoints.deleteReserva;
      resource.paramsRoute = [id];
      const response = await fetchHttpResource(resource);

      if (response.status) {
        filterReservas();
      } else {
        await showAlertFromResponse(response);
      }
    }
  } catch (error) {
    console.error(error);
  } finally {
    hideLoading();
  }
};
onMounted(async () => {
  showLoading();
  await filterReservas();
  hideLoading();
});
</script>
