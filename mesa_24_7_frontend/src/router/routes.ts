import { RouteRecordRaw } from 'vue-router';
import authRoutes from 'src/modules/auth/router/routes';
import comensalRoutes from 'src/modules/comensal/routes';
import mesaRoutes from 'src/modules/mesa/routes';
import reservaRoutes from 'src/modules/reserva/routes';
const routes: RouteRecordRaw[] = [
  {
    path: '/',
    name: 'home',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/IndexPage.vue') },
      ...comensalRoutes,
      ...mesaRoutes,
      ...reservaRoutes,
    ],
  },
  ...authRoutes,
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue'),
  },
];

export default routes;
