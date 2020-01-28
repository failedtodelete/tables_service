<?php namespace App\Models;

use App\Models\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name', 'last_name', 'position', 'email', 'password', 'role_id', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = [
        'password', 'email_verified_at', 'remember_token', 'role_id'
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Получение ролей пользователя.
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Получение таблиц данного пользователя.
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function tables()
    {
        return $this->belongsToMany(Table::class, 'user_table')
            ->withPivot(['adding_row', 'created_at', 'updated_at']);
    }

    /**
     * Получение доступа к разрешению роли пользователя.
     * @param $permission
     * @return bool
     */
    public function access($permission)
    {
        return (bool) $this->role->permissions
            ->where('name', $permission)
            ->first();
    }

}
