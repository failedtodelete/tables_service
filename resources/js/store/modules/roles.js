// Установленные значения.
import Axios from "axios";

const state = {
    roles: [],
    permissions: []
};

// Получение значений.
const getters = {};

// Действия.
const actions = {

    /**
     * Загрузка ролей с разрешениями.
     * @param state
     * @param commit
     * @returns {Promise<any>}
     * @constructor
     */
    async FETCH_ROLES ({ state, commit }) {
        if (state.roles.length) return state.roles;
        else {
            return Axios.get('users/roles?include=permissions')
                .then(roles => {
                    commit('FETCH_ROLES', roles.data);
                    return state.roles;
                });
        }
    },

    /**
     * Загрузка доступных разрешений.
     * @param state
     * @param commit
     * @returns {Promise<any>}
     * @constructor
     */
    async FETCH_PERMISSIONS ({ state, commit }) {
        if (state.permissions.length) return state.permissions;
        else {
            return Axios.get('users/permissions?include=type')
                .then(permissions => {
                    commit('FETCH_PERMISSIONS', permissions.data);
                    return state.permissions;
                })
        }
    },

    /**
     * Переключение разрешения для роли.
     * @param state
     * @param commit
     * @param data
     * @returns {Promise<AxiosResponse<any> | never>}
     * @constructor
     */
    async TOGGLE_PERMISSION_ROLE({ state, commit }, data) {

        // Получение роли и проверка существования разрешения.
        let role = state.roles.find(r => r.id === data.role_id);
        if (!role) throw new Error('Role not found');

        // Удаление разрешения у роли на стороне сервера.
        return Axios.post('users/roles/'+ data.role_id +'/toggle_permission?include=permissions', {
            permission_id: data.permission_id
        }).then(role => {
            commit('UPDATE_ROLE', role);
            return role;
        });
    }

};

// Мутации.
const mutations = {
    'FETCH_ROLES' (state, roles) {
        state.roles = roles;
    },
    'FETCH_PERMISSIONS' (state, permissions) {
        state.permissions = permissions;
    },
    'UPDATE_ROLE' (state, role) {
        let index = state.roles.findIndex(r => r.id === role.id);
        state.roles[index] = role;
    },
};

export default {
    state,
    getters,
    actions,
    mutations
};
