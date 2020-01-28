import store from '@/store/index';

export default {
    install(Vue, options) {

        /**
         * Внедренный метод $can проверяет наличие переданного разрешения у пользователя.
         * @param permission
         * @returns {boolean}
         */
        Vue.prototype.$can = function (permission) {
            permission = store.getters.USER_PERMISSION_FIND_BY_NAME(permission);
            return typeof permission === 'object';
        };

        /**
         * Директива @permission.
         * Удаление элемента, если у пользователя нет переданного разрешения.
         */
        Vue.directive('permission', {
            isEmpty: false,

            inserted(el, binding, vnode, oldVnode) {

                let access = false;
                switch (typeof binding.value) {
                    case 'string':
                        access = Vue.prototype.$can(binding.value);
                        break;
                    case 'object':
                        if (binding.value.type === 'anyOf') {
                            if ('list' in binding.value) {
                                for(let key in binding.value.list) {
                                    if (Vue.prototype.$can(binding.value.list[key]))
                                        access = true;
                                }
                            }
                        }
                        break;
                }

                if (!access) el.remove();

            }
        });
    }
}