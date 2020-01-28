<?php

namespace App\Transformers;

use App\Models\TableRow;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class TableRowTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [
        'creator'
    ];

    /**
     * @param TableRow $row
     * @return array
     */
    public function transform(TableRow $row)
    {
        return [
            'id' => (int) $row->id,
            'values' => unserialize($row->values),
            'created_at' => (string) Carbon::parse($row->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => (string) Carbon::parse($row->updated_at)->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Получение пользователя, создавшего строку.
     * @param TableRow $row
     * @return \League\Fractal\Resource\Item
     */
    public function includeCreator(TableRow $row)
    {
        return $this->item($row->creator, new UserTransformer());
    }

}
