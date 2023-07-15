import { POST } from '../../../../ws/api/index.js'

window.addEventListener('DOMContentLoaded', function () {
  var navbarContainer = document.querySelector('#navbar');

  fetch('/frontend/ws/staticHTML/navbar.html')
    .then(function (response) {
      return response.text();
    })
    .then(function (html) {
      navbarContainer.innerHTML = html;
    });
});


const formRegister = document.querySelector('#registerForm');
const registerBtn = document.querySelector('.createBtn')

const formLogin = document.querySelector('#loginForm');
const loginBtn = document.querySelector('.loginBtn')

registerBtn.addEventListener('click', async event => {
  event.preventDefault();
  let formData = new FormData(formRegister);

  await POST('user/new.php', formData)
    .then(data => data.json())
    .then(item => {
      if (item.msg.includes('code 1062')) {
        console.log(item);
        window.alert('username in use! try another')
      }
    }
    )
    .catch(err => console.error(err))
  const r = await POST('user/login.php', formData).then(data => data.json()).catch(err => console.error(err))
  if (r.token) {
    window.location.href = `/frontend/src/api/auth/user/login/void.html?token=${r.token}`
  }
})

loginBtn.addEventListener('click', async event => {
  event.preventDefault();
  let formData = new FormData(formLogin);

  if (Cookies.get('token')) {
    return
  } else {
    const r = await POST('user/login.php', formData).then(data => data.json()).catch(err => console.error(err))
    console.log(r);
    if (r.token) {
      window.location.href = `/frontend/src/api/auth/user/login/void.html?token=${r.token}`
    }

  }
});

if (Cookies.get('token')) {
  loginBtn.classList.add('disabled')
  registerBtn.classList.add('disabled')
}
