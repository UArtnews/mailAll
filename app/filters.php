<?php

Route::filter('force.ssl', function()
{
    if( ! Request::secure())
    {
        return Redirect::secure(Request::path());
    }
    
});

/*
 |--------------------------------------------------------------------------
 | Application & Route Filters
 |--------------------------------------------------------------------------
 |
 | Below you will find the "before" and "after" events for the application
 | which may be used to do any work before or after a request into your
 | application. Here you may also register your custom route filters.
 |
 */

App::before(function($request)
{
    
/* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
++ Below code to inspect SQL from Laravel
++ DB::enableQueryLog();
++ dd(DB::getQueryLog());
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++  */
/* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
++ this is for the back door on dev
++TIP* make sure 'deleted_at' has a value o is null or the queries will not work 
++on all tables with the 'deleted_at' column
++make every table has the id as the primary key and is properly formatted "pk nn un ai"
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++  */
    $anonID = $request->input('uid');
    if($anonID > 0){
        $user = User::find($anonID);
        if(count($user)){
            Auth::login(User::find($anonID));
            print("<strong style=\"color: red;\" >Permission to Login Granted</strong>");
        }else{
            Redirect::guest('/')->withError('You do not have permission to access that!');
        }
    }
});

//g
App::after(function($request, $response)
{
    //
});

/*
 |--------------------------------------------------------------------------
 | Authentication Filters
 |--------------------------------------------------------------------------
 |
 | The following filters are used to verify that the user of the current
 | session is logged into this application. The "basic" filter easily
 | integrates HTTP Basic authentication for quick, simple checking.
 |
 */


Route::filter('auth', function()
{
    if(Auth::check()){
        $user = Auth::user();
    }else {
        $user = User::where('uanet', uanet())->first();
        
        if (count($user) <= 0) {
            return Redirect::guest('/');
        } else {
            Auth::login(User::find($user->id));
        }
    }
    
    if (Auth::guest()) return Redirect::guest('/')->withError('You do not have permission to access that!');
});

Route::filter('editAuth', function() {
    $instance = Instance::where('name', getInstanceName())->first();
    //Log them in, or not
    if(Auth::check()){
        $user = Auth::user();
    }else {
        $user = User::where('uanet', uanet())->first();
        
        if (count($user) <= 0) {
            return Redirect::guest('/')->withError('You do not have permission to access that!');
        } else {
            Auth::login(User::find($user->id));
        }
    }
    //Perform Instance Node Permission Check
    if(Auth::check() && ( $user->hasPermission($instance->id, 'edit') || $user->hasPermission(0, 'edit')) ){
        //Let editors in
    }else if(Auth::check() && ( $user->hasPermission($instance->id, 'admin') || $user->hasPermission(0, 'admin')) ){
        //Let admins in
    }else{
        return Redirect::guest('/')->withError('You do not have permission to access that!');
    }
});
    
    Route::filter('adminAuth', function() {
        $instance = Instance::where('name', getInstanceName())->first();
        //Log them in, or not
        if(Auth::check()){
            $user = Auth::user();
        }else {
            $user = User::where('uanet', uanet())->first();
            
            if (count($user) <= 0) {
                return Redirect::guest('/')->withError('You do not have permission to access that!');
            } else {
                Auth::login(User::find($user->id));
            }
        }
        //Perform Instance Node Permission Check
        if(Auth::check() && ( $user->hasPermission($instance->id, 'admin') || $user->hasPermission(0, 'admin')) ){
            //Let admins in
        }else{
            return Redirect::guest('/')->withError('You do not have permission to access that!');
        }
    });
        
        Route::filter('superAuth', function() {
            //Log them in, or not
            if(Auth::check()){
                $user = Auth::user();
            }else {
                $user = User::where('uanet', uanet())->first();
                
                if (count($user) <= 0) {
                    return Redirect::guest('/')->withError('You do not have permission to access that!');
                } else {
                    Auth::login(User::find($user->id));
                }
            }
            //Perform SuperAdmin Check
            if(Auth::check() && $user->isSuperAdmin()){
                //Let them in
            }else{
                return Redirect::guest('/')->withError('You do not have permission to access that!');
            }
        });
            
            Route::filter('registerSubmitter', function(){
                if(Auth::check()){
                    //logged in
                }else{
                    $user = User::where('uanet', uanet())->first();
                    
                    if (count($user) <= 0) {
                        //User does not exist, create new user
                        $user = new User;
                        $user->uanet = uanet();
                        $user->email = $_SERVER['mail'];
                        $user->first = $_SERVER['givenName'];
                        $user->last = $_SERVER['sn'];
                        $user->submitter = 1;
                        $user->save();
                        Auth::login(User::find($user->id));
                    } else {
                        //Log them in
                        Auth::login(User::find($user->id));
                    }
                }
            });
                
                
                Route::filter('auth.basic', function()
                {
                    return Auth::basic();
                });
                
                /*
                 |--------------------------------------------------------------------------
                 | Guest Filter
                 |--------------------------------------------------------------------------
                 |
                 | The "guest" filter is the counterpart of the authentication filters as
                 | it simply checks that the current user is not logged in. A redirect
                 | response will be issued if they are, which you may freely change.
                 |
                 */
                
                Route::filter('guest', function()
                {
                    if (Auth::check()) return Redirect::to('/');
                });
                
                /*
                 |--------------------------------------------------------------------------
                 | CSRF Protection Filter
                 |--------------------------------------------------------------------------
                 |
                 | The CSRF filter is responsible for protecting your application against
                 | cross-site request forgery attacks. If this special token in a user
                 | session does not match the one given in this request, we'll bail.
                 |
                 */
                
                Route::filter('csrf', function()
                {
                    if (Session::token() != Input::get('_token'))
                    {
                        throw new Illuminate\Session\TokenMismatchException;
                    }
                });