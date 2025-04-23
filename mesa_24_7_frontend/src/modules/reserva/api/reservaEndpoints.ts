import {
  HttpMethods,
  type IHttpResourceOption,
} from 'app/composable/fetch/useFetch';

type KeyResource = 'getReservas' | 'deleteReserva';

const reservaEndpoints: Record<KeyResource, IHttpResourceOption> = {
  getReservas: <IHttpResourceOption>{
    path: '/reservas',
    method: HttpMethods.Get,
  },
  deleteReserva: <IHttpResourceOption>{
    path: '/reservas',
    method: HttpMethods.Delete,
  },
};

export { reservaEndpoints };
