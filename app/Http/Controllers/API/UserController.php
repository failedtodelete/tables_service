<?php namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Facades\UserServiceFacade as UserService;
use App\Transformers\UserTransformer;
use App\Models\User;

class UserController extends Controller
{

    /**
     * Получение всех пользователей.
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {

        // Проверка прав доступа.
        $this->authorize('index', User::class);

        // Получение пользователей.
        $users = UserService::all();
        return fractal()
            ->collection($users)
            ->transformWith(new UserTransformer())
            ->toJson();
    }

    /**
     * Создание пользователя.
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        // Проверка прав доступа.
        $this->authorize('create', User::class);

        // Создание пользователя.
        $user = UserService::store($request->all());
        return fractal()
            ->item($user)
            ->transformWith(new UserTransformer())
            ->toJson();
    }

    /**
     * Обновление данных пользователя.
     * @param Request $request
     * @param $id
     * @return string
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, $id)
    {
        // Проверка прав доступа.
        $this->authorize('update', User::class);

        // Обновление пользователя.
        $user = UserService::update($request->all(), $id);
        return fractal()
            ->item($user)
            ->transformWith(new UserTransformer())
            ->toJson();
    }

    /**
     * Просмотр пользователя.
     * @param Request $request
     * @param $id
     * @return string
     */
    public function show(Request $request, $id)
    {
        $user = UserService::findOrFail($id);
        return fractal()
            ->item($user)
            ->transformWith(new UserTransformer())
            ->toJson();
    }

}
