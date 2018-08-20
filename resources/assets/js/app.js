
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.BootstrapVue = require('bootstrap-vue');

Vue.use(BootstrapVue);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));

//Access Codes Generator

Vue.component(
    'access-code',
    require('./components/clients/GenerateAccessCode.vue')
    );

//Passport Components
Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue')
);

Vue.component(
    'passportauthorizedclients',
    require('./components/passport/AuthorizedClients.vue')
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
);

//Verbos Components
Vue.component(
    'verbos-create',
    require('./components/verbos/Create.vue')
);

Vue.component(
    'dict-create',
    require('./components/verbos/Dict.vue')
);

Vue.component(
    'info-create',
    require('./components/verbos/Info.vue')
);


Vue.component(
    'verbos-show',
    require('./components/verbos/Show.vue')
);

const app = new Vue({
    el: '#app'
});
