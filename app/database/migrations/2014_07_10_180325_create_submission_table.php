<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubmissionTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submission', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('instance_id');
            $table->integer('user_id');
            $table->string('uanet');
            $table->string('title');
            $table->text('content');
            $table->date('event_start_date');
            $table->date('event_end_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location');
            $table->string('issue_dates');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('organization');
            $table->string('department');
            $table->enum('publish_contact_info', array('Y','N'))->default('N');
            $table->enum('promoted', array('Y','N'))->default('N');
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
        Schema::drop('submission');
    }

}
