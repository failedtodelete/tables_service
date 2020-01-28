<template>
    <div class="page" id="table">

        <!-- Отображение таблицы -->
        <div class="table" v-if="Object.keys(table).length">
            <div class="row">
                <div class="col-12">
                    <div class="card card-table" v-bind:class="{ 'card-fullscreen': tableFullScreen}">
                        <div class="card-header">
                            <h4 class="card-title text-uppercase font-medium-2">{{ table.name }}</h4>
                            <div class="description"><p class="m-0 font-small-3 text-uppercase">{{ table.description }}</p></div>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3" @></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li v-if="makeAddingRow()" @click="creationRow = !creationRow" v-bind:class="{ active: creationRow }"><a><i class="ft-plus"></i></a></li>
                                    <li @click="tableFullScreen = !tableFullScreen" v-bind:class="{ active: tableFullScreen }"><a><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show overflow-x-scroll">

                            <!-- Обертка основной таблицы -->
                            <div class="table-responsive">

                                <!--Форма для создания новой строки-->
                                <form v-on:submit.prevent="add" id="add_row"/>

                                <!-- Основная таблица -->
                                <table class="table table-bordered mb-0 tables">

                                    <!-- Создание строки -->
                                    <template v-if="makeAddingRow() && creationRow">
                                        <tr id="creation">
                                            <td v-for="(column, index) in table.columns" class="p-0">
                                                <textarea class="form-control font-small-2" rows="2" form="add_row" :name="`columns[${index}]`"/>
                                            </td>
                                            <td class="p-0">
                                                <div class="buttons">
                                                    <ul>
                                                        <li><button type="submit" form="add_row" class="la la-save border-0 info"></button></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>

                                    <!-- Строка заголовков -->
                                    <tr>
                                        <th v-for="column in table.columns" class="p-1">
                                            <div v-bind:style="{ minWidth: column.width + 'px', maxWidth: column.width + 'px' }" style="white-space: normal;">
                                                <div class="font-small-3 text-uppercase">{{ column.name }}</div>
                                                <i class="la la-info-circle" v-tooltip.top.start="column.description" v-if="column.description"></i>
                                            </div>
                                        </th>
                                        <th class="p-1"><div class="font-small-3 text-uppercase" style="min-width:150px;">Создатель</div></th>
                                    </tr>

                                    <template v-for="(row, index) in rows" v-if="rows.length">

                                        <!-- Строка заголовков -->
                                        <tr v-if="index !== 0 && Number.isInteger(index / 8)">
                                            <th v-for="column in table.columns" class="p-1">
                                                <div v-bind:style="{ minWidth: column.width + 'px', maxWidth: column.width + 'px' }" style="white-space: normal;">
                                                    <div class="font-small-3 text-uppercase">{{ column.name }}</div>
                                                </div>
                                            </th>
                                            <th class="p-1"><div class="font-small-3 text-uppercase" style="min-width:150px;">Создатель</div></th>
                                        </tr>

                                        <!-- Строка данных -->
                                        <tr>
                                            <td v-for="(value, index) in row.values" class="p-0" @dblclick="editColumn(row, index, value)" v-bind:class="{ edit: editingValue.enable && editingValue.rowID == row.id && editingValue.valueIndex == index}">
                                                <div class="edition-value" v-if="editingValue.enable && editingValue.rowID == row.id && editingValue.valueIndex == index">
                                                    <textarea class="font-small-2 p-1" rows="4" v-model="editingValue.value">{{ editingValue.value }}</textarea>
                                                    <div class="btn-group btn-group-sm" style="margin: 2px;">
                                                        <button type="button" class="btn btn-sm btn-secondary" @click="editingValue.enable = false"><i class="la la-close"></i></button>
                                                        <button type="button" class="btn btn-sm btn-success" @click="saveColumn()"><i class="la la-save"></i></button>
                                                    </div>
                                                </div>
                                                <div class="value p-1" v-else>
                                                    <p class="m-0 font-small-2">{{ value }}</p>
                                                </div>
                                            </td>
                                            <td class="p-1"><p class="m-0 font-small-2">{{ moment(row.created_at).format('YYYY-MM-DD HH:mm') }} <br> {{ row.creator.last_name }} {{ row.creator.name }}</p></td>
                                        </tr>

                                    </template>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <TableEditionForm v-if="$can('tables.update')"
             :open="editionFormOpen"
             :table="table"
             @toggle="editionFormOpen = !editionFormOpen"
             @updated="updated"
        />

    </div>
