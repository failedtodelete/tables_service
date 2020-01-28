// Установленные значения.
const state = {
    loading: false
};

// Получение значений.
const getters = {};

// Действия.
const actions = {

    /**
     * Добавление сайта пользователю.
     * @param state
     * @param commit
     * @param site
     * @constructor
     */
    ENABLE_LOADING({ state, commit }, value) {
        commit('ENABLE_LOADING', value);
    }
};

// Мутации.
const mutations = {
    'ENABLE_LOADING' (state, value) {
        state.loading = value;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
