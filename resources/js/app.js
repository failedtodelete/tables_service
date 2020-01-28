import Vue from 'vue'
import VueLodash from 'vue-lodash'
import VueBus from 'vue-bus';
import Axios from 'axios';
import Snotify, { SnotifyPosition } from 'vue-snotify';
import VModal from 'vue-js-modal';
import vSelect from 'vue-select';
import moment from 'moment';
import VueBreadcrumbs from 'vue-breadcrumbs';
import PrettyCheckbox from 'pretty-checkbox-vue';
import Tooltip from 'vue-directive-tooltip';

// App
import App from './App.vue';
import store from './store';
import router from "./routes";

// Plugins
import PermissionPlugin from './plugins/permission';
import SystemPlugin from './plugins/system';

Vue.use(Tooltip);
Vue.use(PrettyCheckbox);
Vue.use(VueBreadcrumbs, {
    template: '<div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new" v-if="$breadcrumbs.length">' +
                '<h3 class="content-header-title mb-0 d-inline-block">{{ $breadcrumbs[$breadcrumbs.length - 1].meta.breadcrumb }}</h3>' +
                '<div class="row breadcrumbs-top d-inline-block">' +
                    '<div class="breadcrumb-wrapper col-12">' +
                        '<ol class="breadcrumb">' +
                            '<li class="breadcrumb-item">' +
                                '<router-link :to="{name:\'profile\'}">Главная</router-link>' +
                            '</li>' +
                            '<li class="breadcrumb-item" v-for="(crumb, key) in $breadcrumbs" v-if="key !== $breadcrumbs.length - 1">' +
                                '<router-link :to="linkProp(crumb)" :key="key" >{{ crumb | crumbText }}</router-link>' +
                            '</li>' +
                        '</ol>' +
                    '</div>' +
                '</div>' +
               '</div>'
});

Vue.use(VueBus);
Vue.use(VModal, {dynamic: true, dynamicDefaults: {clickToClose: false}});
Vue.use(Snotify, {
    toast: {
        position: SnotifyPosition.rightBottom,
        showProgressBar: false
    }
});
Vue.use(PermissionPlugin);
Vue.use(SystemPlugin);
Vue.use(VueLodash, {name: 'lodash'});
Vue.component('v-select', vSelect);


Vue.prototype.$eventHub = new Vue();
Vue.prototype.moment = moment;

// Axios settings
Axios.defaults.baseURL = '/api';
Axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': 'Bearer ' + store.state.user.profile.api_token,
};

Axios.interceptors.response.use(function (response) {
    if (response.data.status === false) {
        if (response.data.message === 'Unauthenticated') {
            setTimeout(() => { location.href = '/login' }, 0);
        }
        app.$snotify.error(response.data.message);
        return Promise.reject(new Error(response.data.message));
    }
    return response.data;
}, function (error) {
    app.$snotify.error(error.message);
    return Promise.reject(error);
});

// Application
let app = new Vue({
    el: '#app',
    store,
    router,
    render: h => h(App)
});
