<template>
    <div class="page" id="profile">

        <!-- Список таблиц -->
        <TableList :tables="tables"></TableList>

        <!-- Создание таблицы -->
        <CreateCustomizer
                v-if="$can('tables.create')"
                :open="createCustomizerShow"
                @toggle="createCustomizerShow = !createCustomizerShow"
                @created="addTable"
        ></CreateCustomizer>

    </div>
</template>

<script>
    import Loader from '@/components/Loader';
    import Axios from 'axios';
    import TableList from './components/table.list';
    import CreateCustomizer from '@/customizers/table.creation';
    export default {
        name: "index",
        components: {CreateCustomizer, TableList, Loader},
        data() {
            return {
                tables: [],
                // loading: true,
                createCustomizerShow: false
            }
        },
        computed: {

            // Получение профиля текущего пользователя.
            profile() {
                return this.$store.state.user.profile;
            }
        },
        methods: {
            // Добавление созданной таблицы на страницу.
            addTable(table) {this.tables.push(table)}
        },

        async created() {

            this.$store.dispatch('ENABLE_LOADING', true);

            // Получение всех таблиц.
            this.tables = await Axios.get('tables').then(tables => {return tables.data});
            setTimeout(() => {this.$store.dispatch('ENABLE_LOADING', false)}, 1000);
        }
    }
</script>

<style scoped>

</style>
