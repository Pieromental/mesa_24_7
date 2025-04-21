import {
  HttpMethods,
  type IHttpResourceOption,
} from 'app/composable/fetch/useFetch';

type KeyResource = 'login';

const endPoints: Record<KeyResource, IHttpResourceOption> = {
  login: <IHttpResourceOption>{
    path: '/login',
    method: HttpMethods.Post,
  },
};

export { endPoints };
