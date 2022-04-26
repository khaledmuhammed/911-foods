require('./bootstrap');
require('../../node_modules/jquery/dist/jquery');
require('../../node_modules/jquery/dist/jquery.slim');
require('../../node_modules/aos/dist/aos');
require('../../node_modules/slick-carousel/slick/slick');
require('../../node_modules/bootstrap/dist/js/bootstrap.bundle');
window.Vue = require('vue').default;

import Notifications from 'vue-notification'
import {i18n} from './i18n';
import { VueperSlides, VueperSlide } from 'vueperslides'
import 'vueperslides/dist/vueperslides.css'
import VueGeolocation from 'vue-browser-geolocation';
import * as VueGoogleMaps from 'vue2-google-maps';

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('faq-help', require('./components/FaqHelp.vue').default);
Vue.component('footer-categories-links', require('./components/footer/FooterCategoriesLinks.vue').default);
Vue.component('footer-fields-links', require('./components/footer/FooterFieldsLinks.vue').default);
Vue.component('favorites', require('./components/Favorites.vue').default);
Vue.component('banner', require('./components/Banner.vue').default);
Vue.component('main-index', require('./components/MainIndex.vue').default);
Vue.component('market-details', require('./components/MarketDetails.vue').default);
Vue.component('list-markets', require('./components/ListMarkets.vue').default);

Vue.component('vueper-slides', VueperSlides);
Vue.component('vueper-slide', VueperSlide);

Vue.use(VueGoogleMaps, {load: {key: google_maps_key}});
Vue.use(VueGeolocation);

Vue.use(Notifications);


const app = new Vue({
    i18n,
    el: '#app',
});


