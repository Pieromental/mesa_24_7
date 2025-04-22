import {
  HttpMethods,
  type IHttpResourceOption,
} from 'app/composable/fetch/useFetch';

type KeyResource = 'getComensales';

const endPoints: Record<KeyResource, IHttpResourceOption> = {
  getComensales: <IHttpResourceOption>{
    path: '/comensales',
    method: HttpMethods.Get,
  },
};

export { endPoints };
