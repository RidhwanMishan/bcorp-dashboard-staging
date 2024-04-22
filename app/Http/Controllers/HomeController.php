<?php

namespace App\Http\Controllers;

use Microsoft\Graph\Graph;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() { }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        // From Microsoft Graph
        $viewData = $this->loadViewData();

        if($viewData) {
            
            $users = DB::table('users')->where('power_bi_email', $viewData['userEmail'])->get();
            if($users) {

                $result = json_decode($users, true);
                $have_landingPage = isset($result[0]['landing_page']);

                if($have_landingPage) {

                    $landingPage = $result[0]['landing_page'];

                    if(!empty($landingPage)) {
                        return redirect()->route($landingPage);
                    }

                    return view('auth.login', $viewData);

                }

            }

        } else {

            return view('auth.login');

        }

    }

}

