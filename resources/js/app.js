require('./bootstrap');
require('./noty');

window.Vue = require('vue').default;
Vue.component('job-button', require('./components/JobButton.vue').default);

const app = new Vue({
    el: '#app',
});
