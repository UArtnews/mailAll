<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePublicationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('publication', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('instance_id');
			$table->date('publish_date');
			$table->string('banner_image');
			$table->enum('published',array('Y','N'));
			$table->bool('is_raw')->default(false);
			$table->text('type');
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
		Schema::drop('publication');
	}

}
