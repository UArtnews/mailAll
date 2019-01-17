<?php

class PublicationTableSeeder extends Seeder {

	public function run()
	{
		DB::table('publication')->truncate();

		$faker = Faker\Factory::create();

		foreach(Instance::all() as $instance)
		{
			//Create two live Publications
			foreach(range(1,40) as $index)
			{
				Publication::create(array(
					'instance_id'	=> $instance->id,
					'publish_date'	=> date_format($faker->dateTimeThisMonth(), 'Y-m-d'),
					'banner_image'	=> $faker->imageUrl(500,200),
					'published'		=> 'Y',
					'type'          => rand(1,10) > 7 ? 'special' : 'regular',
					'created_at'	=> date_format($faker->dateTimeThisYear(), 'Y-m-d H:i:s'),
					'updated_at'	=> date_format($faker->dateTimeThisYear(), 'Y-m-d H:i:s'),
				));
			}

			Publication::create(array(
				'instance_id'	=> $instance->id,
				'publish_date'	=> date_format($faker->dateTimeThisMonth(), 'Y-m-d'),
				'banner_image'	=> $faker->imageUrl(500,200),
				'published'		=> 'N',
                'type'          => rand(1,10) > 7 ? 'special' : 'regular',
				'created_at'	=> date_format($faker->dateTimeThisYear(), 'Y-m-d H:i:s'),
				'updated_at'	=> date_format($faker->dateTimeThisYear(), 'Y-m-d H:i:s'),
			));
		}
	}

}
