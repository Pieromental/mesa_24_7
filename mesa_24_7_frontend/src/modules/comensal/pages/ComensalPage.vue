<template>
  <div class="q-pa-lg">
    <div class="q-mb-md">
      <ListHeaderComponent v-bind="headerProps" />
    </div>
    <q-separator spaced />
    <div class="q-mt-md q-mb-md">
      <ListFilterComponent :filters="filters" @apply="filterListComensal" />
    </div>
    <ListItemComponent
      :items="comensalCardList"
      @delete="deleteComensal"
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
import GenericFormModal from 'src/components/shared/GenericFormModal.vue';
import { rulesValidation } from 'app/composable/inputRules/useRules';
import { endPoints } from '../api/comensalEndPoints';
import { useFetchHttp } from 'app/composable/fetch/useFetch';
import { mapComensalToCardItem } from '../helper/comensalMapper';
import { createBaseComensalStructure } from '../helper/createComensalStructure';
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

const filters = ref<DynamicInput[]>(createBaseComensalStructure(rules));
const modalProps = ref({
  fields: createBaseComensalStructure(rules, true),
  modalTitle: '',
  actionType: '',
});

const comensalCardList = ref<GenericCardItem[]>([]);
const genericFormRef = ref();
const headerProps: ListHeaderProps = {
  title: 'Comensales',
  subtitle: 'Controla la información de tus clientes',
  optionsHeader: [
    {
      text: 'Agregar',
      color: 'primary',
      icon: 'add',
      method: () => {
        modalProps.value.actionType = 'save';
        modalProps.value.modalTitle = 'Registro de Comensal';
        genericFormRef.value?.changeModalState();
      },
    },
  ],
};
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

const filterListComensal = async () => {
  try {
    const resource = endPoints.getComensales;
    resource.params = {
      ...activeFilters.value,
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
const openEditModal = async (id: string | number | undefined) => {
  try {
    showLoading();

    const resource = endPoints.getComensaleById;
    resource.paramsRoute = [id];
    const response = await fetchHttpResource(resource);

    if (response.status) {
      const comensal = response.data;
      modalProps.value.actionType = 'edit';
      modalProps.value.modalTitle = 'Edición de Comensal';
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
const handleSubmit = async (type: string, formData: Record<string, any>) => {
  console.log(type);
  switch (type) {
    case 'save':
      saveComensal(formData);
      break;
    case 'edit':
      editComensal(formData);
      break;
    default:
      console.log('Not a method inplemented');
      break;
  }
};
const saveComensal = async (formData: Record<string, any>) => {
  try {
    const canContinue = await confirmAlert(
      { type: 'warning' },
      '¿Está seguro de registrar el comensal?'
    );
    if (canContinue) {
      showLoading();

      const resource = endPoints.saveComensal;
      resource.data = {
        ...formData,
      };
      const response = await fetchHttpResource(resource);
      genericFormRef.value?.resetFields();
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
const editComensal = async (formData: Record<string, any>) => {
  try {
    const canContinue = await confirmAlert(
      { type: 'warning' },
      '¿Está seguro de editar el comensal?'
    );
    if (canContinue) {
      showLoading();

      const resource = endPoints.editComensal;
      resource.paramsRoute = [formData.id];
      resource.data = {
        ...formData,
      };
      const response = await fetchHttpResource(resource);
      genericFormRef.value?.resetFields();
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
