<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicationLoggerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::create('publication_log', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id');
            $table->integer('user_id');
            $table->string('eventname');
            $table->text('type');
            $table->text('description');
            $table->timestamps();
        });
    }
    
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('publication_log');
    }

}
