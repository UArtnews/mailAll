 <?php
	Schema::table('submission', function($table)
	{
		$table->string('contactName')->after('location');
		$table->string('contactEmail')->after('contactName');
		$table->string('contactPhone')->after('contactEmail');
	});

?>