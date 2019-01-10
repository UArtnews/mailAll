<?php

class AdminController extends BaseController {

    public function route($action = null, $subAction = null, $id = null){
        $app = app();
        $adminController = $app->make('AdminController');

        $parameters = array(
            'data'  => array(
                'default_tweakables'    => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
                'tweakables'            => array(),
                'action'                => $action,
                'subAction'             => $subAction,
                'id'                    => $id
            )
        );

        if($action == null){
            return $adminController->callAction('index', $parameters);
        }else{
            return $adminController->callAction($action, $parameters);
        }
    }

    public function index($data)
    {
        return Redirect::to('admin/instances');
    }

    public function users($data){
        $instances = array(0 => 'GLOBAL');

        foreach(Instance::all() as $instance){
            $instances[$instance->id] = $instance->name;
        }

        $data['users'] = User::with('permissions')->where('submitter', 0)->get();
        $data['deleted_users'] = User::onlyTrashed()->where('submitter', 0)->get();
        $data['instances'] = $instances;
        $data['nodes'] = array(
            'edit' => 'edit',
            'admin' => 'admin',
            'superAdmin' => 'superAdmin');

        return View::make('admin.users', $data);
    }

    public function instances($data){
        $data = array(
            'default_tweakables'    => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
            'tweakables'            => array(),
            'action'                => 'instances',
            'instances'             => Instance::all(),
            'deleted_instances'     => Instance::onlyTrashed()->get()
        );

        return View::make('admin.instances', $data);
    }

    public function save($data){
        //Handle Incoming PermissionNodes
        if($data['subAction'] == 'permissionNode'){
            if(UserPermission::where('instance_id', Input::get('instance_id', Input::get('instance_id')))->where('user_id', Input::get('user_id'))->where('node', Input::get('node'))->count() > 0){
                //Just leave it alone!
                return Redirect::back()->withSuccess('Permission Node Updated!');
            }else{
                UserPermission::create(Input::all());
                return Redirect::back()->withSuccess('Permission Node Added!');
            }
        }elseif($data['subAction'] == 'user'){
            $validator = Validator::make(Input::all(), User::$rules);
            if($validator->fails()){
                return Redirect::back()->withError('Invalid input');
            }else{
                //Do the insert
                //Check if they're a submitter being added to the admin group
                if(User::where('uanet', Input::get('uanet'))->count() > 0){
                    if(User::where('uanet', Input::get('uanet'))->where('submitter', 1)->count() > 0){
                        //user is a submitter, promote them
                        $user = User::where('uanet', Input::get('uanet'))->first();
                        $user->submitter = false;
                        $user->save();
                    }
                    return Redirect::back()->withSuccess('User already existed, promoting!');
                }else{
                    User::create(Input::all());
                    return Redirect::back()->withSuccess('User succesfully Added!');
                }
            }
        }elseif($data['subAction'] == 'instance'){
            if(Instance::where('name', Input::get('name'))->count() > 0){
                return Redirect::back()->withError('An instance with that name already exists');
            }else {
                Instance::create(array('name' => Input::get('name')));
                return Redirect::back()->withSuccess('Instance Successfully Created!');
            }
        }
    }

    public function delete($data){
        //Handle Incoming PermissionNodes
        if($data['subAction'] == 'permissionNode'){
            UserPermission::find($data['id'])->delete();
            return Redirect::back()->withSuccess('Permission Node Deleted');
        }elseif($data['subAction'] == 'user'){
            User::find($data['id'])->delete();
            UserPermission::where('user_id', $data['id'])->delete();
            return Redirect::back()->withSuccess('User successfully Deleted!');
        }elseif($data['subAction'] == 'instance'){
            Instance::find($data['id'])->delete();
            return Redirect::back()->withSuccess('Instance successfully Deleted!');
        }
    }

    public function restore($data){
        if($data['subAction'] == 'instance'){
            Instance::onlyTrashed()->where('id', $data['id'])->restore();
            return Redirect::back()->withSuccess('Instance Successfully Restored!');
        }elseif($data['subAction'] == 'user'){
            User::onlyTrashed()->where('id', $data['id'])->restore();
            return Redirect::back()->withSuccess('User Successfully Restored');
        }
    }
<<<<<<< HEAD
	public function updateSubmission()
	{
		Schema::table('submission', function($table)
		{
			$table->string('contactName')->after('location')->nullable();
			$table->string('contactEmail')->after('contactName')->nullable();
			$table->string('contactPhone')->after('contactEmail')->nullable();
		});
	}
	public function nonShibSignin()
	{
		$data = array();
        $instances = array(0 => 'GLOBAL');	
		
		$instance = Instance::firstOrFail();
		$data['instance'] = $instance;
        $defaultTweakable = DefaultTweakable::all();
		
        $action = '';
        $subAction = '';
        //Gather Data Common to all editor views
        $data = array(
            'action'                   => $action,
            'subAction'                => $subAction,
            'instance'                 => $instance,
            'instanceId'               => $instance->id,
            'instanceName'             => $instance->name,
            'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
            'default_tweakables'       => reindexArray($defaultTweakable, 'parameter', 'value'),
            'tweakables_types'         => reindexArray($defaultTweakable, 'parameter', 'type'),
            'default_tweakables_names' => reindexArray($defaultTweakable, 'parameter', 'display_name'),
			'submission'			   => '',
        );
		
		
		return View::make('editor.editorLogin', $data);
		
		//return View::make('editor.editorLogin', $data);
	}
	function doNonShibAuth()
	{
		//add required validation here for both

	  print(Input::get('uanet'));
		$validator = Validator::make(
			array(
				'uanet' => Input::get('uanet'),
				'pass' => Input::get('pass'),
			),
			array(
				'uanet' => 'required',
				'pass' => 'required',
			)
		);
        
       /* */       
	   if($validator->fails()){
            $errorMessage = 'Please correct the following:</br>';
            foreach($validator->messages()->all() as $field => $message){
                $errorMessage .= ucfirst($message)."</br>";
            }
            return Redirect::back()->withError($errorMessage);
        }
		 //FORM HAS VALUES ++++++++++++++++++++++++++++++++++++++++
		$uanetId = Input::get('uanet');
		$loginToken = Input::get('pass');	

		
		//b374d155-3903-4db2-b11b-0059e77ffac0

		$isToken = DefaultTweakable::where('parameter', "login-token")->where('value', $loginToken)->count();

		
		if($isToken > 0){
			$anonID = 1899;
			$user = User::where('uanet', $uanetId)->firstOrFail();
			if(count($user)){
				Auth::login(User::find($user->id));
				$errorMessage = "<strong style=\"color: red;\" >Permission to Login Granted</strong>";
				return Redirect::to('editors')->withSuccess($errorMessage);
				
			}else{
				$errorMessage = 'You do not have permission to access that!';
				return Redirect::back()->withError($errorMessage);
				}
		}
	}
=======
>>>>>>> mailAllProd/master
}