<?php

namespace App\Http\Controllers\API;

use App\Transformers\TableTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{

    /**
     * Получение личного профиля аутентифицированного пользователя.
     * @param Request $request
     * @return mixed
     */
    public function index()
    {
        return fractal()
            ->item(Auth::user())
            ->transformWith(new UserTransformer())
            ->parseIncludes('role.permissions')
            ->toJson();
    }

    /**
     * Получение всех таблиц текущего пользователя.
     * @param Request $request
     * @return mixed
     */
    public function tables(Request $request)
    {
        return fractal()
            ->collection(Auth::user()->tables)
            ->transformWith(new TableTransformer())
            ->toJson();
    }

}
