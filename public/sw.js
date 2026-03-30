const CACHE_NAME = "seafood-pwa-v1";
const APP_SHELL = [
    "/",
    "/offline.html",
    "/manifest.webmanifest",
    "/icons/icon.svg",
    "/icons/apple-touch-icon.svg",
    "/images/hero-seafood.jpg",
];

self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(APP_SHELL))
    );
    self.skipWaiting();
});

self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(
                keys
                    .filter((key) => key !== CACHE_NAME)
                    .map((key) => caches.delete(key))
            )
        )
    );
    self.clients.claim();
});

self.addEventListener("fetch", (event) => {
    if (event.request.method !== "GET") {
        return;
    }

    event.respondWith(
        fetch(event.request)
            .then((response) => {
                const clonedResponse = response.clone();

                if (event.request.url.startsWith(self.location.origin)) {
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, clonedResponse);
                    });
                }

                return response;
            })
            .catch(async () => {
                const cachedResponse = await caches.match(event.request);

                if (cachedResponse) {
                    return cachedResponse;
                }

                if (event.request.mode === "navigate") {
                    return caches.match("/offline.html");
                }

                return Response.error();
            })
    );
});
