<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar class="bg-yellow-9">
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />
        <q-avatar class="q-mx-sm" style="border-radius: 0%">
          <img src="../assets/images/svg/mesa24.svg" />
        </q-avatar>
        <q-toolbar-title> Mesa 24/7 </q-toolbar-title>

        <q-btn-dropdown flat round stretch color="white" icon="account_circle">
          <div class="row no-wrap q-pa-md">
            <div class="column items-center" style="width: 150px">
              <q-avatar size="72px">
                <q-icon name="logout" color="grey" size="72px" />
              </q-avatar>

              <div class="text-subtitle1 q-mt-xs q-mb-xs">{{ userName }}</div>
              <q-btn
                color="primary"
                label="Cerrar Sesion"
                push
                size="sm"
                @click="logOut"
                v-close-popup
              />
            </div>
          </div>
        </q-btn-dropdown>
      </q-toolbar>
    </q-header>

    <q-drawer v-model="leftDrawerOpen" show-if-above bordered>
      <q-list>
        <q-item-label header> Menú </q-item-label>

        <EssentialLink
          v-for="link in linksList"
          :key="link.title"
          v-bind="link"
        />
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import EssentialLink, {
  EssentialLinkProps,
} from 'components/EssentialLink.vue';
import { useCrypto } from 'app/composable/crypto/useCrypto';
import { useRouter } from 'vue-router';
import { useAlert } from 'app/composable/alert/useAlert';
const { decryptAES } = useCrypto();
const router = useRouter();
const { confirmAlert } = useAlert();
defineOptions({
  name: 'MainLayout',
});
const userName = ref('');
const linksList: EssentialLinkProps[] = [
  {
    title: 'Comensales',
    icon: 'groups',
    link: '/comensal',
  },
  {
    title: 'Mesas',
    icon: 'table_bar',
    link: '/mesa',
  },
  {
    title: 'Reservas',
    icon: 'local_dining',
    link: '/reserva',
  },
];

const leftDrawerOpen = ref(false);

function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value;
}

const checkSessionInformation = async (): Promise<void> => {
  const tokenKey = import.meta.env.VITE_NAME_TOKEN;
  const userKey = import.meta.env.VITE_NAME_USUARIO;

  const encryptedToken = localStorage.getItem(tokenKey);

  if (!encryptedToken) {
    router.push({ path: '/login' });
    return;
  }

  const encryptedUser = localStorage.getItem(userKey);
  const decryptedUser = decryptAES(encryptedUser ?? '');

  userName.value = decryptedUser ?? '';
};

const logOut = async () => {
  const canContinue = await confirmAlert(
    { type: 'warning' },
    '¿Está seguro de cerrar sesión?',
    'Pulse aceptar para continuar'
  );
  if (canContinue) {
    localStorage.clear();
    router.push({ path: '/login' });
  }
};
onMounted(() => {
  checkSessionInformation();
});
</script>
