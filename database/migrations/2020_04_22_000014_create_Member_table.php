<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'Member';

    /**
     * Run the migrations.
     * @table Member
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('user')->unsigned();
            $table->integer('club')->unsigned();

            $table->index(["user"], 'user_idx');

            $table->index(["club"], 'club_idx');


            $table->foreign('user', 'user_idx')
                ->references('idUser')->on('User')
                ->onDelete('no action')
                ->onUpdate('no action');

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
