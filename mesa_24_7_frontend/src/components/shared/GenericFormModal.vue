<template>
  <div>
    <q-dialog v-model="isModalOpen" persistent>
      <q-card style="max-width: 40vw; width: 900px; height: auto">
        <q-card-section class="q-pa-none">
          <q-bar class="bg-primary text-white q-py-lg">
            <q-toolbar class="q-my-md">
              <q-toolbar-title>
                <div class="text-h5">{{ modalTitle }}</div>
              </q-toolbar-title>
            </q-toolbar>
          </q-bar>
        </q-card-section>
        <q-card-section class="q-pt-md">
          <DynamicFormInputs
            :fields="props.fields"
            :grid-class="gridClass"
            :input-item-class="inputItemClass"
            ref="dynamicInputsRef"
          />
        </q-card-section>
        <q-card-actions align="right">
          <q-btn flat label="Cancelar" color="red" @click="changeModalState" />
          <q-btn flat label="Guardar" color="primary" @click="submitData" />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>
<script setup lang="ts">
/****************************************************************************/
/*                             IMPORTS                                      */
/****************************************************************************/
import { ref, defineExpose } from 'vue';
import DynamicFormInputs from './DynamicFormInputs.vue';
import { DynamicInput } from 'src/types/components/props';

/****************************************************************************/
/*                             PROPS                                         */
/****************************************************************************/
const props = withDefaults(
  defineProps<{
    fields: DynamicInput[];
    modalTitle?: string;
    actionType?: string;
  }>(),
  {
    modalTitle: 'Sin título',
    actionType: '',
  }
);
const gridClass = 'row';
const inputItemClass = 'cols-6 col-md-6 col-sm-12 col-xs-12 q-px-md q-pt-md';
/****************************************************************************/
/*                             DATA                                         */
/****************************************************************************/
const emit = defineEmits<{
  (e: 'submitData', actionType: string): void;
}>();

const isModalOpen = ref(false);
const dynamicInputsRef = ref<any>(null);
/****************************************************************************/
/*                             METHODS                                      */
/****************************************************************************/
const resetFields = () => {
  props.fields.forEach((field) => {
    field.value = null;
  });
};
const changeModalState = () => {
  isModalOpen.value = !isModalOpen.value;
};
const submitData = async () => {
  try {
    const isValidForm = await dynamicInputsRef.value.formRef.validate();
    if (isValidForm) {
      emit('submitData', props.actionType);
      changeModalState();
    }
  } catch (error) {}
};
/****************************************************************************/
/*                             EXPOSE                                       */
/****************************************************************************/
defineExpose({ changeModalState, resetFields });
</script>
