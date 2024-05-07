workbox.precaching.precacheAndRoute([]);

self.addEventListener('push', (event) => {
  const payload = event.data.json();
  event.waitUntil(
    self.registration.showNotification(payload.title, {
      body: payload.body,
      icon: '/path_to_some_icon_if_needed.png',
    })
  );
});
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.ready.then(function(registration) {
      registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: 'YOUR_PUBLIC_VAPID_KEY'
      }).then(function(subscription) {
        // Send the subscription to your server
      });
    });
  }