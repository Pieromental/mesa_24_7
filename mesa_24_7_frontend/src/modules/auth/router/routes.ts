import { RouteRecordRaw } from 'vue-router';

const authRoutes: RouteRecordRaw[] = [
  {
    path: '/login',
    component: () => import('../pages/LoginPage.vue'),
  },
];

export default authRoutes;
