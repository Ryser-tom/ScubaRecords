<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookmarksuserdivesiteTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'BookmarksUserDivesite';

    /**
     * Run the migrations.
     * @table BookmarksUserDivesite
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('User');
            $table->integer('DiveSite')->unsigned();

            $table->index(["DiveSite"], 'diveSite_idx');


            $table->foreign('DiveSite', 'diveSite_idx')
                ->references('idDiveSite')->on('DiveSite');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists($this->tableName);
     }
}
