<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Facades\UserServiceFacade as UserService;

class AppController extends Controller
{

    /**
     * Отображение общей статичесткой страницы JS приложения.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function app()
    {

        // Предварительная прогрузка данных текущего пользователя.
        // Так как данные формируются в JS объект со стороны фронта, данные о роли и доступных разрешениях полтягиваю запросами.
        $user = Auth::user();

        // Отображение статичной страницы JS приложения.
        return view('app', [
            'user' => $user,
            'role' => $user->role,
            'permissions' => $user->role->permissions
        ]);
    }

}
