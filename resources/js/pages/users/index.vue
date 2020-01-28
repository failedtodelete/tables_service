<template>
    <div class="content-wrapper">
        <div class="content-header row mb-1">
            <breadcrumbs/>
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-12">
                    <UsersList v-permission="'users.index'" :users="users"/>
                </div>
            </div>
        </div>

        <UserCreationCustomizer :open="creationCustomizerShow" @created="addUser" @toggle="creationCustomizerShow = !creationCustomizerShow"/>
    </div>
</template>

<script>
    import Axios from 'axios';
    import UserCreationCustomizer from '@/customizers/user.creation';
    import UsersList from './components/users_list';
    export default {
        name: "index",
        components: {
            UsersList,
            UserCreationCustomizer
        },
        data: function() {
            return {
                users: [],
                creationCustomizerShow: false
            }
        },
        async created() {

            this.$store.dispatch('ENABLE_LOADING', true);

            // Получение пользователей.
            this.users = await Axios.get('users').then(users => {return users.data});

            setTimeout(() => {this.$store.dispatch('ENABLE_LOADING', false)}, 1000);

        },
        methods: {
            addUser(user) {
                this.users.push(user);
            }
        }
    }
</script>

<style scoped>
</style>
