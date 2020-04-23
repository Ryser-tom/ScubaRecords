<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificationTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'Certification';

    /**
     * Run the migrations.
     * @table Certification
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('idCertification');
            $table->integer('user')->unsigned()->nullable();
            $table->string('name', 45)->nullable();
            $table->dateTime('date')->nullable();

            $table->index(["user"], 'user_idx');


            $table->foreign('user', 'user_idx')
                ->references('idUser')->on('User');
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
