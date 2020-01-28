<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Models\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TableService extends BaseService
{

    /**
     * TableService constructor.
     * @param Table $model
     */
    public function __construct(Table $model)
    {
        parent::__construct($model);
    }

    /**
     * Создание таблицы.
     * @param $data
     * @return mixed
     * @throws ValidationException
     */
    public function store($data)
    {
        $rules = [
            'name'                  => 'required|string',
            'description'            => 'nullable|string',
            'users'                 => 'required|array|min:1',
            'users.*.id'            => 'required|numeric|exists:users,id',
            'users.*.adding_row'    => 'required|boolean',
            'columns'               => 'required|array|min:1',
            'columns.*.name'        => 'required|string',
            'columns.*.width'       => 'required|numeric|min:1',
            'columns.*.description' => 'required|string'
        ];
        $validator = Validator::make($data, $rules, [
            'name.required'     => 'Не указано название таблицы',
            'name.string'       => 'Название таблицы должно быть строкой',
            'users.required'    => 'Не выбраны пользователи',
            'users.array'       => 'Переданные пользователи должны являться массивом',
            'users.min'         => 'Недопустимое количество пользователей',
            'columns.required'  => 'Не переданы колонки таблицы',
            'columns.min'       => 'Недопустимое количество колонок в таблице',
        ]);
        if ($validator->fails()) throw new ValidationException($validator);

        // Создание таблицы.
        $table = Table::create([
            'name' => $data['name'],
            'description' => key_exists('description', $data) ? $data['description']: null,
            'columns' => (string) serialize($data['columns']),
            'creator_id' => Auth::id(),
        ]);

        // Присоединение пользователей к таблице.
        foreach ($data['users'] as $user) {
            $table->users()->attach($user['id'], ['adding_row' => $user['adding_row']]);
        }

        return $table;
    }

    /**
     * Обновление таблицы.
     * @param $data
     * @param $id
     * @return mixed
     * @throws CustomException
     * @throws ValidationException
     */
    public function update($data, $id)
    {
        $rules = [
            'name'                  => 'required|string',
            'description'           => 'nullable|string',
            'users'                 => 'required|array|min:1',
            'users.*.id'            => 'required|numeric|exists:users,id',
            'users.*.adding_row'    => 'required|boolean',
            'columns'               => 'required|array|min:1',
            'columns.*.name'        => 'required|string',
            'columns.*.width'       => 'required|numeric|min:1',
            'columns.*.description' => 'required|string'
        ];
        $validator = Validator::make($data, $rules, [
            'name.required'     => 'Не указано название таблицы',
            'name.string'       => 'Название таблицы должно быть строкой',
            'users.required'    => 'Не выбраны пользователи',
            'users.array'       => 'Переданные пользователи должны являться массивом',
            'users.min'         => 'Недопустимое количество пользователей',
            'columns.required'  => 'Не переданы колонки таблицы',
            'columns.min'       => 'Недопустимое количество колонок в таблице',
        ]);
        if ($validator->fails()) throw new ValidationException($validator);


        // Получение существующей таблицы.
        $table = self::findOrFail($id);

        // Обновление названия.
        if (key_exists('name', $data)) $table->name = $data['name'];

        // Обновление описания.
        if (key_exists('description', $data)) $table->description = $data['description'];

        // Обновление колонок.
        if (key_exists('columns', $data)) $table->columns = (string) serialize($data['columns']);

        // Обновление пользователей.
        if (key_exists('users', $data)) {

            // Открепление всех пользователей от таблицы.
            $table->users()->detach();

            // Присоединение пользователей к таблице.
            foreach ($data['users'] as $user) {
                $table->users()->attach($user['id'], ['adding_row' => $user['adding_row']]);
            }
        }

        // Сохранение таблицы.
        $table->save();
        return $table;
    }

    /**
     * Создание строки.
     * @param $data
     * @param $table_id
     * @return mixed
     * @throws CustomException
     * @throws ValidationException
     */
    public function add_row($data, $table_id)
    {
        $rules = ['columns'   => 'required|array|min:1'];
        $validator = Validator::make($data, $rules, [
            'columns.required'  => 'В строке не переданы колонки',
            'columns.array'     => 'Строка имеет не верный тип',
            'columns.min'       => 'Переданно не верное количество элементов'
        ]);
        if ($validator->fails()) throw new ValidationException($validator);

        // Получение таблицы.
        $table = self::findOrFail($table_id);

        // Сравнивание количества колонок и количество елементов, которое пришор в запросе.
        $columns = unserialize($table->columns);
        if (count($columns) !== count($data['columns']))
            throw new CustomException('Переданно не верное количество элементов');

        // Создание строки ..
        return $table->rows()->create(['values' => serialize($data['columns']), 'creator_id' => Auth::id()]);
    }

    /**
     * Обновление строки.
     * @param $data
     * @param $table_id
     * @return mixed
     * @throws CustomException
     * @throws ValidationException
     */
    public function update_row($data, $table_id)
    {
        $rules = [
            'row_id'   => 'required|numeric|exists:table_rows,id',
            'data'   => 'required|array|min:1',
            'data.*.index' => 'required|numeric',
            'data.*.value' => 'required|string'
        ];
        $validator = Validator::make($data, $rules, [
            'columns.required'  => 'В строке не переданы колонки',
            'columns.array'     => 'Строка имеет не верный тип',
            'columns.min'       => 'Переданно не верное количество элементов'
        ]);
        if ($validator->fails()) throw new ValidationException($validator);

        // Получение строки текущей таблицы.
        $table = self::findOrFail($table_id);
        $row = $table->rows()->findOrFail($data['row_id']);

        // Декодирование строки.
        $values = unserialize($row->values);

        // Перебор переданых значений.
        // Преобразование общего массива данных текущей строки.
        foreach($data['data'] as $value) {
            $values[$value['index']] = $value['value'];
        }

        $row->values = serialize($values);
        $row->save();
        return $row;
    }

    /**
     * Удаление таблицы.
     * @param $id
     * @throws \App\Exceptions\CustomException
     */
    public function delete($id)
    {
        $table = self::findOrFail($id);
        $table->delete();
    }


}
