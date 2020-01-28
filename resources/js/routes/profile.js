import ProfileLayout from '@/pages/profile/layout';
import ProfileIndex from '@/pages/profile/index';

export default {
    path: '/profile',
    component: ProfileLayout,
    meta: {breadcrumb: 'Профиль'},
    children: [
        { name: 'profile', path: '', component: ProfileIndex }
    ]
}
