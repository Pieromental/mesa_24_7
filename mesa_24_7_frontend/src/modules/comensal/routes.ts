import { RouteRecordRaw } from 'vue-router';

const comensalRoutes: RouteRecordRaw[] = [
  {
    path: '/comensal',
    component: () => import('./pages/ComensalPage.vue'),
  },
];

export default comensalRoutes;
