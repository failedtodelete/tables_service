<?php

namespace App\Http\Controllers\API;

use App\Models\Table;
use App\Transformers\TableRowTransformer;
use App\Transformers\TableTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Facades\TableServiceFacade as TableService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class TableController extends Controller
{

    /**
     * Получение всех таблиц.
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {

        // Если пользователь имеет доступ к просмотру всех таблиц - отображение всех,
        // если нет - отображение только тех, которые ему доступны.
        if (Auth::user()->access('tables.index')) $tables = TableService::all();
        else $tables = Auth::user()->tables;

        return fractal()
            ->collection($tables)
            ->transformWith(new TableTransformer())
            ->toJson();
    }

    /**
     * Создание таблицы.
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        // Проверка прав доступа.
        $this->authorize('create', Table::class);

        // Создание и возврат таблицы.
        $table = TableService::store($request->all());
        return fractal()
            ->item($table)
            ->transformWith(new TableTransformer())
            ->toJson();
    }

    /**
     * Обновление таблицы.
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, $id)
    {
        // Проверка прав доступа.
        $this->authorize('update', Table::class);

        // Создание и возврат таблицы.
        $table = TableService::update($request->all(), $id);
        return fractal()
            ->item($table)
            ->transformWith(new TableTransformer())
            ->toJson();
    }

    /**
     * Получение таблицы.
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Request $request, $id)
    {
        // Проверка прав доступа.
        $table = TableService::findOrFail($id);
        $this->authorize('show', $table);

        // Возврат таблицы.
        return fractal()
            ->item($table)
            ->transformWith(new TableTransformer())
            ->toJson();
    }

    /**
     * Добавление строки в таблицу.
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function add_row(Request $request, $id)
    {
        // Проверка прав доступа.
        $table = TableService::findOrFail($id);
        $this->authorize('add_row', $table);

        $row = TableService::add_row($request->all(), $id);
        return fractal()
            ->item($row)
            ->transformWith(new TableRowTransformer())
            ->toJson();

    }

    /**
     * Обновление строки в таблице.
     * @param Request $request
     * @param $id
     * @return string
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update_row(Request $request, $id)
    {
        // Проверка прав доступа.
        $this->authorize('update', Table::class);
        $row = TableService::update_row($request->all(), $id);
        return fractal()
            ->item($row)
            ->transformWith(new TableRowTransformer())
            ->toJson();
    }

    /**
     * Удаление таблицы.
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Request $request, $id)
    {
        // Проверка прав доступа.
        $this->authorize('delete', Table::class);

        // Удаление таблицы и возврат статуса.
        TableService::delete($id);
        return Response::json(['status' => true]);
    }

}
