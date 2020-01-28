<?php

namespace App\Policies;

use App\Models\Table;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TablePolicy
{
    use HandlesAuthorization;

    /**
     * Получение всех таблиц.
     * @param User $user
     * @return bool
     */
    public function index(User $user)
    {
        return $user->access('tables.index');
    }

    /**
     * Создание таблицы.
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->access('tables.create');
    }

    /**
     * Просмотр таблицы.
     * @param User $user
     * @param Table $table
     * @return bool
     */
    public function show(User $user, Table $table)
    {
        return (bool) $user->access('tables.show') ||
            $table->users->find($user->id);
    }

    /**
     * Обновление данных таблицы.
     * @param User $user
     * @return bool
     */
    public function update(User $user)
    {
        return $user->access('tables.update');
    }

    /**
     * Добавление строки в таблицу.
     * @param User $user
     * @param Table $table
     * @return bool
     */
    public function add_row(User $user, Table $table)
    {
        return (bool) $user->access('tables.update') ||
            $table->users()->where('id', $user->id)
                ->wherePivot('adding_row', true)
                ->get()
                ->count();
    }

    /**
     * Удаление таблицы.
     * @param User $user
     * @return bool
     */
    public function delete(User $user)
    {
        return $user->access('tables.delete');
    }

}
