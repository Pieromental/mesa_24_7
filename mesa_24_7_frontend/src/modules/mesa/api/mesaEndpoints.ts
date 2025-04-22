import {
  HttpMethods,
  type IHttpResourceOption,
} from 'app/composable/fetch/useFetch';

type KeyResource = 'getMesas';

const mesaEndpoints: Record<KeyResource, IHttpResourceOption> = {
  getMesas: <IHttpResourceOption>{
    path: '/mesas',
    method: HttpMethods.Get,
  },
};

export { mesaEndpoints };
