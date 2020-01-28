import TablesLayout from '@/pages/tables/layout';
import TablesShow from '@/pages/tables/show';

export default {
    path: '/tables',
    component: TablesLayout,
    meta: {breadcrumb: 'Таблицы'},
    children: [
        { name: 'tables-show', path: ':id', component: TablesShow, meta: { breadcrumb: 'Просмотр' }}
    ]
}
