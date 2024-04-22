<?php

namespace App\Http\Controllers;

use http\Client\Curl\User;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TokenStore\TokenCache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use \Session;

class AuthController extends Controller {
	
    public function signin(Request $request) {

        $inputs = $request->all();

        $user = DB::table('users')->where('email', $inputs['email'])->first();

        if ($user->email) {
        
            if (Hash::check($inputs['password'], $user->password)) {
                   
                session(['id' => $user->id]);
                session(['email' => $user->email]);
                session(['name' => $user->name]);
    
                if($user->landing_page) {
    
                    if(!empty($user->landing_page)) {
                        return redirect()->route($user->landing_page);
                        // return redirect()->route('services.logi_secure');
                    }
    
                    return view('auth.login', $inputs);
    
                }
    
            } else {
                // TODO : Error msg (Invalid Password)
                return view('auth.login', $inputs);
            }

        } else {
            // TODO : Error msg (Invalid User)
            return view('auth.login', $inputs);
        }
 
    }

    public function signout(Request $request) {
		
        $request->session()->flush();

        session()->forget('id');
        session()->forget('email');
        session()->forget('name');
		
        return redirect('/');
		
    }
	
}
