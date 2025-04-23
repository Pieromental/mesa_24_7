import { Loading, QSpinnerFacebook } from 'quasar';

export function useLoading() {
  const showLoading = () => {
    Loading.show({
      spinner: QSpinnerFacebook,
      spinnerColor: 'white',
      spinnerSize: 140,
      backgroundColor: 'primary',
      message: 'Estamos procesando la información. En breve continuamos',
      messageColor: 'white',
    });
  };

  const hideLoading = () => {
    Loading.hide();
  };

  return {
    showLoading,
    hideLoading,
  };
}
