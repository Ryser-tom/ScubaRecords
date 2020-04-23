<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'Media';

    /**
     * Run the migrations.
     * @table Media
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('idMedia');
            $table->dateTime('dttmMedia');
            $table->string('pathMedia', 100);
            $table->integer('Dive_idDive');

            $table->index(["Dive_idDive"], 'fk_Picture_Dive1_idx');


            $table->foreign('Dive_idDive', 'fk_Picture_Dive1_idx')
                ->references('idDive')->on('Dive')
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