</template>

<script>
    import Loader from '@/components/Loader';
    import TableEditionForm from '@/customizers/table.edition';
    import Axios from 'axios';
    export default {
        name: "show",
        components: {Loader, TableEditionForm},
        data() {
            return {
                table: {},
                tableFullScreen: false,
                editionFormOpen: false,
                creationRow: false,

                editingValue: {
                    rowID: undefined,
                    valueIndex: undefined,
                    enable: false,
                    value: ''
                }
            }
        },
        methods: {

            /**
             * Редактирование колонки.
             * */
            editColumn(row, valueIndex, text) {
                if (this.$can('tables.update')) {
                    this.editingValue = {
                        rowID: row.id,
                        valueIndex: valueIndex,
                        value: text,
                        enable: true
                    };
                }
            },

            /**
             * Сохранение колонки.
             * */
            saveColumn() {
                let data = {row_id: this.editingValue.rowID, data: []};
                data.data.push({
                    index: this.editingValue.valueIndex,
                    value: this.editingValue.value
                });
                Axios.post(`tables/${this.table.id}/update_row`, data)
                    .then(row => {
                        // Замена строки в таблице.
                        let index = this.rows.findIndex(r => r.id === row.id);
                        if (index !== -1) this.rows[index] = row;

                        this.$snotify.success('Строки успешно обновлена');
                        this.editingValue.enable = false;
                    })
            },

            /**
             * Добавление новой строки.
             * Получение данных от формы и отправка запроса.
             * @param event FormEvent
             */
            add(event) {
                let formData = new FormData(event.target);
                Axios.post(`tables/${this.table.id}/add_row`, formData)
                    .then(row => {
                        this.$snotify.success('Строка успешно добавлена');
                        this.table.rows.data.push(row);
                        this.creationRow = false;
                        event.target.reset();
                    });
            },

            /**
             * Получение возможности добавление строки в таблицу.
             * */
            makeAddingRow() {
                return this.$can('tables.update') ||
                    this.table.users.data && this.table.users.data.find(u => u.id === this.profile.id && u.pivot.adding_row);
            },

            /**
             * Обновление таблицы.
             * @param table
             */
            updated(table) {
                this.table = table;
            },

        },
        computed: {
            profile() {
                return this.$store.state.user.profile;
            },
            rows() {
                return JSON.parse(JSON.stringify(this.table.rows.data)).reverse();
            }
        },
        async created() {

            // Отображение загрузки страницы.
            this.$store.dispatch('ENABLE_LOADING', true);

            // Отображение загрузочного экрана.
            this.loading = true;

            // Получение таблицы.
            this.table = await Axios.get(`tables/${this.$route.params.id}`).then(table => {return table});

            // Отключение загрузочного экрана.
            setTimeout(() => {this.$store.dispatch('ENABLE_LOADING', false)}, 1000);

        }
    }
</script>

<style lang="scss">
    .page#table {
        .heading-elements {
            li {
                &.active {
                    color: #666ee8;
                }
            }
        }
        .card {
            max-height: 75vh;
            overflow: hidden;
        }
        .card.card-fullscreen {
            max-height: 100vh;
            overflow: auto;
        }
        table.tables {
            tr {

                // Создание строки.
                &#creation {
                    td {
                        textarea {
                            border: none;
                            resize: none;
                            width: 100%;
                            height: 100%;
                        }
                        .buttons {
                            ul {
                                li {
                                    button {
                                        font-size: 2rem;
                                        padding: 1rem;
                                        outline: none;
                                    }
                                }
                            }
                        }
                    }
                }


                th {
                    background-color: #f4f5fa;
                    color: #5a5a5a;
                }
                td {
                    &.edit {
                        border: 2px solid #1f9ff2;
                        textarea {
                            border: none;
                            resize: none;
                            padding: 1rem;
                            width: 100%;
                            height: 100%;
                            outline: none;
                        }
                    }
                }
            }
        }
    }
</style>
