<?php

namespace App\Http\Controllers\Admin;

use SimpleXMLElement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ActiveLicenseController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $viewData = $this->loadViewData();

        if ($viewData) {
            $users = DB::table('users')->where('power_bi_email', $viewData['userEmail'])->get();
            $result = json_decode($users, true);
            $userID = $result[0]['id'];
            $landingPage = $result[0]['landing_page'];

            // $people_overview = 1;
            // $p_overview = DB::table('role_user')->where('user_id', $userID)->where('role_id', $people_overview)->first();
            // $roles = DB::table('role_user')->where('user_id', $userID)->get()->pluck('role_id');
            // $people_access = ($roles->contains(3)) || ($roles->contains(4)) || ($roles->contains(5)) || ($roles->contains(6)) || ($roles->contains(7)) || ($roles->contains(8)) || ($roles->contains(9)) || ($roles->contains(21)) || ($roles->contains(25)); 
            // $sales_access = ($roles->contains(10)) || ($roles->contains(11)) || ($roles->contains(12)) || ($roles->contains(13)) || ($roles->contains(20)) || ($roles->contains(22)) || ($roles->contains(29)) || ($roles->contains(30)) || ($roles->contains(31));
            // $finance_access =  ($roles->contains(14)) || ($roles->contains(15)) || ($roles->contains(16)) || ($roles->contains(23));
            // $operation_access = ($roles->contains(18)) || ($roles->contains(24)) || ($roles->contains(26)) || ($roles->contains(27)) || ($roles->contains(28)) || ($roles->contains(32)) || ($roles->contains(33)) || ($roles->contains(34));
            // $upload_access = ($roles->contains(21)) || ($roles->contains(22)) || ($roles->contains(23)) || ($roles->contains(24));
            

            $response = Http::get('https://api.powerbi.com/v1.0/myorg/groups/707beb0d-3ccb-4534-a186-1cc8e133d16d/users');
            //dd($response);


            // $response = Http::post('https://prod-apnortheast-a.online.tableau.com/api/3.9/auth/signin', [
            //     'credentials'=>[
            //         'name'=>'csr.ios@central-sugars.com.my',
            //         'password'=>'1234@csrbt3',
            //         'site'=>[
            //             'contentUrl'=>'csranalytics'
            //         ]
            //     ]
            // ]);

            $xml_auth = new SimpleXMLElement($response->body());
            $xml_auth_token = strval($xml_auth->credentials->attributes()->token);
            $xml_site_id = strval($xml_auth->credentials->site['id']);

            $response_users = Http::withHeaders([
                'X-Tableau-Auth' => $xml_auth_token
            ])->get('https://prod-apnortheast-a.online.tableau.com/api/3.9/sites/'.$xml_site_id.'/users');
            $xml_auth_users = new SimpleXMLElement($response_users->body());
            $xml_auth_users_users = ($xml_auth_users->users->user);    

            $userData = array();
            foreach($xml_auth_users_users as $user) {
                $userData[] = array(
                                'name' => $user->attributes()->name, 
                                'siteRole' => $user->attributes()->siteRole,
                                'lastLogin' => $user->attributes()->lastLogin
                            );
            }

            // $jobs = DB::table('jobs')->get();
            // dd($jobs);

            if($p_overview)
            {
                return view('admin.users.queue_active_license', ['xml_auth_users_users'=>$userData,'people_access'=>$people_access,'sales_access'=>$sales_access,'finance_access'=>$finance_access,'operation_access'=>$operation_access,'upload_access'=>$upload_access ,'userName'=>$viewData['userName'],'userID'=>$userID]);
            }
            else
            {
                return redirect()->route($landingPage);
            }

            }
        return redirect()->route('admin.users.queue_active_license');
    }
}
