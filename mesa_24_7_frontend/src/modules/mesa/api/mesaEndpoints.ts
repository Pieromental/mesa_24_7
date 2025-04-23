import {
  HttpMethods,
  type IHttpResourceOption,
} from 'app/composable/fetch/useFetch';

type KeyResource = 'getMesas' | 'deleteTable';

const mesaEndpoints: Record<KeyResource, IHttpResourceOption> = {
  getMesas: <IHttpResourceOption>{
    path: '/mesas',
    method: HttpMethods.Get,
  },
  deleteTable: <IHttpResourceOption>{
    path: '/mesas',
    method: HttpMethods.Delete,
  },
};

export { mesaEndpoints };
