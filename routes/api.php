<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
*/

Route::group(['middleware' => ['auth:api'], 'namespace' => 'API'], function() {

    // ПРОФИЛЬ ТЕКУЩЕГО ПОЛЬЗОВАТЕЛЯ.
    Route::group(['prefix' => 'profile'], function() {
        Route::get('/', 'ProfileController@index');                      // Просмотр профайла
        Route::get('/tables', 'ProfileController@tables');               // Получение таблиц пользователя
    });

    // ПОЛЬЗОВАТЕЛИ
    Route::group(['prefix' => 'users'], function() {

        // РОЛИ И РАЗРЕШЕНИЯ
        Route::get('permissions', 'PermissionController@index');        // Просмотр всех разрешений
        Route::group(['prefix' => 'roles'], function() {
            Route::get('/', 'RoleController@index');                                        // Просмотр всех ролей
            Route::post('/{id}/toggle_permission', 'RoleController@toggle_permission');     // Добавление/удаление разрешения у роли
        });

        Route::get('', 'UserController@index');                         // Просмотр всех пользователей
        Route::post('', 'UserController@store');                        // Добавление пользователя
        Route::get('/{id}', 'UserController@show');                     // Просмотр пользователя
        Route::put('/{id}', 'UserController@update');                   // Обновление пользователя
    });

    Route::post('tables/{id}/add_row', 'TableController@add_row');      // Добавление строки таблицы
    Route::post('tables/{id}/update_row', 'TableController@update_row');// Обновление строки таблицы.
    Route::resource('tables', 'TableController');
});

