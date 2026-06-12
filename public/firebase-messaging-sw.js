importScripts('https://www.gstatic.com/firebasejs/12.7.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/12.7.0/firebase-messaging-compat.js');

firebase.initializeApp({
    apiKey: "AIzaSyCy3lqCQeMUIJKdRPB2c8gzc15VgNLxFjs",
    authDomain: "restaurant-1f6bf.firebaseapp.com",
    projectId: "restaurant-1f6bf",
    storageBucket: "restaurant-1f6bf.firebasestorage.app",
    messagingSenderId: "292218144073",
    appId: "1:292218144073:web:5cdf6fbefd9e369fe78698",
    measurementId: "G-1PLX6VG0NL"
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(payload => {
    self.registration.showNotification(
        payload.notification.title,
        {
            body: payload.notification.body,
            icon: payload.notification.icon,
            data: payload.data,
        }
    );
});
