<template>
  <div class="q-pa-lg">
    <div class="q-mb-md">
      <ListHeaderComponent v-bind="headerProps" />
    </div>
    <q-separator spaced />
    <div class="q-mt-md q-mb-md">
      <ListFilterComponent :filters="filters" @apply="filterListComensal" />
    </div>

    <ListItemComponent :items="comensalCardList" @delete="deleteComensal" />
  </div>
</template>
<script setup lang="ts">
/****************************************************************************/
/*                             IMPORTS                                      */
/****************************************************************************/
import { ListHeaderProps, DynamicFilter } from 'src/types/components/props';
import ListHeaderComponent from 'src/components/shared/ListHeaderComponent.vue';
import ListFilterComponent from 'src/components/shared/ListFilterComponent.vue';
import ListItemComponent from 'src/components/shared/ListItemComponent.vue';
import { rulesValidation } from 'app/composable/inputRules/useRules';
import { endPoints } from '../api/comensalEndPoints';
import { useFetchHttp } from 'app/composable/fetch/useFetch';
import { mapComensalToCardItem } from '../helper/comensalMapper';
import { GenericCardItem } from 'src/types/components/props';
import { ref, onMounted, computed } from 'vue';
import { useLoading } from 'app/composable/loading/useLoading';
import { useAlert } from 'app/composable/alert/useAlert';
/****************************************************************************/
/*                             COMPOSABLE                                    */
/****************************************************************************/
const { rules } = rulesValidation();
const { fetchHttpResource } = useFetchHttp();
const { showLoading, hideLoading } = useLoading();
const { showAlertFromResponse, confirmAlert } = useAlert();
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

const filters = ref<DynamicFilter[]>([
  { key: 'nombre', label: 'Nombre', type: 'text', value: null },
  {
    key: 'correo',
    label: 'Correo',
    type: 'text',
    rules: [rules.email],
    value: null,
  },
  { key: 'telefono', label: 'Teléfono', type: 'text', value: null },
  { key: 'direccion', label: 'Dirección', type: 'text', value: null },
]);

const comensalCardList = ref<GenericCardItem[]>([]);

/****************************************************************************/
/*                             COMPUTED                                      */
/****************************************************************************/
const filtrosActivos = computed<Record<string, string>>(() => {
  console.log('Saltamos X');
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

const filterListComensal = async () => {
  try {
    const resource = endPoints.getComensales;
    resource.params = {
      ...filtrosActivos.value,
    };
    const response = await fetchHttpResource(endPoints.getComensales);

    if (response.status) {
      comensalCardList.value = response.data.map(mapComensalToCardItem);
    } else {
      await showAlertFromResponse(response);
    }
  } catch (error) {
    console.error(error);
  }
};
const deleteComensal = async (id: string | number | undefined) => {
  try {
    const canContinue = await confirmAlert(
      { type: 'warning' },
      '¿Está seguro de eliminar el comensal?'
    );
    if (canContinue) {
      showLoading();
      const resource = endPoints.deleteComensales;
      resource.paramsRoute = [id];
      const response = await fetchHttpResource(resource);

      if (response.status) {
        filterListComensal();
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

/****************************************************************************/
/*                             LYFECICLE                                      */
/****************************************************************************/
onMounted(async () => {
  showLoading();
  await filterListComensal();
  hideLoading();
});
</script>
