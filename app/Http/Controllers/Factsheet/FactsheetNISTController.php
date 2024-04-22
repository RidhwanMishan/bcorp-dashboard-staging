<?php

namespace App\Http\Controllers\Factsheet;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Async\Pool;
use App\Role;
use App\User;

class FactsheetNISTController extends Controller {

    public function __construct() { }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke() {

        //$viewData = $this->loadViewData();

        $GLOBALS['entity_name'] = "Natural Intelligence Solutions Technology Sdn Bhd"; //Can change depending on what company
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
            // Start Revenue
            $GLOBALS['account_name'] = "Revenue"; //Change
            $pool_revenue = Pool::create();
            $pool_revenue[] = async(function(){
                $value_revenue = DB::connection('redshift')->select("select entity_name,year,actual_amount
                from reporting.factsheet_key_figures
                where account_name = '".$GLOBALS['account_name']."'
                AND entity_name = '".$GLOBALS['entity_name']."'
                order by entity_name,year
            ");                
                return $value_revenue;
                DB::close($value_revenue);
            });

            $revenue_result = await($pool_revenue);
            if(!empty($revenue_result[0][0])) {
                $revenue = $revenue_result;
            } else {
                $nodata_json='[[{"entity_name": "No data","year": "0.0000","actual-amount": "0.0000"}]]';
                $revenue=json_decode($nodata_json)[0][0];
            }

            $revenue = $revenue_result[0];
            
            $revenue_year = [];
            foreach($revenue as $year) { $revenue_year[] = $year->year;}

            $revenue_amount = [];
            foreach($revenue as $amount) { $revenue_amount[] = $amount->actual_amount;}
            
            //dd($revenue_year);
            // End Revenue

            // Start COS
            $GLOBALS['account_name'] = "Cost of good sold"; //Change
            $pool_cos = Pool::create();
            $pool_cos[] = async(function(){
                $value_cos = DB::connection('redshift')->select("select entity_name,year,actual_amount
                from reporting.factsheet_key_figures
                where account_name = '".$GLOBALS['account_name']."'
                AND entity_name = '".$GLOBALS['entity_name']."'
                order by entity_name,year
            ");                
                return $value_cos;
                DB::close($value_cos);
            });

            $cos_result = await($pool_cos);
            if(!empty($cos_result[0][0])) {
                $cos = $cos_result;
            } else {
                $nodata_json='[[{"entity_name": "No data","year": "0.0000","actual-amount": "0.0000"}]]';
                $cos=json_decode($nodata_json)[0][0];
            }

            $cos = $cos_result[0];
            
            $cos_year = [];
            foreach($cos as $year) { $cos_year[] = $year->year;}

            $cos_amount = [];
            foreach($cos as $amount) { $cos_amount[] = $amount->actual_amount;}
            
            //dd($cos_year);
            // End COS

            // Start GPL
            $GLOBALS['account_name'] = "Gross profit/(loss)"; //Change
            $pool_gpl = Pool::create();
            $pool_gpl[] = async(function(){
                $value_gpl = DB::connection('redshift')->select("select entity_name,year,actual_amount
                from reporting.factsheet_key_figures
                where account_name = '".$GLOBALS['account_name']."'
                AND entity_name = '".$GLOBALS['entity_name']."'
                order by entity_name,year
            ");                
                return $value_gpl;
                DB::close($value_gpl);
            });

            $gpl_result = await($pool_gpl);
            if(!empty($gpl_result[0][0])) {
                $gpl = $gpl_result;
            } else {
                $nodata_json='[[{"entity_name": "No data","year": "0.0000","actual-amount": "0.0000"}]]';
                $gpl=json_decode($nodata_json)[0][0];
            }

            $gpl = $gpl_result[0];
            
            $gpl_year = [];
            foreach($gpl as $year) { $gpl_year[] = $year->year;}

            $gpl_amount = [];
            foreach($gpl as $amount) { $gpl_amount[] = $amount->actual_amount;}
            
            //dd($gpl_amount);
            // End GPL

            // Start OP
            $GLOBALS['account_name'] = "Operating expense"; //Change
            $pool_op = Pool::create();
            $pool_op[] = async(function(){
                $value_op = DB::connection('redshift')->select("select entity_name,year,actual_amount
                from reporting.factsheet_key_figures
                where account_name = '".$GLOBALS['account_name']."'
                AND entity_name = '".$GLOBALS['entity_name']."'
                order by entity_name,year
            ");                
                return $value_op;
                DB::close($value_op);
            });

            $op_result = await($pool_op);
            if(!empty($op_result[0][0])) {
                $op = $op_result;
            } else {
                $nodata_json='[[{"entity_name": "No data","year": "0.0000","actual-amount": "0.0000"}]]';
                $op=json_decode($nodata_json)[0][0];
            }

            $op = $op_result[0];
            
            $op_year = [];
            foreach($op as $year) { $op_year[] = $year->year;}

            $op_amount = [];
            foreach($op as $amount) { $op_amount[] = $amount->actual_amount;}
            
            //dd($op_year);
            // End OP

            // Start PAT
            $GLOBALS['account_name'] = "PAT"; //Change
            $pool_pat = Pool::create();
            $pool_pat[] = async(function(){
                $value_pat = DB::connection('redshift')->select("select entity_name,year,actual_amount
                from reporting.factsheet_key_figures
                where account_name = '".$GLOBALS['account_name']."'
                AND entity_name = '".$GLOBALS['entity_name']."'
                order by entity_name,year
            ");                
                return $value_pat;
                DB::close($value_pat);
            });

            $pat_result = await($pool_pat);
            if(!empty($pat_result[0][0])) {
                $pat = $pat_result;
            } else {
                $nodata_json='[[{"entity_name": "No data","year": "0.0000","actual-amount": "0.0000"}]]';
                $pat=json_decode($nodata_json)[0][0];
            }

            $pat = $pat_result[0];
            
            $pat_year = [];
            foreach($pat as $year) { $pat_year[] = $year->year;}

            $pat_amount = [];
            foreach($pat as $amount) { $pat_amount[] = $amount->actual_amount;}
            
            //dd($op_year);
            // End PAT

            // Start EBITDA
            $GLOBALS['account_name'] = "EBITDA"; //Change
            $pool_ebitda = Pool::create();
            $pool_ebitda[] = async(function(){
                $value_ebitda = DB::connection('redshift')->select("select entity_name,year,actual_amount
                from reporting.factsheet_key_figures
                where account_name = '".$GLOBALS['account_name']."'
                AND entity_name = '".$GLOBALS['entity_name']."'
                order by entity_name,year
            ");                
                return $value_ebitda;
                DB::close($value_ebitda);
            });

            $ebitda_result = await($pool_ebitda);
            if(!empty($ebitda_result[0][0])) {
                $ebitda = $ebitda_result;
            } else {
                $nodata_json='[[{"entity_name": "No data","year": "0.0000","actual-amount": "0.0000"}]]';
                $ebitda=json_decode($nodata_json)[0][0];
            }

            $ebitda = $ebitda_result[0];
            
            $ebitda_year = [];
            foreach($ebitda as $year) { $ebitda_year[] = $year->year;}

            $ebitda_amount = [];
            foreach($ebitda as $amount) { $ebitda_amount[] = $amount->actual_amount;}
            
            //dd($ebitda_year);
            // End EBITDA

            // OUR ACTIVITIES
            $GLOBALS['category'] = "Our Activities"; //Change
            $GLOBALS['fact'] = "Customer Base Region"; //Change
            $pool_actvt = Pool::create();
            $pool_actvt[] = async(function(){
                $value_actvt = DB::connection('redshift')->select("select fact, fact_value, fact_value_2,
                    replace(fact_value_2, '%', '')::numeric as value_num
                from reporting.factsheet
                where category = '".$GLOBALS['category']."'
                AND fact = '".$GLOBALS['fact']."'
                AND entity_name = '".$GLOBALS['entity_name']."'
                ORDER by value_num desc
            ");                
                return $value_actvt;
                DB::close($value_actvt);
            });

            $actvt_result = await($pool_actvt);
            if(!empty($actvt_result[0][0])) {
                $actvt = $actvt_result;
            } else {
                $nodata_json='[[{"fact": "No data","fact_value": "No data","fact_value_2": "No data"}]]';
                $actvt=json_decode($nodata_json)[0][0];
            }

            //$actvt = $actvt_result[0];

            $actvt_fact = [];
            foreach($actvt as $fact) { $actvt_fact[] = $fact->fact;}

            $actvt_fact_value = [];
            foreach($actvt as $fact_value) { $actvt_fact_value[] = $fact_value->fact_value;}

            $actvt_fact_value_2 = [];
            foreach($actvt as $fact_value_2) { $actvt_fact_value_2[] = $fact_value_2->fact_value_2;}

            //====================

            $GLOBALS['category'] = "Our Activities"; //Change
            $GLOBALS['fact'] = "Services"; //Change
            $pool_actvt2 = Pool::create();
            $pool_actvt2[] = async(function(){
                $value_actvt2 = DB::connection('redshift')->select("select fact, fact_value, fact_value_2,
                       replace(fact_value_2, '%', '')::numeric as value_num
                from reporting.factsheet
                where category = '".$GLOBALS['category']."'
                AND fact = '".$GLOBALS['fact']."'
                AND entity_name = '".$GLOBALS['entity_name']."'
                ORDER by value_num desc
            ");                
                return $value_actvt2;
                DB::close($value_actvt2);
            });

            $actvt2_result = await($pool_actvt2);
            if(!empty($actvt2_result[0][0])) {
                $actvt2 = $actvt2_result;
            } else {
                $nodata_json='[[{"entity_name": "No data","category": "No data","fact": "No data","fact": "No data","fact_value": "No data","fact_value_2": "No data"}]]';
                $actvt2=json_decode($nodata_json)[0][0];
            }

            $actvt2 = $actvt2_result[0];

            $actvt2_fact = [];
            foreach($actvt2 as $fact) { $actvt2_fact[] = $fact->fact;}

            $actvt2_fact_value = [];
            foreach($actvt2 as $fact_value) { $actvt2_fact_value[] = $fact_value->fact_value;}

            $actvt2_fact_value_2 = [];
            foreach($actvt2 as $fact_value_2) { $actvt2_fact_value_2[] = $fact_value_2->fact_value_2;}

            
            //dd($actvt_fact);
            //END OUR ACTIVITIES

            // KEY FACTS
            $GLOBALS['category'] = "Key Facts"; //Change

            $pool_keyfact = Pool::create();
            $pool_keyfact[] = async(function(){
                $value_keyfact = DB::connection('redshift')->select("select fact, details, fact_value_2,
                       (SELECT sort_num FROM reporting.factsheet WHERE category='".$GLOBALS['category']."' AND entity_name='".$GLOBALS['entity_name']."' AND fact=cmb.fact LIMIT 1) AS sort_num
                FROM (
                    select fact, listagg(fact_value, '||') within group (order by sort_num) as details ,fact_value_2
                    from reporting.factsheet
                    where category = '".$GLOBALS['category']."' and
                    entity_name = '".$GLOBALS['entity_name']."'
                    group by fact,fact_value_2) cmb
                ORDER BY sort_num
            ");                
                return $value_keyfact;
                DB::close($value_keyfact);
            });

            $keyfact_result = await($pool_keyfact);
            if(!empty($keyfact_result[0][0])) {
                $keyfact = $keyfact_result;
            } else {
                $nodata_json='[[{"entity_name": "No data","category": "No data","fact": "No data","fact": "No data","fact_value": "No data","fact_value_2": "No data"}]]';
                $keyfact=json_decode($nodata_json)[0][0];
            }

            $keyfact = $keyfact_result[0];

            $keyfact_fact = [];
            foreach($keyfact as $fact) { $keyfact_fact[] = $fact->fact;}

            $keyfact_fact_value = [];
            foreach($keyfact as $details) { $keyfact_fact_value[] = $details->details;}
            
            //dd($keyfact);
            //END KEY FACTS

            // MANAGEMENT & ACHIEVEMENTS
            $GLOBALS['category'] = "Management"; //Change
            $pool_management = Pool::create();
            $pool_management[] = async(function(){
                $value_management = DB::connection('redshift')->select("select fact, fact_value, fact_value_2
                from reporting.factsheet
                where category = '".$GLOBALS['category']."'
                AND entity_name = '".$GLOBALS['entity_name']."'
                ORDER by sort_num asc
            ");                
                return $value_management;
                DB::close($value_management);
            });

            $management_result = await($pool_management);

            $management = $management_result[0];

            $management_fact = [];
            foreach($management as $fact) { $management_fact[] = $fact->fact;}

            $management_fact_value = [];
            foreach($management as $fact_value) { $management_fact_value[] = $fact_value->fact_value; }

            //==========================

            $GLOBALS['category'] = "Achievements"; //Change
            $pool_achievements = Pool::create();
            $pool_achievements[] = async(function(){
                $value_achievements = DB::connection('redshift')->select("select fact, fact_value, fact_value_2
                from reporting.factsheet
                where category = '".$GLOBALS['category']."'
                AND entity_name = '".$GLOBALS['entity_name']."'
                ORDER by sort_num asc
            ");                
                return $value_achievements;
                DB::close($value_achievements);
            });

            $achievements_result = await($pool_achievements);
            if(!empty($achievements_result[0][0])) {
                $achievements = $achievements_result;
            } else {
                $nodata_json='[{"fact":"No data","fact_value": "No data"}]';
                $achievements=json_decode($nodata_json);
            }

            //$achievements = $achievements_result[0];

            $achievements_fact = [];
            foreach($achievements as $fact) { $achievements_fact[] = $fact->fact; }

            $achievements_fact_value = [];
            foreach($achievements as $fact_value) { $achievements_fact_value[] = $fact_value->fact_value; }
            
            //dd($achievements);
            //END MANAGEMENT & ACHIEVEMENTS

            // SOUND BITES
            $GLOBALS['category'] = "Sound Bites"; //Change
            $pool_sound = Pool::create();
            $pool_sound[] = async(function(){
                $value_sound = DB::connection('redshift')->select("select fact, fact_value, fact_value_2
                from reporting.factsheet
                where category = '".$GLOBALS['category']."'
                AND entity_name = '".$GLOBALS['entity_name']."'
                ORDER by sort_num asc
            ");                
                return $value_sound;
                DB::close($value_sound);
            });

            $sound_result = await($pool_sound);
            if(!empty($sound_result[0][0])) {
                $sound = $sound_result;
            } else {
                $nodata_json='[[{"entity_name": "No data","category": "No data","fact": "No data","fact": "No data","fact_value": "No data","fact_value_2": "No data"}]]';
                $sound=json_decode($nodata_json)[0][0];
            }

            $sound = $sound_result[0];

            $sound_fact = [];
            foreach($sound as $fact) { $sound_fact[] = $fact->fact;}

            $sound_fact_value = [];
            foreach($sound as $fact_value) { $sound_fact_value[] = $fact_value->fact_value;}
            
            //dd($keyfact);
            //END SOUND BITES
           
            if($r_berjaya) {
                return view('factsheet.nist', [
                'update_date'=>$update_date,
                'berjaya_access'=>$berjaya_access, 
                'hospitality_access'=>$hospitality_access,
                'property_access'=>$property_access, 
                'retail_access'=>$retail_access, 
                'services_access'=>$services_access,
                'userName'=>session('email'),
                'userID'=>$userID,
                'factsheet'=>true,
                'revenue_year'=>$revenue_year,
                'revenue_amount'=>$revenue_amount,
                'cos_year'=>$cos_year,
                'cos_amount'=>$cos_amount,
                'gpl_year'=>$gpl_year,
                'gpl_amount'=>$gpl_amount,
                'op_year'=>$op_year,
                'op_amount'=>$op_amount,
                'pat_year'=>$pat_year,
                'pat_amount'=>$pat_amount,
                'ebitda_year'=>$ebitda_year,
                'ebitda_amount'=>$ebitda_amount,
                'keyfact_fact'=>$keyfact_fact,
                'keyfact_fact_value'=>$keyfact_fact_value,
                'management_fact'=>$management_fact,
                'management_fact_value'=>$management_fact_value,
                'achievements_fact'=>$achievements_fact,
                'achievements_fact_value'=>$achievements_fact_value,
                'actvt_fact'=>$actvt_fact,
                'actvt_fact_value'=>$actvt_fact_value,
                'actvt_fact_value_2'=>$actvt_fact_value_2,
                'actvt2_fact'=>$actvt2_fact,
                'actvt2_fact_value'=>$actvt2_fact_value,
                'actvt2_fact_value_2'=>$actvt2_fact_value_2,
                'sound_fact'=>$sound_fact,
                'sound_fact_value'=>$sound_fact_value,
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