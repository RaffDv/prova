import { POST } from '../../../ws/api/index.js'


const form_pesquisa = document.getElementById('formulario_pesquisa')

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

form_pesquisa.addEventListener('submit', async (e) => {
  e.preventDefault();
  let formData = new FormData(form_pesquisa);

  if (!Cookies.get('token')) {
    return
  } else {
    formData.append("owner_token", Cookies.get('token'));
    await POST('musics/get.php', formData)
    .then(data=>data.json())
    .then(r => {
      if(r){
        r.map(musica => {
          console.log(musica);
          document.querySelector('#lista').innerHTML += musica
        })
      }
    })
    .catch(err => console.error(err)) 

  const musicas = document.querySelectorAll('.card-musica span.to-playlist');
  musicas.forEach(musica => {
    musica.addEventListener('click', async (e) => {
      const cardId = e.target.closest('.card-musica').id;
      const formdata = new FormData()
      formdata.append('owner_token',Cookies.get('token'))
      formdata.append('musicaID',cardId)

      await POST('playlist/add.php',formdata).then(data=>data.json()).catch(err=>console.error(err))
      
    });
  });
}})

