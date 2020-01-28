<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{

    /**
     * Создание пользователя.
     * Создание модели пользователя от не аутентифицированного пользователя.
     * Создание модели от пользователя, не имеющего разрешения.
     * Создание модели от пользоватля, имеющего разрешение.
     */
    public function testCreationUser()
    {
        // Модель пользователя
        $user = [
            'name' => 'test123',
            'last_name' => 'test_last',
            'position' => 'position here',
            'email' => 'test@test.com',
            'password' => 'test123',
            'role_id' => 1,
            'api_token' => 'test'
        ];

        // Отправка запроса от не аутентифицированного пользователя.
        $response = $this->ajax('POST', 'api/users', $user, [], []);
        $response->assertStatus(200)->assertJson(['status' => false]);

        // Отправка запроса от пользователя, НЕ ИМЕЮЩЕГО разрешения на выполнение действия.
        $response = $this->ajax('POST', 'api/users', $user, [], ['auth' => '!users.create']);
        $response->assertStatus(200)->assertJson(['status' => false]);

        // Отправка запроса от пользователя, ИМЕЮЩЕГО разрешения на выполнение действия.
        $response = $this->ajax('POST', 'api/users', $user, [], ['auth' => 'users.create']);
        $response->assertStatus(200)->assertJsonStructure([
            'id', 'name', 'last_name', 'position', 'email', 'role'
        ]);

    }

    /**
     * Получение всех пользователей.
     * Создание нескольких пользователей - проверка на количество пользователей в ответе.
     */
    public function testIndexUsers()
    {
        $users = factory(User::class, rand(5, 20))->create();
        $response = $this->ajax('GET', '/api/users', [], [], ['auth' => 'users.index']);
        $response->assertStatus(200)
            ->assertJsonCount($users->count() + 1, 'data');
    }

    /**
     * Получение пользователя. */
    public function testShowUser()
    {
        $user = factory(User::class)->create();
        $response = $this->ajax('GET', "api/users/{$user->id}", [], [], ['auth' => 'users.show']);
        $response->assertStatus(200)
            ->assertJson([
                'id' => $user->id
            ]);
    }

}
