<?php

namespace App\Transformers;

use App\Models\Table;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class TableTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
         'users', 'creator', 'rows'
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Table $table)
    {
        return [
            'id'            => (int) $table->id,
            'name'          => (string) $table->name,
            'description'   => (string) $table->description,
            'columns'       => unserialize($table->columns),
            'created_at'    => (string) Carbon::parse($table->created_at)->format('Y-m-d H:i:s'),
            'updated_at'    => (string) Carbon::parse($table->updated_at)->format('Y-m-d H:i:s')
        ];
    }

    /**
     * Получение пользователей.
     * @param Table $table
     * @return \League\Fractal\Resource\Collection
     */
    public function includeUsers(Table $table)
    {
        return $this->collection($table->users, new UserTransformer());
    }

    /**
     * Получение создателя таблицы.
     * @param Table $table
     * @return \League\Fractal\Resource\Item
     */
    public function includeCreator(Table $table)
    {
        return $this->item($table->creator, new UserTransformer());
    }

    /**
     * Получение строк текущей таблицы.
     * @param Table $table
     * @return \League\Fractal\Resource\Collection
     */
    public function includeRows(Table $table)
    {
        return $this->collection($table->rows, new TableRowTransformer());
    }

}
