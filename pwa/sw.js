// Service Worker �̃o�[�W�����ƃL���b�V������ App Shell ���`����

const NAME = 'qiita-post-app-v2-';
const VERSION = '002';
const CACHE_NAME = NAME + VERSION;
const urlsToCache = [
  './index.html',
  './main.js',
  './bootstrap.min.css',
];

// Service Worker �փt�@�C�����C���X�g�[��

self.addEventListener('install', function (event) {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(function (cache) {
        console.log('Opened cache');
        return cache.addAll(urlsToCache);
      })
  );
});

// ���N�G�X�g���ꂽ�t�@�C���� Service Worker �ɃL���b�V������Ă���ꍇ
// �L���b�V�����烌�X�|���X��Ԃ�

self.addEventListener('fetch', function (event) {
  if (event.request.cache === 'only-if-cached' && event.request.mode !== 'same-origin')
    return;
  event.respondWith(
    caches.match(event.request)
      .then(function (response) {
        if (response) {
          return response;
        }
        return fetch(event.request);
      })
  );
});

// Cache Storage �ɃL���b�V������Ă���T�[�r�X���[�J�[��key�ɕύX���������ꍇ
// �V�o�[�W�������C���X�g�[����A���o�[�W�����̃L���b�V�����폜����
// (���̃t�@�C���ł� CACHE_NAME ��key�̒l�Ƃ݂Ȃ��A�ύX�����m���Ă���)

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys => Promise.all(
      keys.map(key => {
        if (!CACHE_NAME.includes(key)) {
          return caches.delete(key);
        }
      })
    )).then(() => {
      console.log(CACHE_NAME + "activated");
    })
  );
});