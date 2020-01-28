<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableRow extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'values', 'creator_id'
    ];

    /**
     * Получение таблицы текущей строки.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }

    /**
     * Получение пользователя, создавшего строку.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

}
