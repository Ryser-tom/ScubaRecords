<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiveTagTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'Dive_Tag';

    /**
     * Run the migrations.
     * @table Dive_Tag
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('idDive');
            $table->integer('idTag');
            $table->string('txtValue', 45);

            $table->index(["idTag"], 'fk_Dive_Tag_Tag1_idx');

            $table->index(["idDive"], 'fk_Dive_Tag_Dive1_idx');


            $table->foreign('idDive', 'fk_Dive_Tag_Dive1_idx')
                ->references('idDive')->on('Dive')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idTag', 'fk_Dive_Tag_Tag1_idx')
                ->references('idTag')->on('Tag')
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
