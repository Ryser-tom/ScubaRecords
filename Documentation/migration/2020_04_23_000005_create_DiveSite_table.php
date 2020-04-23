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
            $table->longText('Description')->nullable()->default(null);
            $table->integer('Location');
            $table->dateTime('StartDateTime')->nullable()->default(null);
            $table->dateTime('EndDateTime')->nullable()->default(null);
            $table->string('difficulty', 45)->nullable()->default(null);
            $table->integer('depthMin')->nullable()->default(null);
            $table->integer('depthMax')->nullable()->default(null);
            $table->tinyInteger('compas')->nullable()->default(null);

            $table->index(["Location"], 'location_idx');

            $table->unique(["Name"], 'Name_UNIQUE');


            $table->foreign('Location', 'location_idx')
                ->references('idLocation')->on('Location')
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
