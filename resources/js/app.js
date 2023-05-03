import './bootstrap';
import './requires/miscFuncs';
import '../css/app.css';

import { createApp, h } from 'vue';
import {createPinia} from "pinia";
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import timeago from 'vue-timeago3';
import {PiniaHistoryPlugin} from "@/plugins/PiniaHistoryPlugin";

const pinia = createPinia()
pinia.use( PiniaHistoryPlugin )

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .use(timeago)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
