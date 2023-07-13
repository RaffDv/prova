import { POST } from '../../../ws/api/index.js'


const form_pesquisa = document.getElementById('formulario_pesquisa')

form_pesquisa.addEventListener('submit',async  (e)=>{
  e.preventDefault();
  let formData = new FormData(formLogin);

  if (Cookies.get('token')) {
    return
  } else {
    const r = await POST('playlists/get.php', formData).then(data => data.json()).catch(err => console.error(err))
    console.log(r);
    }
})