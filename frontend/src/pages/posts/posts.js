import { GET, POST } from '../../../ws/api/index.js'
import jwtDecode from 'https://cdn.jsdelivr.net/npm/jwt-decode@3.1.2/+esm'
import Cookies from 'https://cdn.jsdelivr.net/npm/js-cookie@3.0.5/+esm'

window.addEventListener('DOMContentLoaded', function () {
  var navbarContainer = document.getElementById('navbar');

  fetch('./../../../ws/staticHTML/navbar.html')
    .then(function (response) {
      return response.text();
    })
    .then(function (html) {
      navbarContainer.innerHTML = html;
    });
});


async function getPosts() {
  try {
    const response = await GET(`post/getAll.php?token=${Cookies.get('token')}`);
    const data = await response.json();
    console.log(data);
    return data.posts;
  } catch (error) {
    console.error(error);
    return [];
  }
}
let posts;
let isVotedID;
async function updatePosts() {
  posts = await getPosts()

  const postsContainer = document.getElementById('posts');
  postsContainer.innerHTML = posts.join('');

  const upvoltElements = document.querySelectorAll('[id^="upvolt-"]');
  const downvoltElements = document.querySelectorAll('[id^="downvolt-"]');

  upvoltElements.forEach((element) => {
    element.addEventListener('click', async function () {
      const postId = this.id.split('-')[1];
      let count = 0;



      if (isVotedID === postId) {
        count = 2;
      } else {
        count = 1;
      }

      const token = Cookies.get('token');
      console.log(token);

      const formdata = new FormData();
      formdata.append('count', count)
      formdata.append('post_id', postId)
      formdata.append('user', token)
      console.log(count, postId);

      await POST('/post/note/upvolt.php', formdata).then(data => data.json()).then(r => console.log(r)).catch(err => console.error(err))

      updatePosts()

      isVotedID = postId;
    });
  });

  downvoltElements.forEach((element) => {
    element.addEventListener('click', async function () {
      const postId = this.id.split('-')[1];
      let count = 0;


      if (isVotedID === postId) {
        count = 2;
      } else {
        count = 1;
      }
      const token = Cookies.get('token');

      const formdata = new FormData();
      formdata.append('count', count)
      formdata.append('post_id', postId)
      formdata.append('user', token)
      console.log(count, postId);

      await POST('/post/note/downvolt.php', formdata).then(data => data.json()).then(r => console.log(r)).catch(err => console.error(err))
      updatePosts()

      isVotedID = postId;
    });
  });
}

updatePosts();