import Echo from 'laravel-echo';

import Pusher from 'pusher-js';

window.Pusher = Pusher;

const scheme = (import.meta.env.VITE_REVERB_SCHEME ?? 'https').toLowerCase();
const useTls = scheme === 'https';
const port = Number(import.meta.env.VITE_REVERB_PORT) || (useTls ? 443 : 80);

const csrfToken = document.head.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

const reverbKey = import.meta.env.VITE_REVERB_APP_KEY;
const reverbHost = import.meta.env.VITE_REVERB_HOST || window.location.hostname;

if (import.meta.env.DEV && !reverbKey) {
    console.warn(
        '[Echo/Reverb] VITE_REVERB_APP_KEY está vazio. Defina no .env e reinicie `npm run dev` (o Vite só lê variáveis VITE_* ao arrancar).'
    );
}

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: reverbKey,
    wsHost: reverbHost,
    wsPort: port,
    wssPort: port,
    forceTLS: useTls,
    encrypted: useTls,
    // Obrigatório para hosts customizados (Reverb); sem isto o pusher-js pode falhar a ligar.
    cluster: '',
    enabledTransports: ['ws', 'wss'],
    authEndpoint: `${window.location.origin}/broadcasting/auth`,
    auth: {
        headers: {
            'X-CSRF-TOKEN': csrfToken ?? '',
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    },
});
