<template>
  <div class="q-pa-lg">
    <div class="q-mb-md">
      <ListHeaderComponent v-bind="headerProps" />
    </div>
    <q-separator spaced />
    <div class="q-mt-md q-mb-md">
      <ListFilterComponent :filters="filters" @apply="filterReservas" />
    </div>
    <ListItemComponent
      :items="reservaCardList"
      @delete="deleteReserva"
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
import { reservaEndpoints } from '../api/reservaEndpoints';
import { useFetchHttp } from 'app/composable/fetch/useFetch';
import { GenericCardItem } from 'src/types/components/props';
import { mapReservaToCardItem } from '../helpers/reservaMapper';
import { ref, onMounted, computed } from 'vue';
import { useAlert } from 'app/composable/alert/useAlert';
import { useLoading } from 'app/composable/loading/useLoading';
import { createBaseReservaStructure } from '../helpers/createReservaStructure';
import { createBaseReservaFieldsStructure } from '../helpers/createBaseReservaFieldsStructure';
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
      method: async () => {
        modalProps.value.actionType = 'save';
        modalProps.value.modalTitle = 'Registro de Reserva';
        await prepareModalProps();
        genericFormRef.value?.changeModalState();
      },
    },
  ],
};

const filters = ref<DynamicInput[]>(createBaseReservaStructure(rules));
const modalProps = ref({
  fields: createBaseReservaFieldsStructure(rules, true),
  modalTitle: '',
  actionType: '',
});
const reservaCardList = ref<GenericCardItem[]>([]);
const genericFormRef = ref();
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
  switch (type) {
    case 'save':
      saveReserva(formData);
      break;
    case 'edit':
      editReserva(formData);
      break;
    default:
      console.log('Not a method inplemented');
      break;
  }
};
const openEditModal = async (id: string | number | undefined) => {
  try {
    showLoading();

    const resource = reservaEndpoints.getReservaById;
    resource.paramsRoute = [id];
    const response = await fetchHttpResource(resource);

    if (response.status) {
      const reserva = response.data;
      modalProps.value.actionType = 'edit';
      modalProps.value.modalTitle = 'Edición de Reserva';
      await prepareModalProps();
      modalProps.value.actionType;
      modalProps.value.fields.forEach((field) => {
        field.value = reserva[field.key] ?? ' ';
      });

      genericFormRef.value?.changeModalState();
    }
  } catch (error) {
    console.error(error);
  } finally {
    hideLoading();
  }
};
const saveReserva = async (formData: Record<string, any>) => {
  try {
    const canContinue = await confirmAlert(
      { type: 'warning' },
      '¿Está seguro de registrar la reserva?'
    );
    if (canContinue) {
      showLoading();

      const resource = reservaEndpoints.saveReserva;
      resource.data = {
        ...formData,
      };
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
const editReserva = async (formData: Record<string, any>) => {
  try {
    const canContinue = await confirmAlert(
      { type: 'warning' },
      '¿Está seguro de editar la reserva?'
    );
    if (canContinue) {
      showLoading();

      const resource = reservaEndpoints.editReserva;
      resource.paramsRoute = [formData.id];
      resource.data = {
        ...formData,
      };
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
const filterReservas = async () => {
  try {
    const resource = reservaEndpoints.getReservas;
    resource.params = {
      ...activeFilters.value,
    };
    const response = await fetchHttpResource(reservaEndpoints.getReservas);
    if (response.status) {
      reservaCardList.value = response.data.map(mapReservaToCardItem);
    } else {
      await showAlertFromResponse(response);
    }
  } catch (error) {
    console.error(error);
  }
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
const getListComensal = async () => {
  try {
    const resource = reservaEndpoints.getComensales;
    const response = await fetchHttpResource(resource);

    if (response.status) {
      return response.data.map((e: any) => ({
        value: e.id,
        label: e.nombre,
      }));
    } else {
      return [];
    }
  } catch (error) {
    console.error('Error obteniendo lista de comensales:', error);
    return [];
  }
};
const getListMesa = async () => {
  try {
    const resource = reservaEndpoints.getMesas;
    const response = await fetchHttpResource(resource);

    if (response.status) {
      return response.data.map((e: any) => ({
        value: e.id,
        label: e.numero_mesa,
      }));
    } else {
      return [];
    }
  } catch (error) {
    console.error('Error obteniendo lista de comensales:', error);
    return [];
  }
};
const prepareModalProps = async () => {
  const comensales = await getListComensal();
  const mesas = await getListMesa();
  const comensalField = modalProps.value.fields.find(
    (f) => f.key === 'comensal_id'
  );
  if (comensalField) {
    comensalField.options = comensales;
  }
  const mesaField = modalProps.value.fields.find((f) => f.key === 'mesa_id');
  if (mesaField) {
    mesaField.options = mesas;
  }
};
/****************************************************************************/
/*                             LYFECICLE                                     */
/****************************************************************************/
onMounted(async () => {
  showLoading();
  await filterReservas();
  hideLoading();
});
</script>
