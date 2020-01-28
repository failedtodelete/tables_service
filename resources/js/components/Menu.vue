]<template>
    <div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

                <!-- Пользовательское -->
                <li>
                    <router-link :to="{ name: 'profile' }" exact>
                        <i class="la la-home"></i>
                        <span class="menu-title">Главная</span>
                    </router-link>
                </li>

                <!-- Основное -->
                <li class="navigation-header" v-permission="{ type: 'anyOf', list: ['users.index', 'settings.index', 'public.sites.index']}"><span>ОСНОВНОЕ</span></li>

                <li v-permission="'users.index'">
                    <router-link :to="{ name: 'users' }">
                        <i class="la la-user-secret"></i>
                        <span class="menu-title">Пользователи</span>
                    </router-link>
                </li>

                <li v-permission="'settings.index'">
                    <router-link :to="{ name: 'settings' }">
                        <i class="la la-puzzle-piece"></i>
                        <span class="menu-title">Настройки</span>
                    </router-link>
                </li>

            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Menu"
    }
</script>

<style lang="scss">
</style>
