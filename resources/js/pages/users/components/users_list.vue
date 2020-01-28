<template>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Список пользователей</h4>
            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"/></a>
        </div>
        <div class="card-content">
            <div class="table-responsive">
                <table class="table table-hover table-xl mb-0">
                    <tr>
                        <th class="border-top-0">Имя</th>
                        <th class="border-top-0">Информация</th>
                    </tr>
                    <tr v-for="user in users.slice().reverse()" @click="showEditForm(user)">
                        <td class="text-truncate">
                            <small class="d-block">{{ user.position }}</small>
                            <span class="font-weight-bold">{{ user.name }} {{ user.last_name }}</span>
                        </td>
                        <td class="text-truncate">
                            <small class="d-block">{{ user.role.display_name }}</small>
                            <span>{{ user.email }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "users_list",
        props: {
            users: Array
        },
        methods: {
            showEditForm(user) {
                this.$modal.show('user.edit', {user});
            }
        }
    }
</script>

<style scoped>

</style>
