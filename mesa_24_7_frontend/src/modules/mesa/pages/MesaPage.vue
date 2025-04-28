<template>
  <div class="q-pa-lg">
    <div class="q-mb-md">
      <ListHeaderComponent v-bind="headerProps" />
    </div>
    <q-separator spaced />
    <div class="q-mt-md q-mb-md">
      <ListFilterComponent :filters="filters" @apply="filterMesa" />
    </div>

    <ListItemComponent
      :items="mesaCardList"
      @delete="deleteTable"
      @edit="openEditModal"
    />
    <GenericFormModal
      ref="genericFormRef"
      v-bind="modalProps"
      @submit-data="handleSubmit"
    />
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
import { ref, onMounted, computed } from 'vue';
import { useAlert } from 'app/composable/alert/useAlert';
import { useLoading } from 'app/composable/loading/useLoading';
import { createBaseMesaStructure } from '../helpers/createMesaStructure';
import GenericFormModal from 'src/components/shared/GenericFormModal.vue';
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
const filters = ref<DynamicInput[]>(createBaseMesaStructure(rules));
const genericFormRef = ref();
const modalProps = ref({
  fields: createBaseMesaStructure(rules, true),
  modalTitle: '',
  actionType: '',
});

const headerProps: ListHeaderProps = {
  title: 'Mesas',
  subtitle: 'Controla la información de tus mesas',
  optionsHeader: [
    {
      text: 'Agregar',
      color: 'primary',
      icon: 'add',
      method: () => {
        modalProps.value.actionType = 'save';
        modalProps.value.modalTitle = 'Registro de Mesa';
        genericFormRef.value?.changeModalState();
      },
    },
  ],
};

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
const handleSubmit = async (type: string, formData: Record<string, any>) => {
  console.log(type);
  switch (type) {
    case 'save':
      saveMesa(formData);
      break;
    case 'edit':
      editMesa(formData);
      break;
    default:
      console.log('Not a method inplemented');
      break;
  }
};
const saveMesa = async (formData: Record<string, any>) => {
  try {
    const canContinue = await confirmAlert(
      { type: 'warning' },
      '¿Está seguro de registrar la mesa?'
    );
    if (canContinue) {
      showLoading();

      const resource = mesaEndpoints.saveMesa;
      resource.data = {
        ...formData,
      };
      const response = await fetchHttpResource(resource);
      genericFormRef.value?.resetFields();
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
const editMesa = async (formData: Record<string, any>) => {
  try {
    const canContinue = await confirmAlert(
      { type: 'warning' },
      '¿Está seguro de editar la mesa?'
    );
    if (canContinue) {
      showLoading();

      const resource = mesaEndpoints.editMesa;
      resource.paramsRoute = [formData.id];
      resource.data = {
        ...formData,
      };
      const response = await fetchHttpResource(resource);
      genericFormRef.value?.resetFields();
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
const openEditModal = async (id: string | number | undefined) => {
  try {
    showLoading();

    const resource = mesaEndpoints.getMesaById;
    resource.paramsRoute = [id];
    const response = await fetchHttpResource(resource);

    if (response.status) {
      const comensal = response.data;
      modalProps.value.actionType = 'edit';
      modalProps.value.modalTitle = 'Edición de Mesa';
      modalProps.value.fields.forEach((field) => {
        field.value = comensal[field.key] ?? ' ';
      });
      genericFormRef.value?.changeModalState();
    }
  } catch (error) {
    console.error(error);
  } finally {
    hideLoading();
  }
};
const filterMesa = async () => {
  try {
    const resource = mesaEndpoints.getMesas;
    resource.params = {
      ...activeFilters.value,
    };
    const response = await fetchHttpResource(mesaEndpoints.getMesas);
    if (response.status) {
      mesaCardList.value = response.data.map(mapMesaToCardItem);
    } else {
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
