<template>
  <div class="q-pa-lg">
    <div class="q-mb-md">
      <ListHeaderComponent v-bind="headerProps" />
    </div>
    <q-separator spaced />
    <div class="q-mt-md q-mb-md">
      <ListFilterComponent :filters="filters" @apply="filterMesa" />
    </div>

    <ListItemComponent :items="mesaCardList"  @delete="deleteTable"/>
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
import { mesaEndpoints } from '../api/mesaEndpoints';
import { useFetchHttp } from 'app/composable/fetch/useFetch';
import { mapMesaToCardItem } from '../helpers/mesaMapper';
import { GenericCardItem } from 'src/types/components/props';
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

const filters = ref<DynamicInput[]>([
  {
    key: 'numero_mesa',
    label: 'Número de Mesa',
    type: 'text',
    rules: [rules.alfanumerico],
    value: null,
  },
  {
    key: 'capacidad',
    label: 'Capacidad',
    type: 'text',
    rules: [rules.entero],
    value: null,
  },
  { key: 'ubicacion', label: 'Ubicación', type: 'text', value: null },
]);
const mesaCardList = ref<GenericCardItem[]>([]);
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

const filterMesa = async () => {
  try {
    const resource = mesaEndpoints.getMesas;
    resource.params = {
      ...activeFilters.value,
    };
    const response = await fetchHttpResource(mesaEndpoints.getMesas);
    if (response.status) {
      mesaCardList.value = response.data.map(mapMesaToCardItem);
    }else{
      await showAlertFromResponse(response);
    }
  } catch (error) {
    console.error(error);
  }

};
const deleteTable = async (id: string | number | undefined) => {
  try {
    const canContinue = await confirmAlert(
      { type: 'warning' },
      '¿Está seguro de eliminar la mesa?'
    );
    if (canContinue) {
      showLoading();
      const resource = mesaEndpoints.deleteTable;
      resource.paramsRoute = [id];
      const response = await fetchHttpResource(resource);

      if (response.status) {
        filterMesa();
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
  await filterMesa();
  hideLoading();
});
</script>
