<?php

use Illuminate\Database\Seeder;
use App\Facades\UserServiceFacade as UserService;

class Users extends Seeder
{

    protected $users = [
        [
            'name'      => 'test',
            'last_name' => 'last_test',
            'position'  => 'test_position',
            'email'     => 'test@test.com',
            'password'  => 'test123',
            'role_id'   => 1,
            'api_token' => 'test_token'
        ],
        [
            'name'      => 'test11',
            'last_name' => 'last_test11',
            'position'  => 'test_position11',
            'email'     => 'test@test.com1',
            'password'  => 'test123',
            'role_id'   => 2,
            'api_token' => 'test_token'
        ]
    ];

    /**
     * Run the database seeds */
    public function run()
    {
        foreach($this->users as $user) {

            // Получение роли.
            $role = \App\Models\Role::findOrFail($user['role_id']);

            UserService::store([
                'name' => $user['name'],
                'last_name' => $user['last_name'],
                'position' => $user['position'],
                'email' => $user['email'],
                'password' => $user['password'],
                'role' => $role->display_name,
                'api_token' => $user['api_token']
            ]);
        }

        factory(\App\Models\User::class, 20)->create();
    }
}
