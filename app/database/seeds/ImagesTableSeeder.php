<?php

class ImagesTableSeeder extends Seeder {

	public function run()
	{
        DB::table('images')->truncate();

        $faker = Faker\Factory::create();

        $instances = Instance::all();

        foreach($instances as $instance)
        {
            //Write file to disc, then save to DB
            $pathName = preg_replace('/[^\w]+/', '_', $instance->name);

            //Create Image Directories
            if(!is_dir(app_path().'/../images/'.$pathName)){
                echo "Creating directory at ".app_path().'/../images/'.$pathName."\n";
                mkdir(app_path().'/../images/'.$pathName,0775);
            }

            if(!is_dir(app_path().'/../docs/'.$pathName)){
                echo "Creating directory at ".app_path().'/../docs/'.$pathName."\n";
                mkdir(app_path().'/../docs/'.$pathName,0775);
            }

            foreach(range(1,25) as $index){


                $fileName = $faker->word.'.png';
                $didCopy = copy('http://lorempixel.com/'.rand(100,500).'/'.rand(100,300).'/business', app_path().'/../images/'.$pathName.'/'.$fileName);

                while(!$didCopy){
                    $didCopy = copy('http://lorempixel.com/'.rand(100,500).'/'.rand(100,300).'/business', app_path().'/../images/'.$pathName.'/'.$fileName);
                }

                chown(app_path().'/../images/'.$pathName.'/'.$fileName, 'www-data');


                Image::create(array(
                    'instance_id'   => $instance->id,
                    'filename'      => $fileName,
                    'title'         => $faker->catchphrase,
                ));
            }

        }
	}

}
