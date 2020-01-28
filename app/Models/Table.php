<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'columns', 'creator_id'
    ];

    /**
     * Получение пользователей данной таблицы.
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_table')
            ->withPivot(['adding_row', 'created_at', 'updated_at']);
    }

    /**
     * Получение пользователя, создавшего таблицу.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получение строк текущей таблицы.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rows()
    {
        return $this->hasMany(TableRow::class, 'table_id');
    }

}
