<template>
  <q-page class="row items-center justify-evenly">
    <DashboardComponent />
    
  </q-page>
</template>

<script setup lang="ts">

import DashboardComponent from 'components/DashboardComponent.vue';

defineOptions({
  name: 'IndexPage'
});


</script>
