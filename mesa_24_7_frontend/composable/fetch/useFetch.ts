import axios from 'axios';
import type { AxiosRequestConfig, AxiosResponse } from 'axios';
import { AxiosError } from 'axios';
import { useCrypto } from '../crypto/useCrypto';
import { useRouter } from 'vue-router';

const { decryptAES } = useCrypto();

export enum HttpMethods {
  Get = 'get',
  Post = 'post',
  Put = 'put',
  Patch = 'patch',
  Delete = 'delete',
}

export interface IHttpResourceOption {
  baseUrl?: string;
  path: string;
  method: HttpMethods;
  headers?: Record<string, unknown>;
  header?: Record<string, unknown>;
  params?: Record<string, unknown>;
  paramsRoute?: Array<unknown>;
  data?: Record<string, unknown> | FormData;
  timeout?: number;
  auth?: Record<string, unknown>;
  responseType?: string;
  token?: string;
  nameToken?: string;
  download?: boolean;
  nameDocument?: string;
}

export interface IHttpResponse {
  responseCode?: string;
  code?: number;
  responseAction?: string;
  status: boolean;
  data: unknown[];
  title?: string;
  message: string;
  otherMessage: string;
  otherData: unknown[];
}

export function useFetchHttp() {
  /****************************************************************************/
  /*                             VARIABLES                                    */
  /****************************************************************************/

  const router = useRouter();

  /****************************************************************************/
  /*                             METHODS                                      */
  /****************************************************************************/
  const getStoredToken = (explicitToken?: string | null): string | null => {
    if (explicitToken) return explicitToken;

    const rawToken =
      sessionStorage.getItem(import.meta.env.VITE_NAME_TOKEN) ??
      localStorage.getItem(import.meta.env.VITE_NAME_TOKEN);

    return rawToken ? decryptAES(rawToken) : null;
  };
  const setRequestHeaders = (
    storedToken: string | null,
    customHeaders?: Record<string, unknown>
  ): Record<string, unknown> => {
    const token = getStoredToken(storedToken ?? null);

    const headers: Record<string, unknown> = {
      ...(customHeaders ?? {}),
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
    };

    if (!headers['Content-Type']) {
      headers['Content-Type'] = 'application/json; charset=utf-8';
    }

    return headers;
  };
  const generateUrl = (
    baseUrl: string,
    path: string,
    paramsRoute: unknown[] = []
  ): string => {
    const params = paramsRoute.length ? '/' + paramsRoute.join('/') : '';
    return `${baseUrl}${path}${params}`;
  };

  const fetchHttpResource = async (
    options: IHttpResourceOption
  ): Promise<unknown> => {
    const url: string = generateUrl(
      options.baseUrl ?? import.meta.env.VITE_CLIENT_API_URL,
      options.path,
      Array.isArray(options.paramsRoute) ? options.paramsRoute : []
    );

    const method: string = options.method;

    const headers: Record<string, unknown> = setRequestHeaders(
      options.token ?? null,
      options.headers ?? options.header ?? {}
    );

    const params: Record<string, unknown> = options.params ?? {};

    const data: Record<string, unknown> | FormData = options.data ?? {};
    const timeout: number = options.timeout ?? 0;
    const auth: Record<string, any> | null = options.auth ?? null;

    const responseType: string = 'json';
    
    let body: IHttpResponse;

    try {
      const response = await axios(<AxiosRequestConfig>{
        url,
        method,
        headers,
        params,
        data,
        timeout,
        auth,
        responseType,
      });

      body = response?.data as IHttpResponse;
      if (body.code === 401) {
        localStorage.clear();
        router.push({ path: '/login' });
      }
    } catch (err) {
      body = await catchAxiosError(err);
    }

    return body;
  };

  const catchAxiosError = async (err: any): Promise<IHttpResponse> => {
    let body: IHttpResponse = {
      code: 400,
      status: false,
      data: [],
      message: 'Ha sucedido un inconveniente en la solicitud HTTP',
      otherMessage: '',
      otherData: [],
    };

    if (axios.isAxiosError(err)) {
      const error: AxiosError = err;
      const response = err.response as AxiosResponse;
      body = response?.data ?? body;

      if (error?.code === 'ERR_NETWORK') {
        return onNetworkError();
      } else if (error?.response?.status === 401) {
        return onUnauthorized(response);
      } else if (error?.response?.status === 404) {
        return onNotFound(response);
      }
    }

    return body;
  };

  const _onRequestSuccess = async (response: any) => {
    return response;
  };

  const _onRequestFailure = async (error: any) => {
    const { response } = error;

    if (response) {
      if (response.status == 401) {
        localStorage.clear();
        router.push({ path: '/login' });
      } else {
        return Promise.reject(error);
      }
    } else {
      localStorage.clear();
      router.push({ path: '/login' });
    }
  };

  const onNetworkError = (): IHttpResponse => {
    return {
      code: 599,
      status: false,
      data: [],
      message:
        'Ha sucedido un error en la red, se recomienda revisar su conexión a internet',
      otherMessage: '',
      otherData: [],
    };
  };

  const onUnauthorized = (response: AxiosResponse): IHttpResponse => {
    return {
      code: 401,
      status: false,
      data: [],
      message: response?.data?.message ?? 'Recurso no autorizado',
      otherMessage: '',
      otherData: [],
    };
  };

  const onNotFound = (response: AxiosResponse): IHttpResponse => {
    return {
      code: 404,
      status: false,
      data: [],
      message:
        response?.data?.message ?? 'No se encontró el recurso específico',
      otherMessage: '',
      otherData: [],
    };
  };

  /****************************************************************************/
  /*                              INJECT                                      */
  /****************************************************************************/
  axios.interceptors.response.use(_onRequestSuccess, _onRequestFailure);

  return { fetchHttpResource };
}
