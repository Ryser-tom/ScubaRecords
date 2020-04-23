<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'Event';

    /**
     * Run the migrations.
     * @table Event
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('idEvent');
            $table->string('name', 45);
            $table->dateTime('startDate');
            $table->dateTime('endDate');
            $table->string('description', 45);
            $table->string('location', 45);
            $table->tinyInteger('privacity');
            $table->integer('club')->unsigned();

            $table->index(["club"], 'club_idx');


            $table->foreign('club', 'club_idx')
                ->references('idClub')->on('Club')
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
