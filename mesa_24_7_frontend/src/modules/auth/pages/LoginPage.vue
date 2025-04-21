<template>
  <q-layout view="hHh Lpr lff" class="row items-center">
    <!-- Left Panel -->
    <div
      class="col-12 col-md-8 col-lg-8 col-xl-8 login-background-img gt-sm"
      style="height: 100vh"
    ></div>
    <!-- Right Panel -->
    <div
      class="col-12 col-md-4 col-lg-4 col-xl-4 login-background-color flex flex-center"
      style="height: 100vh"
    >
      <div class="row q-pa-xl">
        <LoginLogoComponent />
        <LoginFormComponent />
      </div>
    </div>
  </q-layout>
</template>
<script setup lang="ts">
import LoginLogoComponent from '../components/LoginLogoComponent.vue';
import LoginFormComponent from '../components/LoginFormComponent.vue';
</script>

<style lang="scss" scoped>
.login-background-img {
  background: url('src/assets/images/png/back_login.jpg') no-repeat;
  background-size: cover;
}
.login-background-color {
    background: #FFC95E;
    background: linear-gradient(21deg,rgba(255, 201, 94, 1) 0%, rgba(251, 139, 10, 1) 27%, rgba(255, 52, 1, 1) 63%);
}
</style>
