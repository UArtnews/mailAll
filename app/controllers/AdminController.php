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
}