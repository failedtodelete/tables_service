<?php namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    protected $availableIncludes = [
        'permissions'
    ];

    protected $defaultIncludes = [
        'role'
    ];

    /**
     * A Fractal transformer.
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        $data = [
            'id'        => (int) $user->id,
            'name'      => (string) $user->name,
            'last_name' => (string) $user->last_name,
            'position'  => (string) $user->position,
            'email'     => (string) $user->email
        ];

        if ($user->pivot) $data['pivot'] = $user->pivot;
        return $data;
    }

    /**
     * Получение разрешений.
     * @param User $user
     * @return \League\Fractal\Resource\Collection
     */
    public function includePermissions(User $user)
    {
        return $this->collection($user->permissions, new PermissionTransformer());
    }

    /**
     * Получение роли пользователя.
     * @param User $user
     * @return \League\Fractal\Resource\Item
     */
    public function includeRole(User $user)
    {
        return $this->item($user->role, new RoleTransformer());
    }

}
