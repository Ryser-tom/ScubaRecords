<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivesiteTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'DiveSite';

    /**
     * Run the migrations.
     * @table DiveSite
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('idDiveSite');
            $table->string('Name', 45);
            $table->longText('Description')->nullable();
            $table->integer('Location')->unsigned();
            $table->dateTime('StartDateTime')->nullable();
            $table->dateTime('EndDateTime')->nullable();
            $table->string('difficulty', 45)->nullable();
            $table->integer('depthMin')->nullable();
            $table->integer('depthMax')->nullable();
            $table->tinyInteger('compas')->nullable();

            $table->index(["Location"], 'location_idx');

            $table->unique(["Name"], 'Name_UNIQUE');


            $table->foreign('Location')
                ->references('idLocation')->on('Location');
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
