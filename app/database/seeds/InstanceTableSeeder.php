<?php

class InstanceTableSeeder extends Seeder {

	public function run()
	{
		DB::table('instance')->truncate();

		$faker = Faker\Factory::create();

        $instances = array(
            'Digest',
            'Zipmail',
            'Waynemail',
            'Test',
        );

		foreach($instances as $instance)
		{
			Instance::create(array(
				'name'	=> $instance,
				'created_at'	=> date_format($faker->dateTimeThisYear(), 'Y-m-d H:i:s'),
				'updated_at'	=> date_format($faker->dateTimeThisYear(), 'Y-m-d H:i:s'),
			));
		}
	}
}