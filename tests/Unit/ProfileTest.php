<?php

namespace Tests\Unit;

use App\Models\Table;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileTest extends TestCase
{

    /**
     * Получение профиля пользователя.
     * Получение профиля текущего аутентифицированного пользователя с ролью и разрешениями.
     */
    public function testGetProfile()
    {

        // Попытка получение профиля от не аутентифицированного пользователя.
        $response = $this->ajax('GET', '/api/profile', [], [], []);
        $response->assertStatus(200)->assertJson(['status' => false]);

        // Получение профиля от имени аутентифицированного пользователя.
        $response = $this->ajax('GET', '/api/profile', [], [], ['auth' => 'admin']);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id', 'name', 'email',
                'role' => [
                    'permissions'
                ]
            ]);
    }

    /**
     * Получение всех таблиц текущего пользователя.
     */
    public function testGetTablesCurrentUser()
    {
        // Создание текущего пользователя.
        $user = factory(User::class)->create();

        // Создание таблиц и присвоение текущему пользователю.
        factory(Table::class, 2)->make()->each(function($table) use ($user) {
            $table->save();
            $table->users()->attach($user->id, ['adding_row' => true]);
        });;

        // Отправка запроса на получение всех таблиц текущего пользователя.
        $response = $this->ajax('GET', '/api/profile/tables', [], [], ['auth_current' => $user->id]);
        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

}
