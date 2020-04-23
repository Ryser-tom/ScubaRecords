<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivesitelanguageTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'DiveSiteLanguage';

    /**
     * Run the migrations.
     * @table DiveSiteLanguage
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('diveSite');
            $table->integer('Language')->unsigned();

            $table->index(["Language"], 'language_idx');


            $table->foreign('Language', 'language_idx')
                ->references('idLanguage')->on('Language')
                ->onDelete('no action')
                ->onUpdate('no action');
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
