<?php

use Illuminate\Database\Seeder;
use App\Facades\RoleServiceFacade as RoleService;
use App\Facades\PermissionServiceFacade as PermissionService;

class Roles extends Seeder
{

    /**
     * Роли пользователей.
     * @var array
     */
    protected $roles = [
        ['name' => 'admin', 'display_name' => 'Администратор', 'description' => ''],
        ['name' => 'user', 'display_name' => 'Пользователь', 'description' => '']
    ];

    /**
     * Типы разрешений.
     * @var array
     */
    protected $permissionTypes = [
        ['id' => 1,     'name' => 'Users',              'display_name' => 'Пользователи'],
        ['id' => 100,   'name' => 'Settings',           'display_name' => 'Настройки системы'],
        ['id' => 150,   'name' => 'Tables',             'display_name' => 'Таблицы']
    ];

    /**
     * Разрешения.
     * @var array
     */
    protected $permissions = [

        // Пользователи
        ['type_id' => 1, 'name' => 'users.index',       'display_name' => 'Просмотр всех пользователей', 'main' => true],
        ['type_id' => 1, 'name' => 'users.create',      'display_name' => 'Создание пользователя'],
        ['type_id' => 1, 'name' => 'users.show',        'display_name' => 'Просмотр пользователя'],
        ['type_id' => 1, 'name' => 'users.update',      'display_name' => 'Обновление пользователя'],

        // Настройки системы
        ['type_id' => 100, 'name' => 'settings.index',          'display_name' => 'Настройки системы', 'main' => true],
        ['type_id' => 100, 'name' => 'settings.roles.update',   'display_name' => 'Управление ролями и разрешениями'],

        // Таблицы
        ['type_id' => 150, 'name' => 'tables.index',    'display_name' => 'Просмотр всех таблиц'],
        ['type_id' => 150, 'name' => 'tables.create',   'display_name' => 'Создание таблицы'],
        ['type_id' => 150, 'name' => 'tables.show',     'display_name' => 'Просмотр таблицы'],
        ['type_id' => 150, 'name' => 'tables.update',   'display_name' => 'Обновление данных таблицы'],
        ['type_id' => 150, 'name' => 'tables.delete',   'display_name' => 'Удаление таблицы']

    ];

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $table_create = [
            'name',
            'users' => [
                'id' => 1,
                'make_add' => true
            ]
        ];

        // Создание ролей.
        $roles = new \Illuminate\Database\Eloquent\Collection();
        foreach($this->roles as $role) {
            $role = RoleService::store($role);
            $roles->add($role);
        }

        // Создание типов разрешений.
        foreach($this->permissionTypes as $permissionType)
            PermissionService::permissionTypeStore($permissionType);

        // Создание разрешений и присвоение ролям.
        foreach($this->permissions as $permission) {
            $pm = PermissionService::store(
                [
                    'type_id'       => $permission['type_id'],
                    'name'          => $permission['name'],
                    'display_name'  => $permission['display_name']
                ]
            );

            $role = $roles->where('name', '=', $roles[0]['name'])->first();
            $role->permissions()->attach($pm);

        }
    }
}
