<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use Gate;
use SimpleXMLElement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Spatie\Async\Pool;
use App\Jobs\ProcessPermissiontableau;
use App\Jobs\DeletePermissiontableau;

class UsersController extends Controller {

    public function __construct() { }

    /**
     * Display a listing of
     * @return \Illuminate\Http\Re the resource.
     *sponse
     */
    public function index(Request $request) {
        $inputs = $request->all();
        if (session('email')) {

            //$users = User::all();
            $users = User::with('roles');
            if(!empty($inputs['name'])){
                $users = $users->where('name', 'LIKE', '%'.trim($inputs['name']).'%');
            }
            $users = $users->orderBy('name', 'asc')->paginate(10)->appends($inputs);    
            //$users = User::with('roles')->get();
            //$users = User::with('roles')->orderBy('name', 'asc')->paginate(5);

            $user = DB::table('users')->where('email', session('email'))->first();
            // $result = json_decode($user, true);
            $userID = $user->id;
            $landingPage = $user->landing_page;

            $admin = 1;
            $admin = DB::table('role_user')->where('user_id', $userID)->where('role_id', $admin)->first();

            if($admin) {
                return view('admin.users.index', ['userName' => session('email'), 'users' => $users]);
            } else {
                return redirect()->route($landingPage);
            }

        }

        return redirect()->route('main');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        //$viewData = $this->loadViewData();
        if (session('email')) {

            $roles = Role::all();

            $user = DB::table('users')->where('email', session('email'))->first();
            //$result = json_decode($user, true);
            $userID = $user->id;
            $landingPage = $user->landing_page;

            $admin = 1;
            $admin = DB::table('role_user')->where('user_id', $userID)->where('role_id', $admin)->first();

            if($admin) {
                return view('admin.users.create')->with([
                    'roles'=>$roles,
                    'userName'=>session('email')
                ]);
            } else {
                return redirect()->route($landingPage);
            }

        }

        return redirect()->route('main');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $validated = $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:App\User,email',
            'password'=>'required',
            'landing_page'=>'required',
        ]);

        if($validated && $request->landing_page == ""){
            $request->session()->flash('error', 'There was an error creating the user. Please ensure you have fill all details.');

            return redirect()->route('admin.users.create');
        }

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'landing_page'=>$request->landing_page,
            'password'=>Hash::make($request->password),
        ]);

        $roles = $user->roles()->sync($request->roles);
        $request->session()->flash('success', $request->name . ' profile has been successfully created');
        return redirect()->route('admin.users.index');

    }

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function show($id) {

        //$viewData = $this->loadViewData();

        if (session('email')) {

            $user_show = User::find($id);

            $user = DB::table('users')->where('email', session('email'))->first();
            //$result = json_decode($user, true);
            $userID = $user->id;
            $landingPage = $user->landing_page;

            $admin = 1;
            $admin = DB::table('role_user')->where('user_id', $userID)->where('role_id', $admin)->first();

            if($admin) {
                return view('admin.users.show', ['userName'=>session('email'),'user' => $user_show]);
            } else {
                return redirect()->route($landingPage);
            }

        }

        return redirect()->route('main');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user) {

        //$viewData = $this->loadViewData();

        if (session('email')) {

            $roles = Role::all();

            $user_login = DB::table('users')->where('email', session('email'))->first();
            //$result = json_decode($user_login, true);
            $userID = $user_login->id;
            $landingPage = $user_login->landing_page;

            $admin = 1;
            $admin = DB::table('role_user')->where('user_id', $userID)->where('role_id', $admin)->first();

            if($admin) {
                return view('admin.users.edit')->with([
                    'user'=>$user,
                    'roles'=>$roles,
                    'userName'=>session('email')
                ]);
            } else {
                return redirect()->route($landingPage);
            }

        }

        return redirect()->route('main');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user) {

        $user->roles()->sync($request->roles);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password == '') {
            $user->password = $user->password;
        } else {
            $user->password = Hash::make($request->password);
        }

        // $user->password = Hash::make($request->password);
        $user->landing_page = $request->landing_page;

        if($user->save()) {
            $request->session()->flash('success','Information for user '. $user->name . ' has been successfully updated');
        } else {
            $request->session()->flash('error', 'There was an error updating the user');
        }

        return redirect()->route('admin.users.index');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user) {

        $user->roles()->detach();
        $user->delete();
        return redirect()->route('admin.users.index');

    }

}
