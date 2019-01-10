<?php

class UsersTableSeeder extends Seeder {

	public function run()
	{
		DB::table('users')->truncate();

        User::create(array(
            'uanet'			=> 'als128',
            'email'			=> 'als128@uakron.edu',
            'first'			=> 'Alex',
            'last'			=> 'Sterling',
            'submitter'     => 0,
            'created_at'	=> date('Y-m-d H:i:s'),
            'updated_at'	=> date('Y-m-d H:i:s'),
        ));
	}
}
