<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivebuddyTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'DiveBuddy';

    /**
     * Run the migrations.
     * @table DiveBuddy
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('Dive_idDive');
            $table->integer('User_idUser');

            $table->index(["User_idUser"], 'fk_diveBuddy_User1_idx');


            $table->foreign('Dive_idDive', 'DiveBuddy_Dive_idDive')
                ->references('idDive')->on('Dive')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('User_idUser', 'fk_diveBuddy_User1_idx')
                ->references('idUser')->on('User')
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
