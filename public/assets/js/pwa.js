'use strict';

document.addEventListener('DOMContentLoaded', () => {

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker
                .register('/sw.js')
                .then((registration) => {
                    console.log('Service Worker registered:', registration.scope);
                })
                .catch((error) => {
                    console.error('Service Worker registration failed:', error);
                });
        });
    }

    let deferredPrompt = null;

    const installButton = document.getElementById('pwaInstallButton');

    window.addEventListener('beforeinstallprompt', (event) => {
        event.preventDefault();
        deferredPrompt = event;

        if (installButton) {
            installButton.hidden = false;
        }
    });

    if (installButton) {
        installButton.addEventListener('click', async () => {
            if (! deferredPrompt) {
                return;
            }

            deferredPrompt.prompt();

            const choiceResult = await deferredPrompt.userChoice;

            if (choiceResult.outcome === 'accepted') {
                console.log('User accepted PWA install');
            } else {
                console.log('User dismissed PWA install');
            }

            deferredPrompt = null;
            installButton.hidden = true;
        });
    }

    window.addEventListener('appinstalled', () => {
        console.log('PWA installed');

        if (installButton) {
            installButton.hidden = true;
        }
    });

});