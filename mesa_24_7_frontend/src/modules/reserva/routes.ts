import { RouteRecordRaw } from 'vue-router';

const reservaRoutes: RouteRecordRaw[] = [
  {
    path: '/reserva',
    component: () => import('./pages/ReservaPage.vue'),
  },
];

export default reservaRoutes;
