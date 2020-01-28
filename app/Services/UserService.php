<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Facades\RoleServiceFacade as RoleService;

use App\Models\User;

class UserService extends BaseService
{

    /**
     * UserService constructor.
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Создание пользователя.
     * @param $data
     * @return mixed
     * @throws ValidationException
     */
    public function store($data)
    {

        $rules = [
            'name'      => 'required|string',
            'last_name' => 'required|string',
            'position'  => 'required|string',
            'email'     => 'required|string|unique:users,email',
            'password'  => 'required|string|min:6',
            'role'      => 'nullable|string'
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) throw new ValidationException($validator);

        // Получение роли.
        $role = RoleService::where('display_name', '=', $data['role'])->first();
        if (!$role) throw new CustomException('Роль не найдена');

        // Создание пользователя.
        return User::create([
            'name'      => $data['name'],
            'last_name' => $data['last_name'],
            'position'  => $data['position'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'role_id'   => $role->id,
            'api_token' => md5(rand())
        ]);
    }

    /**
     * Обновление данных пользователя.
     * @param $data
     * @param $id
     * @return mixed
     * @throws ValidationException
     * @throws \App\Exceptions\CustomException
     */
    public function update($data, $id)
    {
        // Получение пользователя.
        $user = self::findOrFail($id);

        $rules = [
            'name'      => 'nullable|string',
            'last_name' => 'nullable|string',
            'position'  => 'nullable|string',
            'email'     => 'nullable|string|unique:users,email,' . $user->id,
            'password'  => 'nullable|string|min:6'
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) throw new ValidationException($validator);

        // Обновление имени.
        if (key_exists('name', $data)) $user->name = $data['name'];

        // Обновление фамилии.
        if (key_exists('last_name', $data)) $user->last_name = $data['last_name'];

        // Обновление должности
        if (key_exists('position', $data)) $user->position = $data['position'];

        // Обновление email
        if (key_exists('email', $data)) $user->email = $data['email'];

        // Обновление пароля.
        if (key_exists('password', $data)) $user->password = Hash::make($data['password']);

        // Сохранение модели пользователя.
        $user->save();
        return $user;
    }

}
