<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'Location';

    /**
     * Run the migrations.
     * @table Location
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('idLocation');
            $table->string('Name', 45);
            $table->integer('Country');
            $table->string('GPS', 45)->nullable()->default(null);

            $table->index(["Country"], 'country_idx');


            $table->foreign('Country', 'country_idx')
                ->references('idCountries')->on('Countries')
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
