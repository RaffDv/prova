import Cookies from 'https://cdn.jsdelivr.net/npm/js-cookie@3.0.5/+esm'


if (Cookies.get('token')) {
  Cookies.remove('token');
  window.location.href = '/frontend/src/pages/user/forms'


} else {
  console.log('token existe');
  window.location.href = '/frontend/src/pages/user/forms'

}

