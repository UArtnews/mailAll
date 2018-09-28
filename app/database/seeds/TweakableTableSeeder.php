<?php

class TweakableTableSeeder extends Seeder {

	public function run()
	{
		DB::table('tweakable')->truncate();

		$faker = Faker\Factory::create();

		foreach(Instance::all() as $instance)
		{
			Tweakable::create(array(
				'instance_id'	=> $instance->id,
				'parameter'		=> 'global-background-color',
				'value'			=> 'rgb('.rand(150,230).','.rand(150,230).','.rand(150,230).')',
			));

			Tweakable::create(array(
				'instance_id'	=> $instance->id,
				'parameter'		=> 'publication-background-color',
				'value'			=> 'rgb('.rand(150,230).','.rand(150,230).','.rand(150,230).')',
			));

			Tweakable::create(array(
				'instance_id'	=> $instance->id,
				'parameter'		=> 'publication-border-color',
				'value'			=> 'rgb('.rand(150,230).','.rand(150,230).','.rand(150,230).')',
			));

			Tweakable::create(array(
				'instance_id'	=> $instance->id,
				'parameter'		=> 'publication-h1-color',
				'value'			=> 'rgb('.rand(150,230).','.rand(150,230).','.rand(150,230).')',
			));

			Tweakable::create(array(
				'instance_id'	=> $instance->id,
				'parameter'		=> 'publication-font-size',
				'value'			=> (rand(50,200)/100).'em',
			));

			Tweakable::create(array(
				'instance_id'	=> $instance->id,
				'parameter'		=> 'publication-banner-image',
				'value'			=> 'http://lorempixel.com/150/100',
			));

		}
	}

}
