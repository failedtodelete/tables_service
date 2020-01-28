import Vue from "vue";
import Router from "vue-router";

// Pages
import ProfileRouter from './profile';
import UsersRouter from './users';
import SettingsRouter from './settings';
import TablesRouter from './tables';

Vue.use(Router);

const router = new Router({
    mode: 'history',
    routes: [
        {name: "index", path: '/', redirect: {name: 'profile'}},
        ProfileRouter,
        UsersRouter,
        SettingsRouter,
        TablesRouter
    ]
});

export default router;
