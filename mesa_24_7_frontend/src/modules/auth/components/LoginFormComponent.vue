<template>
  <div class="col-12 text-center">
    <div style="color: white !important" class="text-h6">Mesa 24/7</div>
  </div>
  <div class="col-12">
    <q-form>
      <q-input
        v-model="loginFields.email"
        type="text"
        label="Correo"
        class="q-mb-md"
        dense
      >
        <template v-slot:prepend>
          <q-icon name="mail" />
        </template>
      </q-input>
      <q-input
        v-model="loginFields.password"
        :type="canSeePassword ? 'text' : 'password'"
        label="Contraseña"
        class="q-mb-md"
        dense
      >
        <template v-slot:prepend>
          <q-icon name="password" />
        </template>
        <template v-slot:append>
          <q-icon
            v-if="loginFields.password.length > 0"
            name="visibility"
            @click="canSeePassword = !canSeePassword"
            class="cursor-pointer"
          />
        </template>
      </q-input>
      <q-btn icon="login" label="Iniciar Sesión" class="full-width login-btn" />
    </q-form>
  </div>
</template>
<script setup lang="ts">
import { ref } from 'vue';

const loginFields = ref({
  email: '',
  password: '',
});

const canSeePassword = ref(false);
</script>

<style lang="scss" scoped>
.login-btn {
  background: #5dc31f;
  color: white;
  border-radius: 10px;
}
</style>
