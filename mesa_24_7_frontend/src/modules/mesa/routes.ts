import { RouteRecordRaw } from 'vue-router';

const mesaRoutes: RouteRecordRaw[] = [
  {
    path: '/mesa',
    component: () => import('./pages/MesaPage.vue'),
  },
];

export default mesaRoutes;
