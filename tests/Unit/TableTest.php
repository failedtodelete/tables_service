<?php

namespace Tests\Unit;

use App\Models\Table;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TableTest extends TestCase
{

    /**
     * Получение всех таблиц.
     * Получение всех таблиц от имени пользователя, не имеющего разрешение.
     * Получение всех таблиц от имени пользователя, имеющего разрешение.
     */
    public function testIndexTables()
    {
        // Пользователь, который будет присоединен к таблице.
        factory(User::class)->create();

        // Создание таблиц.
        factory(Table::class, 15)->create();

        // Проверка возможности просмотра таблиц от имени пользователя, НЕ ИМЕЮЩЕГО разрешение.
        $response = $this->ajax('GET', '/api/tables', [], [], ['auth' => '!tables.index']);
        $response->assertStatus(200)->assertJson(['status' => false]);

        // Проверка возможности просмотра таблиц от имени пользователя, ИМЕЮЩЕГО разрешение.
        $response = $this->ajax('GET', '/api/tables', [], [], ['auth' => 'tables.index']);
        $response->assertStatus(200)
            ->assertJsonCount(15, 'data');

    }

    /**
     * Создание таблицы.
     * Проверка на возможность выполнения действия в зависимости от доступных разрешений пользователя.
     * Проверка на структуру.
     * Проверка установленного флага (добавление строки) у массива пользователей, имеющих доступ.
     */
    public function testCreationTable()
    {

        // Пользователи которые будут присоединены к таблице.
        factory(User::class, 2)->create();

        // Данные для создания.
        $table = [
            'name' => 'test_name',
            'columns' => ['field_1', 'field_2', 'field_3'],
            'users' => [
                ['id' => 2, 'adding_row' => false],
                ['id' => 3, 'adding_row' => true],
            ]
        ];

        // Проверка возможности создания таблицы от имени пользователя, не имеющего разрешение.
        $response = $this->ajax('POST', '/api/tables', $table, [], ['auth' => '!tables.create']);
        $response->assertStatus(200)->assertJson(['status' => false]);

        // Проверка возможности создания таблицы от имени пользователя, ИМЕЮЩЕГО разрешение.
        $response = $this->ajax('POST', '/api/tables', $table, [], ['auth' => 'tables.create']);
        $response->assertStatus(200)->assertJsonStructure([
                'id', 'name', 'creator',
                'users' => [
                    'data' => [
                        0 => [
                            'id',
                            'pivot' => [
                                'adding_row', 'created_at', 'updated_at'
                            ]
                        ]
                    ]
                ]
            ]);

        // Проверка что пользователь с ID 2 НЕ ИМЕЕТ доступ к добавлению данных в таблицу.
        // Проверка что пользователь с ID 3 ИМЕЕТ доступ к добавлению данных в таблицу.
        $response->assertJson([
            'users' => [
                'data' => [
                    0 => [
                        'pivot' => [
                            'adding_row' => false
                        ]
                    ],
                    1 => [
                        'pivot' => [
                            'adding_row' => true
                        ]
                    ]
                ]
            ]
        ]);

    }

    /**
     * Получение таблицы.
     * Получение таблицы от имени пользователя, присоединенного к таблице.
     * Получение таблицы от имени пользователя, НЕ ИМЕЮЩЕГО разрешения на просмотр.
     * Получение таблицы от имени пользователя, ИМЕЮЩЕГО разрешения на просмотр.
     */
    public function testShowTable()
    {
        // Пользователь, который будет присоединен к таблице.
        $user = factory(User::class)->create();

        // Создание таблиц.
        factory(Table::class, 2)->make()->each(function($table) use ($user) {
            $table->save();
            $table->users()->attach($user, ['adding_row' => true]);
        });

        // Проверка возможности просмотра таблицы от имени пользователя
        // НЕ ИМЕЮЩЕГО разрешение, но являющегося присоединенным пользователем к ней.
        $response = $this->ajax('GET', '/api/tables/1', [], [], ['auth_current' => $user->id]);
        $response->assertStatus(200)->assertJsonStructure(['id']);

        // Проверка возможности просмотра таблицы от имени пользователя НЕ ИМЕЮЩЕГО разрешение.
        $response = $this->ajax('GET', '/api/tables/1', [], [], ['auth' => '!tables.show']);
        $response->assertStatus(200)->assertJson(['status' => false]);

        // Проверка возможности просмотра таблицы от имени пользователя, ИМЕЮЩЕГО разрешение.
        $response = $this->ajax('GET', '/api/tables/1', [], [], ['auth' => 'tables.show']);
        $response->assertStatus(200)->assertJsonStructure(['id']);

    }

    /**
     * Удаление таблицы.
     * Попытка удаления таблицы от имени пользователя, НЕ ИМЕЮЩЕГО разрешения.
     * Попытка удаления таблицы от имени пользователя, ИМЕЮЩЕГО разрешение.
     */
    public function testDeleteTable()
    {

        // Пользователь, который будет присоединен к таблице.
        factory(User::class)->create();

        // Создание таблицы.
        factory(Table::class)->create();

        // Проверка возможности удаления таблицы от имени пользователя, НЕ ИМЕЮЩЕГО разрешение.
        $response = $this->ajax('DELETE', '/api/tables/1', [], [], ['auth' => '!tables.delete']);
        $response->assertStatus(200)->assertJson(['status' => false]);

        // Проверка возможности удаления таблицы от имени пользователя, ИМЕЮЩЕГО разрешение.
        $response = $this->ajax('DELETE', '/api/tables/1', [], [], ['auth' => 'tables.delete']);
        $response->assertStatus(200)->assertJson(['status' => true]);

    }

    /**
     * Добавление строк в таблицы.
     * Проверка возможности создания от имени пользователя, имеющего доступ к добавлению и присутствующего среди участников.
     * Проверка передачи иного количества аргументов в columns.
     * Проверка возможности создания от имени пользователя, НЕ ИМЕЮЩЕГО доступ к добавлению и присутствующего среди участников.
     * Проверка возможности создания от имени пользователя, НЕ ИМЕЮЩЕГО доступ к добавлению и НЕ присутствующего среди участников.
     * Проверка возможности создания от имени пользователя, ИМЕЮЩЕГО доступ к добавлению и НЕ присутствующего среди участников.
     */
    public function testAddRowToTable()
    {

        // Создание польльзователя, который НЕ СМОЖЕТ создавать строку к таблице.
        $user = factory(User::class)->create();

        // Создание пользователя, который сможет создавать строку к таблице.
        $user2 = factory(User::class)->create();

        // Создание таблицы.
        factory(Table::class, 1)->make()->each(function($table) use ($user, $user2) {
            $table->save();
            $table->users()->attach($user, ['adding_row' => false]);
            $table->users()->attach($user2, ['adding_row' => true]);
        });

        // Проверка возможности создания строки в таблице от имени пользователя,
        // ИМЕЮЩЕГО разрешение и присутствующего среди участников.
        $response = $this->ajax('POST', '/api/tables/1/add_row', [
            'columns' => ['first', 'second']
        ], [], ['auth_current' => $user2->id]);
        $response->assertStatus(200)->assertJsonStructure(['id', 'values', 'created_at', 'updated_at']);

        // Проверка передачи иного количества аргументов в columns.
        $response = $this->ajax('POST', '/api/tables/1/add_row', [
            'columns' => ['first']
        ], [], ['auth_current' => $user->id]);
        $response->assertStatus(200)->assertJson(['status' => false]);

        // Проверка возможности создания строки в таблице от имени пользователя,
        // НЕ ИМЕЮЩЕГО разрешение, но присутствующего среди участников.
        $response = $this->ajax('POST', '/api/tables/1/add_row', [
            'columns' => ['first', 'second']
        ], [], ['auth_current' => $user->id]);
        $response->assertStatus(200)->assertJson(['status' => false]);

        // Проверка возможности создания строки в таблице от имени пользователя,
        // НЕ ИМЕЮЩЕГО разрешение, и НЕ присутствующего среди участников,
        // не имеющего прав редактирования таблиц.
        $response = $this->ajax('POST', '/api/tables/1/add_row', [
            'columns' => ['first', 'second']
        ], [], ['auth' => '!tables.update']);
        $response->assertStatus(200)->assertJson(['status' => false]);

        // Проверка возможности создания строки в таблице от имени пользователя,
        // ИМЕЮЩЕГО разрешение на обновление таблиц, и НЕ присутствующего среди участников,
        $response = $this->ajax('POST', '/api/tables/1/add_row', [
            'columns' => ['first', 'second']
        ], [], ['auth' => 'tables.update']);
        $response->assertStatus(200)->assertJsonStructure(['id', 'values', 'created_at', 'updated_at']);

    }

}
