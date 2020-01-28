<template>
    <Customizer :label="{ icon: 'ft-edit', bColor: 'bg-info' }" :open="open" @toggle="toggle">
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
                                <input type="text" class="form-control" v-model="source.name" required>
                            </div>
                        </fieldset>
                    </div>

                    <div class="customizer-section-header">
                        <h5>Описание таблицы</h5>
                        <small>Поле не обязательное к заполнению</small>
                    </div>
                    <div class="customizer-section-content">
                        <fieldset class="form-group">
                            <textarea class="form-control" rows="3" style="resize: none;" v-model="source.description"></textarea>
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
                                <li class="columns-list-item" v-for="(column, index) in source.columns">
                                    <div class="name">
                                        <small class="position d-block">Колонка {{ index + 1 }}</small>
                                        <p class="m-0">{{ column.name }} [{{ column.width }}]</p>
                                        <small>{{ column.description }}</small>
                                    </div>
                                    <div class="buttons float-right">
                                        <ul class="button-list">
                                            <li class="button-list-item" @click="$modal.show('table.column.create', {type: 'update', column: column})"><i class="la la-edit"></i></li>
                                            <!--<li class="button-list-item" @click="deleteColumn(index)"><i class="la la-minus-circle"></i></li>-->
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </fieldset>
                        <!--<div class="columns-adding-button">-->
                            <!--<button type="button" class="btn btn-sm btn-info btn-min-width" @click="$modal.show('table.column.create', {type: 'create'})">Создать</button>-->
                        <!--</div>-->
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
                                <li class="user-list-item" v-for="user in users" v-bind:class="{ active: source.users.find(u => u.id === user.id)}">
                                    <div class="name">
                                        <small class="position d-block">{{ user.position }}</small>
                                        <p class="m-0">{{ user.name }}</p>
                                    </div>
                                    <div class="buttons float-right">
                                        <ul class="button-list">
                                            <li class="button-list-item" @click="selectUser(user)" v-bind:class="{ active: source.users.find(u => u.id === user.id)}"><i class="la la-eye"></i></li>
                                            <li class="button-list-item" @click="selectAddRow(user)" v-bind:class="{ active: source.users.find(u => u.id === user.id && u.adding_row)}"><i class="la la-plus-square"></i></li>
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
                        <button class="btn btn-info" type="submit" style="width: 100%;">Обновить</button>
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
        name: "table.edition",
        components: {Customizer, Loader},
        props: {
            open: {
                type: Boolean,
                required: true
            },
            table: {
                type: Object,
                required: true
            }
        },
        data() {
            return {

                source: {
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

            // Обновление таблицы.
            async submit() {
                try {

                    // Отображение окна загрузки.
                    this.creation = true;

                    // Проверка созданных колонок таблицы.
                    if (!this.source.columns.length) throw new Error('У создаваемой таблицы должны быть колонки');

                    // Проверка присвоенных пользователей.
                    if (!this.source.users.length) throw new Error('У создаваемой таблицы должны быть пользователи');

                    // Отпрака запроса на создание таблицы.
                    await Axios.put(`tables/${this.table.id}`, this.source).then(table => {
                        setTimeout(() => {

                            // Отображение уведомления пользователю.
                            this.$snotify.success('Таблица успешно обновлена');

                            // Вызов события добавления таблицы.
                            this.$emit('updated', table);

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

            // Выбор пользователя на просмотр таблицы.
            selectUser(user, adding_row = false) {
                if (!this.source.users.length || !this.source.users.find(u => u.id === user.id)) {
                    this.source.users.push({id: user.id, adding_row: adding_row});
                } else {
                    let index = this.source.users.findIndex(u => u.id === user.id);
                    if (index !== -1) this.source.users.splice(index, 1);
                }
            },

            // Выбор пользователя на добавление строк в таблице.
            selectAddRow(user) {
                let index = this.source.users.findIndex(u => u.id === user.id);
                if (index === -1)this.selectUser(user, true);
                else this.source.users[index].adding_row = !this.source.users[index].adding_row;
            },

            // createColumn(column) {
            //     this.source.columns.push(column);
            // },

            // deleteColumn(index) {
            //     this.source.columns.splice(index, 1);
            // },

            // Очистка всех данных компонента.
            reset() {
                let defaultData = this.$options.data.apply(this);
                defaultData.users = this.users;
                Object.assign(this.$data, defaultData);
            },

            // Открытие / закрытие компонента
            toggle() {
                if (this.open) this.reset();
                else {

                    // this.$bus.on('create-column', this.createColumn);
                    this.source.name = this.table.name;
                    this.source.description = this.table.description;
                    this.source.columns = this.table.columns;
                    this.table.users.data.forEach((user) => {
                        this.source.users.push({
                            id: user.id,
                            adding_row: user.pivot.adding_row
                        })
                    });
                }
                this.$emit('toggle');
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
</style>