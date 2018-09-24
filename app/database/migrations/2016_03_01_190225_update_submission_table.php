<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateSubmissionTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('submission', function($table) {
			$table->string('contactName');
			$table->string('contactEmail');
			$table->string('contactPhone');
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('submission', function($table) {
			$table->dropColumn('contactName');
			$table->dropColumn('contactEmail');
			$table->dropColumn('contactPhone');
		});
    }

}
