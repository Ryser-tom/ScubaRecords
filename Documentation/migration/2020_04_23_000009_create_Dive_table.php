<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiveTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'Dive';

    /**
     * Run the migrations.
     * @table Dive
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('idDive');
            $table->integer('diveSite')->nullable()->default(null);
            $table->string('boat', 45)->nullable()->default(null);
            $table->string('weather', 45)->nullable()->default(null);
            $table->string('weight', 45)->nullable()->default(null)->comment('weight that the diver has to add to be able to dive.');
            $table->longText('description')->nullable()->default(null);
            $table->integer('location')->nullable()->default(null);
            $table->double('pressionInit')->nullable()->default(null)->comment('pression inside the tank after filling it up.');
            $table->integer('diver');
            $table->tinyInteger('public');

            $table->index(["location"], 'location_idx');

            $table->index(["diveSite"], 'divesite_idx');

            $table->index(["diver"], 'diver_idx');


            $table->foreign('location', 'location_idx')
                ->references('idLocation')->on('Location')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('diver', 'diver_idx')
                ->references('idUser')->on('User')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('diveSite', 'divesite_idx')
                ->references('idDiveSite')->on('DiveSite')
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
