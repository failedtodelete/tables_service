import SettingsLayout from '@/pages/settings/layout';
import SettingsIndex from '@/pages/settings/index';
import SettingsPermissions from '@/pages/settings/settings.permissions';

export default {
    path: '/settings',
    component: SettingsLayout,
    meta: { breadcrumb: 'Настройки' },
    children: [
        { name: 'settings', path: '', component: SettingsIndex },
        { name: 'settings.permissions', path: 'permissions', component: SettingsPermissions, meta: { breadcrumb: 'Разрешения' }}
    ]
}