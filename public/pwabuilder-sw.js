// This is the "Offline copy of assets" service worker

const CACHE = "pwabuilder-offline";

importScripts('https://storage.googleapis.com/workbox-cdn/releases/5.1.2/workbox-sw.js');

self.addEventListener("message", (event) => {
  if (event.data && event.data.type === "SKIP_WAITING") {
    self.skipWaiting();
  }
});

// Network-first cache for main pages
workbox.routing.registerRoute(
  new RegExp('/*'),
  new workbox.strategies.NetworkFirst({
    cacheName: CACHE
  })
);

// // Stale while revalidate cache for CSS and JS
// workbox.routing.registerRoute(
//   ({request}) => request.destination === 'script' ||
//                  request.destination === 'style',
//   new workbox.strategies.StaleWhileRevalidate({
//     cacheName: 'css-and-js'
//   })
// );
