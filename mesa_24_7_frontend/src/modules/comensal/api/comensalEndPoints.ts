import {
  HttpMethods,
  type IHttpResourceOption,
} from 'app/composable/fetch/useFetch';

type KeyResource = 'getComensales' | 'deleteComensales';

const endPoints: Record<KeyResource, IHttpResourceOption> = {
  getComensales: <IHttpResourceOption>{
    path: '/comensales',
    method: HttpMethods.Get,
  },
  deleteComensales: <IHttpResourceOption>{
    path: '/comensales',
    method: HttpMethods.Delete,
  },
};

export { endPoints };
