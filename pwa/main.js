"use strict";

registSW();
getPost();

function registSW() {

  // Service Worker �Ή��u���E�U�̏ꍇ�A�X�R�[�v�Ɋ�Â���Service Worker ��o�^����

  if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
      navigator.serviceWorker.register('./sw.js', { scope: './' }).then(function (registration) {
        console.log('ServiceWorker registration successful with scope: ', registration.scope);
      }, function (err) {
        console.log('ServiceWorker registration failed: ', err);
      });
    });
  }
}

function getPost() {

  fetch('https://qiita.com/api/v2/items')
    .then(response => {
      return response.json();

    }).then(res => {

      const title = res[0].title;
      const url = res[0].url;
      const data = `<a href="${url}">${title}</a>`;
      document.getElementById("newitem").innerHTML = data;

    }).catch(function (error) {
      console.log(error);
    });
}