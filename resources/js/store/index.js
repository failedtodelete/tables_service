import Vue from 'vue';
import Vuex from 'vuex';

// Modules
import ui from './modules/ui';
import user from './modules/user';
import roles from './modules/roles';

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';
export default new Vuex.Store({
    modules: {
        user,
        roles,
        ui
    },
    strict: debug
});
