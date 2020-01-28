import store from '@/store/index';

export default {
    install(Vue, options) {

        /**
         * Преобразование байтов в подходящий формат.
         * @param bites
         * @returns {boolean}
         */
        Vue.prototype.$formatSize = function (bites) {
            let i = 0, type = ['б','Кб','Мб','Гб','Тб','Пб'];
            while((bites / 1000 | 0) && i < type.length - 1) {
                bites /= 1024;
                i++;
            }
            return bites.toFixed(2) + ' ' + type[i];
        };

        /**
         * Преобразование байтов в подходящий формат.
         * @param str
         * @returns {boolean}
         */
        Vue.prototype.$getExtension = function (str) {
            return str.split(".").pop()
        };

    }
}