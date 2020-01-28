<template>
    <Customizer :label="{ icon: 'ft-plus-square', bColor: 'bg-info' }" :open="open" @toggle="toggle">
        <div class="customizer-content">
            <form v-on:submit.prevent="submit" class="position-relative">

                <!-- Загрузочный экран -->
                <loader :show="creation" :full="false"></loader>

                <!-- Имя пользователя -->
                <div id="name">
                    <div class="customizer-section-header">
                        <h5 class="text-uppercase">Имя пользователя</h5>
                        <small class="text-uppercase">Укажите настоящее имя пользователя</small>
                    </div>
                    <div class="customizer-section-content">
                        <fieldset class="form-group mt-1 mb-2">
                            <div class="input-group">
                                <input type="text" class="form-control" v-model="user.name" required>
                            </div>
                        </fieldset>
                    </div>
                </div>

                <hr>

                <!-- Имя пользователя -->
                <div id="last_name">
                    <div class="customizer-section-header">
                        <h5 class="text-uppercase">Фамилия пользователя</h5>
                        <small class="text-uppercase">Поле обязательное для заполнения</small>
                    </div>
                    <div class="customizer-section-content">
                        <fieldset class="form-group mt-1 mb-2">
                            <div class="input-group">
                                <input type="text" class="form-control" v-model="user.last_name" required>
                            </div>
                        </fieldset>
                    </div>
                </div>

                <hr>

                <!-- Должность -->
                <div id="position">
                    <div class="customizer-section-header">
                        <h5 class="text-uppercase">Должность</h5>
                        <small class="text-uppercase">Официальная должность сотрудника</small>
                    </div>
                    <div class="customizer-section-content">
                        <fieldset class="form-group mt-1 mb-2">
                            <div class="input-group">
                                <input type="text" class="form-control" v-model="user.position" required>
                            </div>
                        </fieldset>
                    </div>
                </div>

                <hr>

                <!-- Должность -->
                <div id="email">
                    <div class="customizer-section-header">
                        <h5 class="text-uppercase">Email</h5>
                        <small class="text-uppercase">Данный email нужно для аутентификации пользователя</small>
                    </div>
                    <div class="customizer-section-content">
                        <fieldset class="form-group mt-1 mb-2">
                            <div class="input-group">
                                <input type="email" class="form-control" v-model="user.email" required>
                            </div>
                        </fieldset>
                    </div>
                </div>

                <hr>

                <!-- Пароль -->
                <div id="password">
                    <div class="customizer-section-header">
                        <h5 class="text-uppercase">Пароль</h5>
                        <small class="text-uppercase">Не забудьте этот пароль сохранить, так как дальнейшая расшифровка невозможна</small>
                    </div>
                    <div class="customizer-section-content">
                        <fieldset class="form-group mt-1 mb-2">
                            <div class="input-group">
                                <input type="text" class="form-control" v-model="user.password" required>
                            </div>
                        </fieldset>
                    </div>
                </div>

                <!-- Пароль -->
                <div id="isAdmin">
                    <div class="customizer-section-header">
                        <h5 class="text-uppercase">Администратор ?</h5>
                        <small class="text-uppercase">Напишите роль пользователя, доступные роли:</small>
                        <ul>
                            <li class="d-inline-block font-small-1">Администратор</li>
                            <li class="d-inline-block font-small-1">Пользователь</li>
                        </ul>
                    </div>
                    <div class="customizer-section-content">
                        <fieldset class="form-group mt-1 mb-2">
                            <div class="input-group">
                                <input type="text" class="form-control" v-model="role" required>
                            </div>
                        </fieldset>
                    </div>
                </div>

                <!-- Кнопки взаимодействия -->
                <div class="buttons mb-0">
                    <fieldset class="text-center">
                        <button class="btn btn-info mr-1" type="submit" style="width: 100%;" v-bind:disabled="disableCreateButton">Создать</button>
                    </fieldset>
                </div>

            </form>
        </div>
    </Customizer>
</template>

<script>
    import Axios from 'axios';
    import Loader from '@/components/Loader';
    import Customizer from '@/components/Customizer';
    export default {
        name: "index",
        components: { Loader, Customizer },
        props: {
            open: {
                type: Boolean,
                default: false
            }
        },
        data: function() {
            return {
                user: {
                    name: '',
                    last_name: '',
                    position: '',
                    email: '',
                    password: ''
                },

                role: '',

                // Доступные роли пользователей.
                roles: [],

                // Состояние создания пользователя.
                creation: false

            }
        },
        methods: {

            /**
             * Формирование и отправка запроса на создание сайта.
             * @param event
             * */
            async submit(event) {

                try {

                    // Состояние загрузки.
                    this.creation = true;

                    // Обработка введенного пароля.
                    if (this.user.password.length < 6) throw new Error('Минимальная длина пароля 6 символов');

                    // Формирование данных для запроса.
                    let data = {
                        name:       this.user.name,
                        last_name:  this.user.last_name,
                        position:   this.user.position,
                        email:      this.user.email,
                        password:   this.user.password,
                        role:       this.role
                    };

                    // Передача запроса на сторону сервера.
                    await Axios.post('users', data).then(user => {

                        // Отображение уведомления и закрытие кастомайзера.
                        this.$snotify.success('Пользователь успешно добавлен');
                        this.$emit('created', user);
                        setTimeout(() => {this.toggle()}, 1500);
                    }).catch(err => {});

                } catch (e) {
                    if (e.name === 'Error') this.$snotify.error(e.message);
                } finally {
                    setTimeout(() => {this.creation = false}, 1000);
                }

            },

            /**
             * Очистка всех данных компонента.
             * */
            reset() {
                let defaultData = this.$options.data.apply(this);
                defaultData.roles = this.roles;
                Object.assign(this.$data, defaultData);
            },

            /**
             * Открытие / закрытие компонента */
            toggle() {
                if (this.open) this.reset();
                this.$emit('toggle');
            }
        },

        computed: {

            // Состояние кнопки создания.
            disableCreateButton() {
                let result = false;
                for (let key in this.user) {
                    if (!this.user[key].length && !result) {
                        result = true;
                    }
                }
                return result;
            }
        },

        /**
         * Получение необходимых данных
         * на этапе создания экземпляра компонента.
         * @returns {Promise<void>}
         */
        async created() {
            // Получение доступных ролей.
            this.roles = await Axios.get('users/roles')
                .then(roles => {return roles.data});
        }

    }
</script>

<style lang="scss">
    #isAdmin {
        ul {
            margin-top: 4px;
            li {
                background-color: #ececec;
                border-radius: 3px;
                padding: 0.2rem 0.5rem;
                text-transform: uppercase;
            }
        }
    }
</style>
