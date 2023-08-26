// Service Worker �̃o�[�W�����ƃL���b�V������ App Shell ���`����

const NAME = 'EarnestHeart2-';
const VERSION = '001';
const CACHE_NAME = NAME + VERSION;
const urlsToCache = [
  'https://earnestheart2--dev2.sandbox.my.salesforce-sites.com/'
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

