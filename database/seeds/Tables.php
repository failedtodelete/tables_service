<?php

use Illuminate\Database\Seeder;

class Tables extends Seeder
{

    protected $tables = [
        [
            'name' => 'f_0415e170b8d1b6e2',
            'description' => 'Тестовое описание таблицы',
            'creator_id' => 1,
            'columns' => [
                ['name' => '№ п/п', 'width' => 50, 'description' => 'Порядковый номер'],
                ['name' => 'Дата поставки', 'width' => 95, 'description' => 'Тестовое описание даты поставки'],
                ['name' => 'ТТН, №', 'width' => 120, 'description' => null],
                ['name' => 'Поставщик', 'width' => 130, 'description' => null],
                ['name' => 'Наименование вспом.материала', 'width' => 120, 'description' => null],
                ['name' => 'Кол-во, кг/т/шт/ уп/доза', 'width' => 120, 'description' => null],
                ['name' => 'Сопроводительные документы', 'width' => 140, 'description' => null],
                ['name' => 'Сост. транс. упаковки / маркировки', 'width' => 120, 'description' => null],
                ['name' => 'Дата выработки', 'width' => 120, 'description' => null],
                ['name' => 'Годен до', 'width' => 250, 'description' => null],
                ['name' => 'Номер партии', 'width' => 120, 'description' => null],
                ['name' => 'Акт № вход. контроля, дата', 'width' => 120, 'description' => null],
                ['name' => 'Примечание', 'width' => 120, 'description' => null],
            ]
        ]
    ];

    protected $rows = [
        [
            '1',
            '18.07.2019',
            'ТН №1725 от 18.07.19',
            'ООО "Союзснабпром"',
            'Бисульфит калия (18%)',
            '925 кг',
            'Дек. о соот. ЕАЭС N RU Д-FR.АК01.В.00800, сертификат анализа от 13.06.19',
            'без нарушений',
            '04.06.2019',
            '04.06.2020',
            '1190604',
            'Акт №1 от15.07.2019', 'в производство'
        ]
    ];

    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        foreach($this->tables as $table) {
            $table = \App\Models\Table::create([
                'name' => $table['name'],
                'description' => $table['description'],
                'creator_id' => $table['creator_id'],
                'columns' => serialize($table['columns'])
            ]);

            for($i = 0; $i < 40; $i++) {
                foreach($this->rows as $row) {
                    $table->rows()->create([
                        'values' => serialize($row),
                        'creator_id' => $table->creator_id
                    ]);
                }
            }
        }
    }
}
