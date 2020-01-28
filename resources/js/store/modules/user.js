import Axios from "axios";

// Установленные значения.
const state = {
    profile: authenticate.user,
    permissions: authenticate.permissions,
    role: authenticate.role
};

// Получение значений.
const getters = {
    USER_PERMISSIONS: state => {
        return state.permissions;
    },
    USER_PERMISSION_FIND_BY_NAME: state => name => {
        return state.permissions.find(p => p.name === name);
    }
};

// Действия.
const actions = {

    /**
     * Добавление сайта пользователю.
     * @param state
     * @param commit
     * @param site
     * @constructor
     */
    ADD_TEMP_SITE({ state, commit }, site) {
        commit('ADD_TEMP_SITE', site);
    }

};

// Мутации.
const mutations = {
    'ADD_TEMP_SITE' (state, site) {
        state.profile.sites.push(site);
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
