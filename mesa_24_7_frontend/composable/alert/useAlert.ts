import { useQuasar } from 'quasar';
import { useLoading } from '../loading/useLoading';
import { IHttpResponse } from '../fetch/useFetch';

export function useAlert() {
  const $q = useQuasar();
  const { hideLoading } = useLoading();
  /****************************************************************************/
  /*                             METHODS                                      */
  /****************************************************************************/
  interface typeAlert {
    type: 'success' | 'error' | 'question' | 'information' | 'warning';
  }

  const arrImg = [
    { src: '/images/icons/alert/positive.png', type: 'success' },
    { src: '/images/icons/alert/negative.png', type: 'error' },
    { src: '/images/icons/alert/question.png', type: 'question' },
    { src: '/images/icons/alert/information.png', type: 'information' },
    { src: '/images/icons/alert/warning.png', type: 'warning' },
  ];

  const singleAlert = async (
    tipoStr: typeAlert,
    titleStr: string,
    messageStr: string
  ) => {
    hideLoading();
    const pos = arrImg.findIndex((obj) => obj.type === tipoStr.type);
    return new Promise((resolve) => {
      $q.dialog({
        title: '',
        message:
          `<div class="row">
          <div class="col-12 text-center">
            <h5 style="margin:5px;">` +
          titleStr +
          `</h5>
          </div>
          <div class="col-12 text-center">
            <img width="100" style="margin:5px;" src=` +
          arrImg[pos].src +
          `>
          </div>
          <div class="col-12 text-center">
            <p style="margin:5px;">` +
          messageStr +
          `</p>
          </div>
        </div>`,
        html: true,
        ok: {
          label: 'Aceptar',
          color: 'primary',
          flat: true,
        },
        persistent: true,
      }).onOk(() => {
        resolve(true);
      });
    });
  };

  const confirmAlert = async (
    tipoStr: typeAlert,
    titleStr: string,
    messageStr: string = 'Pulse aceptar para continuar',
    okLabel?: string,
    cancelLabel?: string
  ) => {
    hideLoading();
    const pos = arrImg.findIndex((obj) => obj.type === tipoStr.type);
    return new Promise((resolve) => {
      $q.dialog({
        title: '',
        message:
          `<div class="row">
          <div class="col-12 text-center">
            <h5 style="margin:5px;">` +
          titleStr +
          `</h5>
          </div>
          <div class="col-12 text-center">
            <img width="100" style="margin:5px;" src=` +
          arrImg[pos].src +
          `>
          </div>
          <div class="col-12 text-center">
            <p style="margin:5px;">` +
          messageStr +
          `</p>
          </div>
        </div>`,
        html: true,
        ok: {
          label: okLabel || 'Aceptar',
          color: 'primary',
          flat: true,
        },
        cancel: {
          label: cancelLabel || 'Cancelar',
          color: 'negative',
          flat: true,
        },
        persistent: true,
      })
        .onOk(() => {
          resolve(true);
        })
        .onCancel(() => {
          resolve(false);
        });
    });
  };
  const promptAlert = async (
    tipoStr: typeAlert,
    titleStr: string,
    messageStr: string,
    defaultValue: string
  ) => {
    hideLoading();
    return new Promise((resolve) => {
      $q.dialog({
        title: '',
        message:
          `<div class="row">
              <div class="col-12 text-center">
                <h5 style="margin:5px;">` +
          titleStr +
          `</h5>
              </div>
              <div class="col-12 text-center">
            <p style="margin:5px;">` +
          messageStr +
          `</p>
          </div>
            </div>`,
        html: true,
        prompt: {
          model: defaultValue,
          isValid: (val) => val.length > 0,
          type: 'text',
        },
        ok: {
          label: 'Aceptar',
          color: 'primary',
          flat: true,
        },
        cancel: {
          label: 'Cancelar',
          color: 'negative',
          flat: true,
        },
        persistent: true,
      })
        .onOk((data) => {
          resolve(data);
        })
        .onCancel(() => {
          resolve(null);
        });
    });
  };
  const showAlertFromResponse = async (response: IHttpResponse) => {
    const type = response.status
      ? 'success'
      : response.code === 300
      ? 'warning'
      : 'error';

    await singleAlert(
      { type },
      response.title ?? 'Aviso',
      response.message ?? 'Algo ocurrió'
    );
  };

  return { singleAlert, confirmAlert, promptAlert, showAlertFromResponse };
}
