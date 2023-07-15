import { GET,POST } from '../../../ws/api/index.js'

window.addEventListener('DOMContentLoaded', async function () {
  var navbarContainer = document.querySelector('#navbar');

  fetch('/frontend/ws/staticHTML/navbar.html')
    .then(function (response) {
      return response.text();
    })
    .then(function (html) {
      navbarContainer.innerHTML = html;
    });
  if (!Cookies.get('token')) {
    window.location.href = '/frontend/src/pages/user/forms/'
    return
  }
  const r = await GET(`playlist/get.php?owner_token=${Cookies.get('token')}`)
    .then(data=>data.json())
    .then(r => {
      if(r){
        r.map(musica => {
          document.querySelector('#lista').innerHTML += musica
        })
      } else {
        document.querySelector('#lista').innerHTML += 'Não há nada por aqui ainda! Pesquise por musicas na pagina respectiva!'
      }
    })
    .catch(err=>console.error(err))

    const user = await GET(`user/get.php?owner_token=${Cookies.get('token')}`).then(data => data.json()).catch(err=>console.error(err))
    this.document.querySelector('.userEmail').textContent= user.email
    

    const removes = document.querySelectorAll('.remove')
    
    removes.forEach(musica => {
      musica.addEventListener('click',async (e) => {
        const cardId = e.target.closest('.card-musica').id;
        const formdata = new FormData()
        formdata.append('owner_token',Cookies.get('token'))
        formdata.append('musicaID',cardId)

        const r = await POST('playlist/remove.php',formdata).then(data=>data.json()).catch(err=>console.error(err))
        if(r.msg ==='success'){
          console.log(document.querySelector(`.card-musica[id="${cardId}"]`));
          document.querySelector(`.card-musica[id="${cardId}"]`).style.display = 'none';
        }
      })
    })
});


const formPesquisa = document.querySelector('#pesquisa-pl')

formPesquisa.addEventListener('submit',async (e)=> {
  e.preventDefault()
  const formdata = new FormData(formPesquisa);

  await POST('playlist/find.php',formdata).then(data=>data.json())
  .then(r => {
    if(r){
      document.querySelector('#lista').innerHTML ='';
      r.map(musica => {
        document.querySelector('#lista').innerHTML += musica
      })
    } 
  })

  }
)