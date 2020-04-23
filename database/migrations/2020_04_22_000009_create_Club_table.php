<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClubTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'Club';

    /**
     * Run the migrations.
     * @table Club
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('idClub');
            $table->string('Name', 45);
            $table->longText('Description')->nullable();
            $table->integer('Location')->unsigned();
            $table->integer('CreatedBy')->unsigned();
            $table->integer('Master')->unsigned();
            $table->dateTime('StartDateTime');
            $table->dateTime('EndDateTime')->nullable();

            $table->index(["Master"], 'user_idx');

            $table->index(["CreatedBy"], 'fk_Club_User2_idx');

            $table->index(["Location"], 'location_idx');

            $table->unique(["Name"], 'Name_UNIQUE');


            $table->foreign('Master', 'user_idx')
                ->references('idUser')->on('User')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('Location', 'location_idx')
                ->references('idLocation')->on('Location')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('CreatedBy', 'fk_Club_User2_idx')
                ->references('idUser')->on('User')
                ->onDelete('cascade')
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
