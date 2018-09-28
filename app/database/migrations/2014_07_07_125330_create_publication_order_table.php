<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicationOrderTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('publication_order', function(Blueprint $table) {
            $table->increments('cd');
            $table->integer('publication_id');
            $table->integer('article_id');
            $table->enum('likeNew',array('Y','N'))->default('N');
            $table->integer('order');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('publication_order');
	}

}
