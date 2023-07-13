import Cookies from 'https://cdn.jsdelivr.net/npm/js-cookie@3.0.5/+esm'


if (!Cookies.get('token')) {
  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);

  Cookies.set('token', urlParams.get('token'), { expires: 5 })
  console.log(Cookies.get());

  if (Cookies.get('token')) {
    window.location.href = '/frontend/src/pages/posts/index.html'
  }

} else {
  console.log('token existe');
  window.location.href = '/frontend/src/pages/posts/index.html'

}

