<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->text('columns');
            $table->bigInteger('creator_id')->unsigned();
            $table->timestamps();

            $table->foreign('creator_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('restrict');
        });

        Schema::create('table_rows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('table_id')->unsigned();
            $table->text('values');
            $table->bigInteger('creator_id')->unsigned();
            $table->timestamps();

            $table->foreign('table_id')->references('id')->on('tables')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('creator_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('restrict');
        });

        Schema::create('user_table', function(Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('table_id')->unsigned();
            $table->boolean('adding_row')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('table_id')->references('id')->on('tables')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_table', function(Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['table_id']);
            $table->dropIfExists();
        });

        Schema::table('table_rows', function(Blueprint $table) {
            $table->dropForeign(['table_id']);
            $table->dropForeign(['creator_id']);
            $table->dropIfExists();
        });

        Schema::table('tables', function(Blueprint $table) {
            $table->dropForeign(['creator_id']);
            $table->dropIfExists();
        });
    }
}
