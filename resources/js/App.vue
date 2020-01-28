<template>
    <div class="wrap">

        <!-- Main content -->
        <div class="wrap-content">

            <!-- Header -->
            <Header></Header>

            <!-- Menu -->
            <Menu></Menu>

            <!-- Content -->
            <div class="app-content content">
                <router-view></router-view>
            </div>

        </div>

        <!-- notify container -->
        <vue-snotify></vue-snotify>

        <!-- Popup -->
        <Popup></Popup>

        <!-- Loader -->
        <Loader :show="loading" :full="false"></Loader>

    </div>
</template>

<script>
    import Loader from '@/components/Loader';
    import Header from '@/components/Header';
    import Menu from '@/components/Menu';
    import Popup from '@/components/Popup';
    export default {
        name: "App",
        components: {
            Header,
            Menu,
            Popup,
            Loader
        },
        mounted() {

            // Приложение выходит из состояния загрузки через 3 секунды,
            // после загрузке в браузере.
            setTimeout(() => {
                // this.$store.dispatch('ENABLE_LOADING', false);
            }, 2000);
        },
        computed: {
            loading: function() {
                return this.$store.state.ui.loading;
            }
        },
        watch: {
            loading() {
                if (this.loading) document.body.classList.add("overflow-hidden");
                else document.body.classList.remove("overflow-hidden");
            }
        }
    }
</script>

<style lang="scss">
    @import "~vue-snotify/styles/simple";
    @import "vue-select/src/scss/vue-select.scss";
    @import '~pretty-checkbox/src/pretty-checkbox.scss';
    @import "~vue-directive-tooltip/dist/vueDirectiveTooltip.css";

    .vs__dropdown-toggle {
        padding: 4px 6px 9px 6px;
    }

    body {
        ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }
    }
</style>
