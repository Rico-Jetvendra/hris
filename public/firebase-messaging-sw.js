importScripts("https://www.gstatic.com/firebasejs/9.6.11/firebase-app-compat.js");
importScripts("https://www.gstatic.com/firebasejs/9.6.11/firebase-messaging-compat.js");

firebase.initializeApp({
    apiKey: "AIzaSyBRfYzr2qPCLKunvXEtb1DnTtX9SsL5OVw",
    authDomain: "fcm-veron-salesapp.firebaseapp.com",
    projectId: "fcm-veron-salesapp",
    messagingSenderId: "661546324879",
    appId: "1:661546324879:web:ae94db9d19c8473658f1fa"
});

const messaging = firebase.messaging();

// ⬇️ Add this: show actual notifications!
messaging.onBackgroundMessage((payload) => {
    console.log("[SW] Background message received:", payload);

    // FCM may put notification in different places depending on platform
    const notif = payload.notification || payload.webpush?.notification || {};

    const title = notif.title || "Notification";
    const options = {
        body: notif.body || "",
        icon: notif.icon || "/icon.png",
        data: payload.data || {}
    };

    console.log("[SW] Showing notification:", { title, options });

    // 🔥 The line that actually displays the popup
    self.registration.showNotification(title, options);
});

// ⬇️ Optional: handle click events
self.addEventListener("notificationclick", function(event) {
    event.notification.close();

    const url = "/orders";
    event.waitUntil(
        clients.matchAll({ type: "window" }).then((windowClients) => {
            for (const client of windowClients) {
                if (client.url === url && "focus" in client) {
                    return client.focus();
                }
            }
            return clients.openWindow(url);
        })
    );
});
