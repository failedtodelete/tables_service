<template>
    <Customizer :label="{ icon: 'ft-plus-square', bColor: 'bg-info' }" :open="open" @toggle="toggle">
        <div class="customizer-content">
            <form v-on:submit.prevent="submit" class="position-relative">

                <!-- Загрузочный экран -->
                <loader :show="creation" :full="false"></loader>

                <!-- Название таблицы -->
                <div id="table-name">
                    <div class="customizer-section-header">
                        <h5>Название таблицы</h5>
                        <small>Минимальная длина названия таблицы составляет 4 символа</small>
                    </div>
                    <div class="customizer-section-content">
                        <fieldset class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="la la-info-circle"></i></span>
                                </div>
                                <input type="text" class="form-control" v-model="table.name" required>
                            </div>
                        </fieldset>
                    </div>

                    <div class="customizer-section-header">
                        <h5>Описание таблицы</h5>
                        <small>Поле не обязательное к заполнению</small>
                    </div>
                    <div class="customizer-section-content">
                        <fieldset class="form-group">
                            <textarea class="form-control" rows="3" style="resize: none;" v-model="table.description"></textarea>
                        </fieldset>
                    </div>
                </div>

                <hr>

                <!-- Добавление колонок -->
                <div class="table-columns">
                    <div class="customizer-section-header">
                        <h5>Колонки</h5>
                        <small>Количество добавляемых колонок в таблицу не ограничено</small>
                    </div>
                    <div class="customizer-section-content">
                        <fieldset class="form-group mb-1 mt-0">
                            <ul class="columns-list">
                                <li class="columns-list-item" v-for="(column, index) in table.columns">
                                    <div class="name">
                                        <small class="position d-block">Колонка {{ index + 1 }}</small>
                                        <p class="m-0">{{ column.name }} [{{ column.width }}]</p>
                                        <small>{{ column.description }}</small>
                                    </div>
                                    <div class="buttons float-right">
                                        <ul class="button-list">
                                            <li class="button-list-item" @click="$modal.show('table.column.create', {type: 'update', column: column})"><i class="la la-edit"></i></li>
                                            <li class="button-list-item" @click="deleteColumn(index)"><i class="la la-minus-circle"></i></li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </fieldset>
                        <div class="columns-adding-button">
                            <button type="button" class="btn btn-sm btn-info btn-min-width" @click="$modal.show('table.column.create', {type: 'create'})">Создать</button>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Доступные пользователи -->
                <div class="table-users" v-if="users.length">
                    <div class="customizer-section-header">
                        <h5>Доступные пользователи</h5>
                        <small>Не забудьте указать возможность добавления строк, если это необходимо</small>
                    </div>
                    <div class="customizer-section-content">
                        <fieldset class="form-group">
                            <ul class="user-list">
                                <li class="user-list-item" v-for="user in users" v-bind:class="{ active: table.users.find(u => u.id === user.id)}">
                                    <div class="name">
                                        <small class="position d-block">{{ user.position }}</small>
                                        <p class="m-0">{{ user.name }}</p>
                                    </div>
                                    <div class="buttons float-right">
                                        <ul class="button-list">
                                            <li class="button-list-item" @click="selectUser(user)" v-bind:class="{ active: table.users.find(u => u.id === user.id)}"><i class="la la-eye"></i></li>
                                            <li class="button-list-item" @click="selectAddRow(user)" v-bind:class="{ active: table.users.find(u => u.id === user.id && u.adding_row)}"><i class="la la-plus-square"></i></li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </fieldset>
                    </div>
                </div>

                <!-- Кнопки взаимодействия -->
                <div class="buttons mb-0">
                    <fieldset class="text-center">
                        <button class="btn btn-info" type="submit" style="width: 100%;">Создать</button>
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
        name: "table.create",
        components: {Customizer, Loader},
        props: {
            open: {
                type: Boolean,
                required: true
            }
        },
        data() {
            return {

                // Данные таблицы для создания.
                table: {
                    name: '',
                    description: '',
                    users: [],
                    columns: []
                },

                // Доступные пользователи.
                users: [],

                // Состояние создания таблицы.
                creation: false
            }
        },
        methods: {

            // Создание таблицы.
            async submit() {
                try {

                    // Отображение окна загрузки.
                    this.creation = true;

                    // Проверка созданных колонок таблицы.
                    if (!this.table.columns.length) throw new Error('У создаваемой таблицы должны быть колонки');

                    // Проверка присвоенных пользователей.
                    if (!this.table.users.length) throw new Error('У создаваемой таблицы должны быть пользователи');

                    // Отпрака запроса на создание таблицы.
                    await Axios.post('tables', this.table).then(table => {
                        setTimeout(() => {

                            // Отображение уведомления пользователю.
                            this.$snotify.success('Таблица успешно создана');

                            // Вызов события добавления таблицы.
                            this.$emit('created', table);

                            // Закрытие кастомайзера.
                            setTimeout(() => {this.toggle()}, 1500);


                        }, 1000);
                    }).catch(e => {});

                } catch(e) {

                    // Отображение уведомления об ошибке.
                    this.$snotify.error(e.message);
                }

                // Отключение окна загрузки.
                setTimeout(() => {this.creation = false}, 1000);

            },

            // Очистка всех данных компонента.
            reset() {
                let defaultData = this.$options.data.apply(this);
                defaultData.users = this.users;
                Object.assign(this.$data, defaultData);
            },

            // Открытие / закрытие компонента
            toggle() {

                if (this.open) this.reset();
                else {this.$bus.on('create-column', this.createColumn)}
                this.$emit('toggle');
            },

            // Выбор пользователя на просмотр таблицы.
            selectUser(user, adding_row = false) {
                if (!this.table.users.length || !this.table.users.find(u => u.id === user.id)) {
                    this.table.users.push({id: user.id, adding_row: adding_row});
                } else {
                    let index = this.table.users.findIndex(u => u.id === user.id);
                    if (index !== -1) this.table.users.splice(index, 1);
                }
            },

            // Выбор пользователя на добавление строк в таблице.
            selectAddRow(user) {
                let index = this.table.users.findIndex(u => u.id === user.id);
                if (index === -1)this.selectUser(user, true);
                else this.table.users[index].adding_row = !this.table.users[index].adding_row;
            },

            // Создание колонки.
            createColumn(column) {
                this.table.columns.push(column);
            },

            // Удаление колонки по индексу.
            deleteColumn(index) {
                this.table.columns.splice(index, 1);
            }

        },

        // Предварительная загрузка пользователей.
        async created() {
            this.users = await Axios.get('users').then(users => {
                return users.data.filter(u => u.role.name !== 'admin');
            });
        }

    }
