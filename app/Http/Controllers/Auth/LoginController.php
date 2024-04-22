<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
//    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectTo()
    {
        $viewData = $this->loadViewData();
        $users = DB::table('users')->where('email', $viewData['userEmail'])->get();
//        $result = json_decode($users, true);
//        $landingPage = $result[0]['landing_page'];
        if($users->hasRole('admin')){
            $this->redirectTo = route('admin.users.index');
            return $this->redirectTo;
        }

//        if(Auth::user()->hasRole('admin')){
//            $this->redirectTo = route('admin.users.index');
//            return $this->redirectTo;
//        }

//        $userID = Auth::id();
//        $landingPage = DB::table('users')->where('id', $userID)->value('landing_page');
//        return redirect()->route($landingPage);

        $this->redirectTo = '/';
        return $this->redirectTo;
    }

}
