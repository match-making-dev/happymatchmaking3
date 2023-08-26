"use strict";

registSW();

function registSW() {

  // Service Worker �Ή��u���E�U�̏ꍇ�A�X�R�[�v�Ɋ�Â���Service Worker ��o�^����

  if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
      navigator.serviceWorker.register('https://cdn.jsdelivr.net/gh/match-making-dev/happymatchmaking3@latest/pwa/sw.js', { scope: './' }).then(function (registration) {
        console.log('ServiceWorker registration successful with scope: ', registration.scope);
      }, function (err) {
        console.log('ServiceWorker registration failed: ', err);
      });
    });
  }
}