</script>

<style lang="scss">
    .table-columns {
        ul.columns-list {
            li.columns-list-item {
                background-color: #f7f7f7;
                border-radius: 3px;
                position: relative;
                margin-bottom: 6px;

                .name {
                    display: inline-block;
                    padding: 9px 0 2px 10px;

                    small {line-height: 1}
                    p {color: #585858}
                }
                .buttons {
                    position: absolute;
                    top: 0;
                    right: 0;

                    ul.button-list {
                        li.button-list-item {
                            display: inline-block;
                            background-color: #fff;
                            padding: 0.8rem 1.2rem;
                            cursor: pointer;

                            i {
                                font-size: 1.4rem;
                                vertical-align: middle;
                                transition: .1s;
                                color: #6b6f82;
                            }

                            &:first-child {
                                background-color: transparent !important;
                            }

                            &:last-child {
                                border-top-right-radius: 3px;
                                border-bottom-right-radius: 3px;
                            }
                        }
                    }
                }
            }
        }
    }
    .table-users {
        ul.user-list {
            li.user-list-item {
                background-color: #f7f7f7;
                border-radius: 3px;
                position: relative;
                margin-bottom: 6px;

                .name {
                    display: inline-block;
                    padding: 9px 0 2px 10px;

                    small {line-height: 1}
                    p {color: #585858}
                }

                .buttons {
                    position: absolute;
                    top: 0;
                    right: 0;

                    ul.button-list {
                        li.button-list-item {
                            display: inline-block;
                            background-color: #fff;
                            padding: 0.8rem 1.2rem;
                            cursor: pointer;

                            i {
                                font-size: 1.4rem;
                                vertical-align: middle;
                                transition: .1s;
                                color: #6b6f82;
                            }

                            &.active {
                                background-color: #a3dbff;
                            }

                            &:first-child {
                                background-color: transparent !important;
                            }

                            &:last-child {
                                border-top-right-radius: 3px;
                                border-bottom-right-radius: 3px;
                            }
                        }
                    }
                }
                
                &:hover {
                    background-color: #f3f2f2;
                }

                &.active {
                    background-color: #a3dbff;
                    color: #fff !important;
                    .name {
                        p {color: #fff !important}
                    }
                }

            }
        }
    }
</style>