<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArticleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('article', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('instance_id');
            $table->string('title');
			$table->text('content');
			$table->integer('author_id');
			$table->enum('published', array('Y','N'))->default('N');
			$table->enum('submission', array('Y','N'))->default('N');
            $table->string('issue_dates');
			$table->softdeletes();
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
		Schema::drop('article');
	}

}
