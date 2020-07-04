/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

window.events = new Vue();

import Authorization from './Authorization';
import InstantSearch from 'vue-instantsearch';

Vue.use(InstantSearch);

Vue.prototype.authorize = function (...params) {
    const user = window.AwesomeForum.user;

    if (! user) {
        return false;
    }

    if (typeof [...params].shift() === 'string') {
        return Authorization[[...params].shift()]([...params].pop());
    }

    return [...params].shift()(user);
};

Vue.prototype.isSignedIn = () => window.AwesomeForum.isSignedIn;
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('flash', require('./components/Flash.vue').default);
Vue.component('scan', require('./components/Scan.vue').default);
Vue.component('wysiwyg', require('./components/Wysiwyg.vue').default);
Vue.component('thread-view', require('./pages/Thread.vue').default);
Vue.component('notifications', require('./components/Notifications.vue').default);
Vue.component('avatar-component', require('./components/AvatarComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

window.flash = function (message, level = 'success') {
    window.events.$emit('flash', { message, level });
};
