import {
  HttpMethods,
  type IHttpResourceOption,
} from 'app/composable/fetch/useFetch';

type KeyResource =
  | 'getMesas'
  | 'deleteTable'
  | 'saveMesa'
  | 'editMesa'
  | 'getMesaById';

const mesaEndpoints: Record<KeyResource, IHttpResourceOption> = {
  getMesas: <IHttpResourceOption>{
    path: '/mesas',
    method: HttpMethods.Get,
  },
  deleteTable: <IHttpResourceOption>{
    path: '/mesas',
    method: HttpMethods.Delete,
  },
  saveMesa: <IHttpResourceOption>{
    path: '/mesas',
    method: HttpMethods.Post,
  },
  editMesa: <IHttpResourceOption>{
    path: '/mesas',
    method: HttpMethods.Put,
  },
  getMesaById: <IHttpResourceOption>{
    path: '/mesas',
    method: HttpMethods.Get,
  },
};

export { mesaEndpoints };
