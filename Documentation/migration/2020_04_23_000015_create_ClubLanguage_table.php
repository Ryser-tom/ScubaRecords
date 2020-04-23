<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClublanguageTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'ClubLanguage';

    /**
     * Run the migrations.
     * @table ClubLanguage
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('Club');
            $table->integer('Language');

            $table->index(["Language"], 'language_idx');


            $table->foreign('Club', 'ClubLanguage_Club')
                ->references('idClub')->on('Club')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('Language', 'language_idx')
                ->references('idLanguage')->on('Language')
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
