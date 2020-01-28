<template>
    <div class="settings-permissions-page">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="list-group">
                            <a href="javascript:void(0)" class="list-group-item"
                               v-for="(role, index) in roles"
                               v-bind:key="role.id"
                               :class="{ active: active_index === index }"
                               @click="set_active_role(index)">{{ role.display_name }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card" v-for="type in permissions_groups">
                    <div class="card-body p-0">
                        <div class="row permission-wrap m-0">
                            <div class="col-6 col-lg-6 col-xl-4 p-0" v-for="permission in type.permissions">
                                <div class="permission" v-bind:class="{active: permission.active}" :key="permission.id" @click="change_access_permission(permission.id)">
                                    <small>{{ permission.name }}</small>
                                    <p class="m-0">{{ permission.display_name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import store from '@/store/index';
    export default {
        name: "permissions",

        data: function() {
            return {
                roles: [],
                permissions: [],
                total_permissions: [],
                active_index: undefined
            }
        },

        methods: {

            /**
             * Смена активной роли.
             */
            set_active_role(index) {
                this.active_index = index;
                this.update_total_permissions();
            },

            /**
             * Изменения возможности использования разрешения для роли.
             * Если разрешению включено - оно будет деактивированно.
             * @param id
             */
            change_access_permission(id) {

                try {

                    let permission = this.total_permissions.find(p => p.id === id);
                    this.$store.dispatch('TOGGLE_PERMISSION_ROLE', {
                        role_id: this.roles[this.active_index].id,
                        permission_id: permission.id
                    }).then(role => {

                        // Если текущий пользователь имеет данную роль.
                        // Необходимо обновить его разрешения.
                        this.$store.dispatch('UPDATE_USER_ROLE', role);
                        this.update_total_permissions()
                    });

                } catch (e) {
                    this.$snotify.error(e.message);
                    throw e;
                }
            },

            /**
             * Обновление всех разрешений.
             * Сбор разрешений у роли и смешивание со всеми доступными разрешениями.
             */
            update_total_permissions() {
                this.total_permissions = [];
                this.permissions.forEach((permission) => {
                    let access_permission = this.roles[this.active_index].permissions.data.find(rp => rp.id === permission.id);
                    access_permission ? permission.active = true : permission.active = false;
                    this.total_permissions.push(permission);
                });
            }
        },

        /**
         * Презагрузка доступных ролей и разрешений.
         * @param to
         * @param from
         * @param next
         */
        beforeRouteEnter :function(to, from, next) {
            Promise.all([store.dispatch('FETCH_ROLES'), store.dispatch('FETCH_PERMISSIONS')])
                .then(() => {
                    next();
                });
        },

        /**
         * После старта компонента - из хранилища берутся
         * доступные роли и разрешения.
         * Массив данных это ссылка.
         * При изменении данных роли, она обновляется на сервере, так же обновляется у хранилища - соответственно и здесь.
         */
        created: function() {
            this.roles = this.$store.state.roles.roles;
            this.permissions = this.$store.state.roles.permissions;
            this.set_active_role(0);
        },

        computed: {

            /**
             * Группировка разрешений по типу. */
            permissions_groups: function() {
                let types = {};
                if (!this.total_permissions.length) return types;

                this.total_permissions.forEach((permission) => {
                    if (!(permission.type.name in types)) {
                        types[permission.type.name] = permission.type;
                        types[permission.type.name].permissions = [];
                    }
                    types[permission.type.name].permissions.push(permission);
                });

                return types;
            }
        }
    }
</script>

<style lang="scss">
    .settings-permissions-page {
        .permission-wrap {
            padding: 4px;
            .permission {
                border-collapse: collapse;
                padding: 6px 12px;
                margin: 4px;
                border-radius: 3px;
                cursor: pointer;
                transition: .1s;

                &:hover {
                    background-color: #f5f5f5;
                    /*color: #fff;*/
                }
                &.active {
                    background-color: green;
                    color: #fff;
                }
            }
        }
    }
</style>