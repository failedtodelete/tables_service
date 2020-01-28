<template>
    <div class="row">
        <div class="col-12 col-md-6 col-lg-4 col-xl-3" v-for="page in pages">
            <div class="card card-page">
                <div class="card-body p-0">
                    <router-link :to="{ name: page.route }" class="p-1 d-block">
                        <h5 class="title">{{ page.name }}</h5>
                        <small>{{ page.description }}</small>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "index",
        data: function() {
            return {
                pages: {
                    permissions: {
                        icon: '',
                        name: 'Роли и разрешения',
                        description: 'Установка разрешений для разных ролей',
                        route: 'settings.permissions'
                    }
                }
            }
        }
    }
</script>

<style lang="scss">
    .card-page {
        cursor: pointer;
        transition: .1s;

        &:hover {
            background-color: #e9f5ff;
        }
        a, a:hover {
            color: #6b6f82;
        }
    }
</style>