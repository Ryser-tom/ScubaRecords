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
            $table->string('FirstName', 45)->nullable()->default(null);
            $table->string('LastName', 45)->nullable()->default(null);
            $table->string('Email', 45)->nullable()->default(null);
            $table->string('Password', 45)->nullable()->default(null);
            $table->integer('location')->nullable()->default(null);

            $table->index(["location"], 'location_idx');


            $table->foreign('location', 'location_idx')
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
