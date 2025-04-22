import {
  HttpMethods,
  type IHttpResourceOption,
} from 'app/composable/fetch/useFetch';

type KeyResource = 'getReservas';

const reservaEndpoints: Record<KeyResource, IHttpResourceOption> = {
  getReservas: <IHttpResourceOption>{
    path: '/reservas',
    method: HttpMethods.Get,
  },
};

export { reservaEndpoints };
