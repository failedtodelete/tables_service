<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(Roles::class);
        $this->call(Users::class);
        $this->call(Tables::class);
    }
}
