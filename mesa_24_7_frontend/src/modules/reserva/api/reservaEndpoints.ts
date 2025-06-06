import {
  HttpMethods,
  type IHttpResourceOption,
} from 'app/composable/fetch/useFetch';

type KeyResource =
  | 'getReservas'
  | 'deleteReserva'
  | 'saveReserva'
  | 'editReserva'
  | 'getReservaById'
  | 'getComensaleById'
  | 'getComensales'
  | 'getMesas';

const reservaEndpoints: Record<KeyResource, IHttpResourceOption> = {
  getReservas: <IHttpResourceOption>{
    path: '/reservas',
    method: HttpMethods.Get,
  },
  deleteReserva: <IHttpResourceOption>{
    path: '/reservas',
    method: HttpMethods.Delete,
  },
  saveReserva: <IHttpResourceOption>{
    path: '/reservas',
    method: HttpMethods.Post,
  },
  editReserva: <IHttpResourceOption>{
    path: '/reservas',
    method: HttpMethods.Put,
  },
  getReservaById: <IHttpResourceOption>{
    path: '/reservas',
    method: HttpMethods.Get,
  },
  getComensaleById: <IHttpResourceOption>{
    path: '/comensales',
    method: HttpMethods.Get,
  },
  getComensales: <IHttpResourceOption>{
    path: '/comensales',
    method: HttpMethods.Get,
  },
  getMesas: <IHttpResourceOption>{
    path: '/mesas',
    method: HttpMethods.Get,
  },
};

export { reservaEndpoints };
