<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Async\Pool;
use App\Role;
use App\User;

class FactsheetController extends Controller {

    public function __construct() { }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke() {

        //$viewData = $this->loadViewData();

        $GLOBALS['entity_name'] = "Berjaya Enviro Holdings Sdn Bhd"; //Can change depending on what company
        $GLOBALS['fiscalyear'] = ""; //Value is calculated below
        
        if (session('email')) {

            $users = DB::table('users')->where('email', session('email'))->first();
            //$result = json_decode($users, true);
            $userID = $users->id;
            $landingPage = $users->landing_page;

            $retail_berjaya = 7;
            $r_berjaya = DB::table('role_user')->where('user_id', $userID)->where('role_id', $retail_berjaya)->first();
            $roles = DB::table('role_user')->where('user_id', $userID)->get()->pluck('role_id');

            // $retail_access = ($roles->contains(3)) || ($roles->contains(4));

            $berjaya_access = false;
            $hospitality_access = false;     
            $property_access = false;
            $retail_access = false;
            $services_access = false;

            $get_roles = DB::table('role_user')->where('user_id', $userID)->get();
            foreach($get_roles as $role) {

                if ($role->role_id == 7) {
                    $berjaya_access = true;
                }

                if ($role->role_id == 5) {
                    $hospitality_access = true;
                }

                if ($role->role_id == 6) {
                    $property_access = true;
                }

                if ($role->role_id == 3) {
                    $retail_access = true;
                }

                if ($role->role_id == 4) {
                    $services_access = true;
                }

            }

            // Start Get Fiscal Year
            $pool_fiscalyear = Pool::create();
            $pool_fiscalyear[] = async(function(){
                $value_fiscalyear = DB::connection('redshift')->select("SELECT distinct(fiscal_year)
                FROM core.dim_calendar_month
                WHERE year=date_part_year(dateadd(hour, 8, getdate())::date)
                  AND month=date_part(month,dateadd(hour, 8, getdate())::date);");                
                return $value_fiscalyear;
                DB::close($value_fiscalyear);
            });

            $fiscalyear_result = await($pool_fiscalyear);
            $GLOBALS['fiscalyear'] = $fiscalyear_result[0][0]->{"fiscal_year"};
            //dd($fiscalyear);
            // End

            // UPDATE DATE
            $update_date = DB::connection('redshift')->select("SELECT last_date_of_month FROM (
                SELECT last_date_of_month FROM reporting.pnl_max_fiscal_with_data rep
					INNER JOIN core.dim_calendar_day cal ON rep.max_data_date=cal.date
                WHERE entity_name = '".$GLOBALS['entity_name']."'
                    AND rep.fiscal_year = '".$GLOBALS['fiscalyear']."'
                UNION ALL
                SELECT '1900-01-01'::DATE
            ) mx ORDER BY last_date_of_month DESC
            LIMIT 1
            ;")[0]->last_date_of_month;
            $update_date=\Carbon\Carbon::parse($update_date)->format('d/m/Y');
            //dd($update_date);


            // KEY FIGURES
            $account_name = array('Revenue' => 'revenue',
                'Cost of good sold' => 'cos',
                'Gross profit/(loss)' => 'gpl',
                'Operating expense' => 'op',
                'PAT' => 'pat',
                'EBITDA' => 'ebitda');

            foreach ($account_name as $account => $shortcode) {
                $GLOBALS['account'] = $account;

                $pool = Pool::create();
                $pool[] = async(function(){
                    $value = DB::connection('redshift')->select("select entity_name,year,actual_amount
                    from reporting.factsheet_key_figures
                    where account_name = '".$GLOBALS['account']."'
                    AND entity_name = '".$GLOBALS['entity_name']."'
                    order by entity_name,year
                ");                
                    return $value;
                    DB::close($value);
                });

                $shortcode_result = await($pool);
                if(!empty($shortcode_result[0][0])) {
                    $shortcode = $shortcode_result;
                } else {
                    $nodata_json='[[{"entity_name": "No data","year": "0.0000","actual-amount": "0.0000"}]]';
                    $shortcode=json_decode($nodata_json)[0][0];
                }

                //echo $account;
                //dd($shortcode);
            }
            
           
            if($r_berjaya) {
                return view('factsheet', [
                'update_date'=>$update_date,
                'berjaya_access'=>$berjaya_access, 
                'hospitality_access'=>$hospitality_access,
                'property_access'=>$property_access, 
                'retail_access'=>$retail_access, 
                'services_access'=>$services_access,
                'userName'=>session('email'),
                'userID'=>$userID,
                'factsheet'=>true,
                ]);
            } else {
                return redirect()->route($landingPage);
            }

        }
        return redirect()->route('main');

    }

    public function urlFetch($key)
    {
        echo $key;
    }

}