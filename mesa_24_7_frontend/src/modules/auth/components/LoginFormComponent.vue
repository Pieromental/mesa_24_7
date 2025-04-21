<template>
  <div class="col-12 text-center">
    <div style="color: white !important" class="text-h6">Mesa 24/7</div>
  </div>
  <div class="col-12">
    <q-form @submit="login" greedy ref="formRef" class="q-gutter-md">
      <q-input
        v-model="loginFields.email"
        type="text"
        label="Correo"
        class="q-mb-md"
        dense
        color="white"
        :rules="[rules.required, rules.email]"
      >
        <template v-slot:prepend>
          <q-icon color="white" name="mail" />
        </template>
      </q-input>
      <q-input
        v-model="loginFields.password"
        :type="canSeePassword ? 'text' : 'password'"
        :rules="[rules.required]"
        label="Contraseña"
        color="white"
        class="q-mb-md"
      >
        <template v-slot:prepend>
          <q-icon color="white" name="password" />
        </template>
        <template v-slot:append>
          <q-icon
            v-if="loginFields.password.length > 0"
            :name="canSeePassword ? 'visibility' : 'visibility_off'"
            @click="canSeePassword = !canSeePassword"
            class="cursor-pointer"
            color="white"
          />
        </template>
      </q-input>
      <q-btn
        :loading="isLoadingButton"
        icon="login"
        label="Iniciar Sesión"
        type="submit"
        class="full-width login-btn"
      >
        <template v-slot:loading>
          <q-spinner-hourglass class="on-left" />
          Ingresando
        </template>
      </q-btn>
    </q-form>
  </div>
</template>
<script setup lang="ts">
/****************************************************************************/
/*                             IMPORTS                                      */
/****************************************************************************/
import { ref, onMounted } from 'vue';
import { rulesValidation } from 'app/composable/inputRules/useRules';
import { endPoints } from '../api/AuthEndpoints';
import { useFetchHttp } from 'app/composable/fetch/useFetch';
import { useCrypto } from 'app/composable/crypto/useCrypto';
import { useRouter } from 'vue-router';
import { useAlert } from 'app/composable/alert/useAlert';
/****************************************************************************/
/*                             COMPOSABLE                                   */
/****************************************************************************/
const { rules } = rulesValidation();
const { fetchHttpResource } = useFetchHttp();
const { encryptAES, decryptAES } = useCrypto();
const router = useRouter();
const { singleAlert } = useAlert();
/****************************************************************************/
/*                             DATA                                         */
/****************************************************************************/
const loginFields = ref({
  email: '',
  password: '',
});
const canSeePassword = ref(false);
const formRef = ref<any>(null);
const isLoadingButton = ref(false);
/****************************************************************************/
/*                             METHODS                                       */
/****************************************************************************/
const checkUserSession = async () => {
  const encryptedToken = localStorage.getItem(import.meta.env.VITE_NAME_TOKEN);

  if (encryptedToken) {
    const token = decryptAES(encryptedToken);
    if (token) {
      router.push({ name: 'home' });
    }
  } else {
    localStorage.removeItem(import.meta.env.VITE_NAME_TOKEN);
  }
};
const saveUserSession = (token: string, userName: string) => {
  const encryptedToken: string = encryptAES(token) ?? '';
  const encryptedUsername: string = encryptAES(userName) ?? '';
  localStorage.setItem(import.meta.env.VITE_NAME_TOKEN, encryptedToken);
  localStorage.setItem(import.meta.env.VITE_NAME_USUARIO, encryptedUsername);
};
const login = async () => {
  try {
    const isValidForm = await formRef.value.validate();

    if (isValidForm) {
      endPoints.login.data = {
        ...loginFields.value,
      };
      isLoadingButton.value = true;
      const response = await fetchHttpResource(endPoints.login);
      isLoadingButton.value = false;
      if (response.status) {
        saveUserSession(response.data.token, response.data.usuario.name);
        router.push({ name: 'home' });
      } else {
        await singleAlert(
          {
            type: response.status
              ? 'success'
              : response.code == 300
              ? 'warning'
              : 'error',
          },
          response.title ?? '',
          response.message
        );
      }
    }
  } catch (error) {
    console.error(error);
  }
};

/****************************************************************************/
/*                             LYFECICLE                                     */
/****************************************************************************/
onMounted(() => {
  checkUserSession();
});
</script>

<style lang="scss" scoped>
.login-btn {
  background: #5dc31f;
  color: white;
  border-radius: 10px;
}
.login-input .q-field__native {
  color: white !important;
}
</style>
