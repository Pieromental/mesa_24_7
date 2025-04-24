import {
  HttpMethods,
  type IHttpResourceOption,
} from 'app/composable/fetch/useFetch';

type KeyResource =
  | 'getComensales'
  | 'deleteComensales'
  | 'saveComensal'
  | 'getComensaleById'
  | 'editComensal';

const endPoints: Record<KeyResource, IHttpResourceOption> = {
  getComensales: <IHttpResourceOption>{
    path: '/comensales',
    method: HttpMethods.Get,
  },
  deleteComensales: <IHttpResourceOption>{
    path: '/comensales',
    method: HttpMethods.Delete,
  },
  saveComensal: <IHttpResourceOption>{
    path: '/comensales',
    method: HttpMethods.Post,
  },
  editComensal: <IHttpResourceOption>{
    path: '/comensales',
    method: HttpMethods.Put,
  },
  getComensaleById: <IHttpResourceOption>{
    path: '/comensales',
    method: HttpMethods.Get,
  },
};

export { endPoints };
