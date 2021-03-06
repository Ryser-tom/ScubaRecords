<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'User';

    /**
     * Run the migrations.
     * @table User
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('idUser');
            $table->string('FirstName', 45)->nullable();
            $table->string('LastName', 45)->nullable();
            $table->string('Email', 45)->nullable();
            $table->string('Password', 45)->nullable();
            $table->integer('location')->unsigned()->nullable();

            $table->index(["location"], 'location_idx');


            $table->foreign('location', 'location_idx')
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
