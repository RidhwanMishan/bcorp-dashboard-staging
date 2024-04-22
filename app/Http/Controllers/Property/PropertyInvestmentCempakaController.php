<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Async\Pool;


class PropertyInvestmentCempakaController extends Controller {

    public function __construct() { }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke() {

        //$viewData = $this->loadViewData();

        $GLOBALS['entity_name'] = "Cempaka Properties Sdn Bhd"; //Can change depending on what company

        $GLOBALS['fiscalyear'] = ""; //Value is calculated below
        $GLOBALS['breakdown_account_name'] = ""; //Don't delete or alter, used below
        $GLOBALS['parent_account_name'] = ""; //Don't delete or alter, used below
        $GLOBALS['detailed_breakdown_collection'] = array(); //Don't delete or alter, used for breakdown detailed graphs (when pie chart clicked)        

        if (session('email')) {

            $users = DB::table('users')->where('email', session('email'))->first();
            //$result = json_decode($users, true);
            $userID = $users->id;
            $landingPage = $users->landing_page;

            $retail_berjaya = 6;
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
            
            // Start REVENUE
            $pool_revenue = Pool::create();
            $pool_revenue[] = async(function(){
                $value_revenue = DB::connection('redshift')->select("SELECT entity_name, sum(dashboard_amount) AS ytd_revenue,
                sum(target_amount) as ytd_target,
                sum(target_amount)-sum(dashboard_amount) as ytd_variance
            FROM reporting.pnl_monthly
            WHERE entity_name='".$GLOBALS['entity_name']."'
                AND fiscal_year=".$GLOBALS['fiscalyear']."
                AND account_name='Revenue'
            GROUP BY entity_name");                
                return $value_revenue;
                DB::close($value_revenue);
            });

            $revenue_result = await($pool_revenue);
            if(!empty($revenue_result[0][0])) {
                $revenue = $revenue_result[0][0];
            } else {
                $nodata_json='[[{"entity_name": "No data","ytd_revenue": "0.0000","ytd_target": "0.0000","ytd_variance": "0.0000"}]]';
                $revenue=json_decode($nodata_json)[0][0];
            }
            //dd($revenue);
            // End REVENUE

            // Start REVENUE SMALL OVERVIEW GRAPH
            $pool_revenue_overview_graph = Pool::create();
            $pool_revenue_overview_graph[] = async(function(){
                $value_revenue_overview_graph = DB::connection('redshift')->select("SELECT 
                entity_name,
                date,
                dashboard_amount as dashboard_amount,
                target_amount as target_amount
                FROM reporting.pnl_monthly
                WHERE fiscal_year=".$GLOBALS['fiscalyear']."
                AND account_name = 'Revenue'
                AND entity_name = '".$GLOBALS['entity_name']."'
                order by entity_name, date
                ");                
                return $value_revenue_overview_graph;
                DB::close($value_revenue_overview_graph);
            });

            $revenue_overview_graph_result = await($pool_revenue_overview_graph);
            $revenue_overview_graph = $revenue_overview_graph_result[0];
            //dd($revenue_overview_graph);
            // End REVENUE SMALL OVERVIEW  GRAPH

            
            // Start Revenue Breakdown
            $pool_revenue_breakdown = Pool::create();
            $pool_revenue_breakdown[] = async(function(){
                $value_revenue_breakdown = DB::connection('redshift')->select("SELECT account_name, sum(dashboard_amount) AS revenue
                FROM reporting.pnl_monthly
                WHERE entity_name='".$GLOBALS['entity_name']."'
                    AND fiscal_year=".$GLOBALS['fiscalyear']."
                    AND parent_account_name='Revenue'
                GROUP BY account_name
                Having revenue > 0;");                
                return $value_revenue_breakdown;
                DB::close($value_revenue_breakdown);
            });

            $revenue_breakdown_result = await($pool_revenue_breakdown);
            $revenue_breakdown = $revenue_breakdown_result[0];

            $revenue_breakdown_labels=[];
            $revenue_breakdown_data=[];
            $revenue_breakdown_total = 0;

            foreach($revenue_breakdown as $breakdown){
                $revenue_breakdown_total += $breakdown->revenue; 
            }

            foreach($revenue_breakdown as $breakdown){
                array_push($revenue_breakdown_labels, $breakdown->account_name);

                $percentage_breakdown = ($breakdown->revenue / $revenue_breakdown_total) * 100; 
                $percentage_breakdown = round($percentage_breakdown, 2);
                array_push($revenue_breakdown_data, $percentage_breakdown);
            }

            // End Revenue Breakdown

            //Start Revenue - Detailed Data - Monthly / Actual
            $pool_revenue_detailed_monthly = Pool::create();
            $pool_revenue_detailed_monthly[] = async(function(){
                $value_revenue_detailed_monthly = DB::connection('redshift')->select("SELECT entity_name,fiscal_month,month_year, SUM(dashboard_amount) AS actual_month, SUM(target_amount) AS target_amount  
                FROM reporting.pnl_monthly
                WHERE account_name = 'Revenue'
                    AND entity_name = '".$GLOBALS['entity_name']."'
                GROUP BY entity_name, fiscal_month, month_year
                ORDER BY entity_name, fiscal_month;
                ");                
                return $value_revenue_detailed_monthly;
                DB::close($value_revenue_detailed_monthly);
            });

            $revenue_detailed_monthly_result = await($pool_revenue_detailed_monthly);
            $revenue_detailed_monthly = $revenue_detailed_monthly_result[0];

            $revenue_detailed_monthly_labels=[];
            $revenue_detailed_monthly_data_actual=[];
            $revenue_detailed_monthly_data_target=[];

            foreach($revenue_detailed_monthly as $breakdown){
                array_push($revenue_detailed_monthly_labels, $breakdown->month_year);
                array_push($revenue_detailed_monthly_data_actual, $breakdown->actual_month);
                array_push($revenue_detailed_monthly_data_target, $breakdown->target_amount);
 
            }

            //dd($revenue_detailed_monthly_actual_target_data_target);
            // End Revenue - Detailed Data - Monthly / Actual

            //Start Revenue - Detailed Data - Cumulative Actual / Target
            $pool_revenue_detailed_cumulative = Pool::create();
            $pool_revenue_detailed_cumulative[] = async(function(){
                $value_revenue_detailed_cumulative = DB::connection('redshift')->select("SELECT entity_name, month_year, fiscal_month, act_amount, trgt_amount
                FROM reporting.pnl_cumulative_per_entity
                WHERE entity_name = '".$GLOBALS['entity_name']."'
                    AND fiscal_year = '".$GLOBALS['fiscalyear']."'
                    AND account_name = 'Revenue'
                ORDER by fiscal_month
                ");                
                return $value_revenue_detailed_cumulative;
                DB::close($value_revenue_detailed_cumulative);
            });

            $revenue_detailed_cumulative_result = await($pool_revenue_detailed_cumulative);
            $revenue_detailed_cumulative = $revenue_detailed_cumulative_result[0];

            $revenue_detailed_cumulative_data_labels=[];
            $revenue_detailed_cumulative_data_actual=[];
            $revenue_detailed_cumulative_data_target=[];

            //dd($revenue_detailed_cumulative);

            foreach($revenue_detailed_cumulative as $breakdown){
                array_push($revenue_detailed_cumulative_data_labels, $breakdown->month_year);
                array_push($revenue_detailed_cumulative_data_actual, $breakdown->act_amount);
                array_push($revenue_detailed_cumulative_data_target, $breakdown->trgt_amount);
            }


            //dd($revenue_detailed_cumulative_data_target);
            // End Revenue - Detailed Data - Cumulative Actual / Target

            //Start Revenue - Detailed Data - quarterly Actual / Target
            $pool_revenue_detailed_quarterly = Pool::create();
            $pool_revenue_detailed_quarterly[] = async(function(){
                $value_revenue_detailed_quarterly = DB::connection('redshift')->select("SELECT entity_name, 
                pm.fiscal_quarter as fq, 
                fiscal_quarter_str as fiscal_quarter,
                SUM(dashboard_amount) AS actual_amount, 
                SUM(target_amount) AS target_amount  
                FROM reporting.pnl_monthly pm
                LEFT JOIN core.dim_calendar_month cm on pm.date = cm.date
                WHERE account_name = 'Revenue'
                AND entity_name = '".$GLOBALS['entity_name']."'
                GROUP BY entity_name,fq,fiscal_quarter_str
                ORDER BY entity_name,fq
                ");                
                return $value_revenue_detailed_quarterly;
                DB::close($value_revenue_detailed_quarterly);
            });

            $revenue_detailed_quarterly_result = await($pool_revenue_detailed_quarterly);
            $revenue_detailed_quarterly = $revenue_detailed_quarterly_result[0];

            $revenue_detailed_quarterly_data_labels=[];
            $revenue_detailed_quarterly_data_actual=[];
            $revenue_detailed_quarterly_data_target=[];

            //dd($revenue_detailed_quarterly);

            foreach($revenue_detailed_quarterly as $breakdown){
                array_push($revenue_detailed_quarterly_data_labels, $breakdown->fiscal_quarter);
                array_push($revenue_detailed_quarterly_data_actual, $breakdown->actual_amount);
                array_push($revenue_detailed_quarterly_data_target, $breakdown->target_amount);
            }


            //dd($revenue_detailed_cumulative_data_target);
            // End Revenue - Detailed Data - quarterly Actual / Target

            //Start Revenue - Detailed Data -  Monthly Variance
            $pool_revenue_detailed_monthly_variance = Pool::create();
            $pool_revenue_detailed_monthly_variance[] = async(function(){
                $value_revenue_detailed_monthly_variance = DB::connection('redshift')->select("SELECT entity_name, month_year, fiscal_month, sum(target_amount)-sum(dashboard_amount) as monthly_variance   
                FROM reporting.pnl_monthly
                WHERE account_name = 'Revenue'
                    AND entity_name = '".$GLOBALS['entity_name']."'
                GROUP BY entity_name, fiscal_month, month_year
                ORDER BY fiscal_month
                ");                
                return $value_revenue_detailed_monthly_variance;
                DB::close($value_revenue_detailed_monthly_variance);
            });

            $revenue_detailed_monthly_variance_result = await($pool_revenue_detailed_monthly_variance);
            $revenue_detailed_monthly_variance = $revenue_detailed_monthly_variance_result[0];

            $revenue_detailed_monthly_variance_data_labels=[];
            $revenue_detailed_monthly_variance_data_actual=[];

            //dd($revenue_detailed_monthly_variance);

            foreach($revenue_detailed_monthly_variance as $breakdown){
                array_push($revenue_detailed_monthly_variance_data_labels, $breakdown->month_year);
                array_push($revenue_detailed_monthly_variance_data_actual, $breakdown->monthly_variance);
            }


            //dd($revenue_detailed_cumulative_data_target);
            // End Revenue - Detailed Data -  Monthly Variance

            //Start Revenue - Detailed Data -  Cumatively Variance
            $pool_revenue_detailed_cumatively_variance = Pool::create();
            $pool_revenue_detailed_cumatively_variance[] = async(function(){
                $value_revenue_detailed_cumatively_variance = DB::connection('redshift')->select("SELECT entity_name, month_year, fiscal_month, cumulative_variance
            FROM reporting.pnl_cumulative_per_entity
            WHERE entity_name = '".$GLOBALS['entity_name']."'
                AND fiscal_year = '".$GLOBALS['fiscalyear']."'
                AND account_name = 'Revenue'
            ORDER by fiscal_month
                ");                
                return $value_revenue_detailed_cumatively_variance;
                DB::close($value_revenue_detailed_cumatively_variance);
            });

            $revenue_detailed_cumatively_variance_result = await($pool_revenue_detailed_cumatively_variance);
            $revenue_detailed_cumatively_variance = $revenue_detailed_cumatively_variance_result[0];

            $revenue_detailed_cumatively_variance_data_labels=[];
            $revenue_detailed_cumatively_variance_data_actual=[];

            //dd($revenue_detailed_cumatively_variance);

            foreach($revenue_detailed_cumatively_variance as $breakdown){
                array_push($revenue_detailed_cumatively_variance_data_labels, $breakdown->month_year);
                array_push($revenue_detailed_cumatively_variance_data_actual, $breakdown->cumulative_variance);
            }


            //dd($revenue_detailed_cumulative_data_target);
            // End Revenue - Detailed Data -  Cumatively Variance


            //Start Revenue - Detailed Data -  quarterly Variance
            $pool_revenue_detailed_quarterly_variance = Pool::create();
            $pool_revenue_detailed_quarterly_variance[] = async(function(){
                $value_revenue_detailed_quarterly_variance = DB::connection('redshift')->select("SELECT 
                entity_name, 
                pm.fiscal_quarter as fq, 
                fiscal_quarter_str as fiscal_quarter, 
                sum(target_amount)-sum(dashboard_amount) as quarterly_variance   
                FROM reporting.pnl_monthly pm
                LEFT JOIN core.dim_calendar_month cm on pm.date = cm.date
                WHERE account_name = 'Revenue'
                AND entity_name = '".$GLOBALS['entity_name']."'
                GROUP BY entity_name,fq,fiscal_quarter_str
                ORDER BY entity_name,fq
                ");                
                return $value_revenue_detailed_quarterly_variance;
                DB::close($value_revenue_detailed_quarterly_variance);
            });

            $revenue_detailed_quarterly_variance_result = await($pool_revenue_detailed_quarterly_variance);
            $revenue_detailed_quarterly_variance = $revenue_detailed_quarterly_variance_result[0];

            $revenue_detailed_quarterly_variance_data_labels=[];
            $revenue_detailed_quarterly_variance_data_actual=[];

            //dd($revenue_detailed_quarterly_variance);

            foreach($revenue_detailed_quarterly_variance as $breakdown){
                array_push($revenue_detailed_quarterly_variance_data_labels, $breakdown->fiscal_quarter);
                array_push($revenue_detailed_quarterly_variance_data_actual, $breakdown->quarterly_variance);
            }

            //dd($revenue_detailed_cumulative_data_target);
            // End Revenue - Detailed Data -  quarterly Variance
            

            // Start COS
            $pool_cos = Pool::create();
            $pool_cos[] = async(function(){
                $value_cos = DB::connection('redshift')->select("SELECT entity_name, sum(dashboard_amount) AS ytd_revenue,
                sum(target_amount) as ytd_target,
                sum(target_amount)-sum(dashboard_amount) as ytd_variance
         FROM reporting.pnl_monthly
         WHERE entity_name='".$GLOBALS['entity_name']."'
                AND fiscal_year=".$GLOBALS['fiscalyear']."
             AND account_name='Cost of Sales'
         GROUP BY entity_name");                
                return $value_cos;
                DB::close($value_cos);
            });

            $cos_result = await($pool_cos);
            if(!empty($cos_result[0][0])) {
                $cos = $cos_result[0][0];
            } else {
                $nodata_json='[[{"entity_name": "No data","ytd_revenue": "0.0000","ytd_target": "0.0000","ytd_variance": "0.0000"}]]';
                $cos=json_decode($nodata_json)[0][0];
            }
            //dd($cos);
            // End COS

            // Start COS SMALL OVERVIEW  GRAPH
            $pool_cos_overview_graph = Pool::create();
            $pool_cos_overview_graph[] = async(function(){
                $value_cos_overview_graph = DB::connection('redshift')->select("SELECT 
                entity_name,
                date,
                dashboard_amount as dashboard_amount,
                target_amount as target_amount
                FROM reporting.pnl_monthly
                WHERE fiscal_year=".$GLOBALS['fiscalyear']."
                AND account_name = 'Cost of Sales'
                AND entity_name = '".$GLOBALS['entity_name']."'
                order by entity_name, fiscal_month
                ");                
                return $value_cos_overview_graph;
                DB::close($value_cos_overview_graph);
            });

            $cos_overview_graph_result = await($pool_cos_overview_graph);
            $cos_overview_graph = $cos_overview_graph_result[0];
            //dd($revenue_overview_graph);
            // End COS SMALL OVERVIEW  GRAPH

            // Start COS Breakdown
            $pool_cos_breakdown = Pool::create();
            $pool_cos_breakdown[] = async(function(){
                $value_cos_breakdown = DB::connection('redshift')->select("SELECT account_name, sum(dashboard_amount) AS revenue
                FROM reporting.pnl_monthly
                WHERE entity_name='".$GLOBALS['entity_name']."'
                    AND fiscal_year=".$GLOBALS['fiscalyear']."
                    AND parent_account_name='Cost of Sales'
                GROUP BY account_name
                Having revenue > 0;
                ");                
                return $value_cos_breakdown;
                DB::close($value_cos_breakdown);
            });

            $cos_breakdown_result = await($pool_cos_breakdown);
            $cos_breakdown = $cos_breakdown_result[0];

            $cos_breakdown_labels=[];
            $cos_breakdown_data=[];
            $cos_breakdown_total = 0;

            foreach($cos_breakdown as $breakdown){
                $cos_breakdown_total += $breakdown->revenue; 
            }

            foreach($cos_breakdown as $breakdown){
                array_push($cos_breakdown_labels, $breakdown->account_name);

                $percentage_breakdown = ($breakdown->revenue / $cos_breakdown_total) * 100; 
                $percentage_breakdown = round($percentage_breakdown, 2);
                array_push($cos_breakdown_data, $percentage_breakdown);
            }

            // End COS Breakdown

             //Start COS - Detailed Data - Monthly / Actual
             $pool_cos_detailed_monthly = Pool::create();
             $pool_cos_detailed_monthly[] = async(function(){
                 $value_cos_detailed_monthly = DB::connection('redshift')->select("SELECT entity_name,fiscal_month,month_year, SUM(dashboard_amount) AS actual_month, SUM(target_amount) AS target_amount  
                 FROM reporting.pnl_monthly
                 WHERE account_name = 'Cost of Sales'
                     AND entity_name = '".$GLOBALS['entity_name']."'
                 GROUP BY entity_name, fiscal_month, month_year
                 ORDER BY entity_name, fiscal_month
                 ");
                 return $value_cos_detailed_monthly;
                 DB::close($value_cos_detailed_monthly);
             });
 
             $cos_detailed_monthly_result = await($pool_cos_detailed_monthly);
             $cos_detailed_monthly = $cos_detailed_monthly_result[0];
 
             $cos_detailed_monthly_labels=[];
             $cos_detailed_monthly_data_actual=[];
             $cos_detailed_monthly_data_target=[];


             foreach($cos_detailed_monthly as $breakdown){
                 array_push($cos_detailed_monthly_labels, $breakdown->month_year);
                 array_push($cos_detailed_monthly_data_actual, $breakdown->actual_month);
                 array_push($cos_detailed_monthly_data_target, $breakdown->target_amount);
  
             }
 
 
             //dd($revenue_detailed_monthly_actual_target_data_target);
             // End COS - Detailed Data - Monthly / Actual

             //Start COS - Detailed Data - Cumulative Actual / Target
            $pool_cos_detailed_cumulative = Pool::create();
            $pool_cos_detailed_cumulative[] = async(function(){
                $value_cos_detailed_cumulative = DB::connection('redshift')->select("SELECT entity_name, month_year, fiscal_month, act_amount, trgt_amount
                FROM reporting.pnl_cumulative_per_entity
                WHERE entity_name = '".$GLOBALS['entity_name']."'
                    AND fiscal_year = '".$GLOBALS['fiscalyear']."'
                    AND account_name = 'Cost of Sales'
                ORDER by fiscal_month
                ");
                return $value_cos_detailed_cumulative;
                DB::close($value_cos_detailed_cumulative);
            });

            $cos_detailed_cumulative_result = await($pool_cos_detailed_cumulative);
            $cos_detailed_cumulative = $cos_detailed_cumulative_result[0];

            $cos_detailed_cumulative_data_labels=[];
            $cos_detailed_cumulative_data_actual=[];
            $cos_detailed_cumulative_data_target=[];

            //dd($cos_detailed_cumulative);

            foreach($cos_detailed_cumulative as $breakdown){
                array_push($cos_detailed_cumulative_data_labels, $breakdown->month_year);
                array_push($cos_detailed_cumulative_data_actual, $breakdown->act_amount);
                array_push($cos_detailed_cumulative_data_target, $breakdown->trgt_amount);
            }


            //dd($revenue_detailed_cumulative_data_target);
            // End COS - Detailed Data - Cumulative Actual / Target

            //Start COS - Detailed Data - quarterly Actual / Target
            $pool_cos_detailed_quarterly = Pool::create();
            $pool_cos_detailed_quarterly[] = async(function(){
                $value_cos_detailed_quarterly = DB::connection('redshift')->select("SELECT entity_name, 
                pm.fiscal_quarter as fq, 
                fiscal_quarter_str as fiscal_quarter,
                SUM(dashboard_amount) AS actual_amount, 
                SUM(target_amount) AS target_amount  
                FROM reporting.pnl_monthly pm
                LEFT JOIN core.dim_calendar_month cm on pm.date = cm.date
                WHERE account_name = 'Cost of Sales'
                AND entity_name = '".$GLOBALS['entity_name']."'
                GROUP BY entity_name,fq,fiscal_quarter_str
                ORDER BY entity_name,fq
                
                ");
                return $value_cos_detailed_quarterly;
                DB::close($value_cos_detailed_quarterly);
            });
            
            $cos_detailed_quarterly_result = await($pool_cos_detailed_quarterly);
            $cos_detailed_quarterly = $cos_detailed_quarterly_result[0];

            $cos_detailed_quarterly_data_labels=[];
            $cos_detailed_quarterly_data_actual=[];
            $cos_detailed_quarterly_data_target=[];

            //dd($cos_detailed_quarterly);

            foreach($cos_detailed_quarterly as $breakdown){
                array_push($cos_detailed_quarterly_data_labels, $breakdown->fiscal_quarter);
                array_push($cos_detailed_quarterly_data_actual, $breakdown->actual_amount);
                array_push($cos_detailed_quarterly_data_target, $breakdown->target_amount);
            }


            //dd($cos_detailed_cumulative_data_target);
            // End COS - Detailed Data - quarterly Actual / Target

             //Start COS - Detailed Data -  Monthly Variance
             $pool_cos_detailed_monthly_variance = Pool::create();
             $pool_cos_detailed_monthly_variance[] = async(function(){
                 $value_cos_detailed_monthly_variance = DB::connection('redshift')->select("SELECT entity_name, month_year, fiscal_month, sum(target_amount)-sum(dashboard_amount) as monthly_variance   
                 FROM reporting.pnl_monthly
                 WHERE account_name = 'Cost of Sales'
                     AND entity_name = '".$GLOBALS['entity_name']."'
                 GROUP BY entity_name, fiscal_month, month_year
                 ORDER BY fiscal_month
                 ");
                 return $value_cos_detailed_monthly_variance;
                 DB::close($value_cos_detailed_monthly_variance);
             });
 
             $cos_detailed_monthly_variance_result = await($pool_cos_detailed_monthly_variance);
             $cos_detailed_monthly_variance = $cos_detailed_monthly_variance_result[0];
 
             $cos_detailed_monthly_variance_data_labels=[];
             $cos_detailed_monthly_variance_data_actual=[];
 
             //dd($cos_detailed_monthly_variance);
 
             foreach($cos_detailed_monthly_variance as $breakdown){
                 array_push($cos_detailed_monthly_variance_data_labels, $breakdown->month_year);
                 array_push($cos_detailed_monthly_variance_data_actual, $breakdown->monthly_variance);
             }
 
 
             //dd($cos_detailed_cumulative_data_target);
             // End COS - Detailed Data -  Monthly Variance

            //Start COS - Detailed Data -  Cumatively Variance
            $pool_cos_detailed_cumatively_variance = Pool::create();
            $pool_cos_detailed_cumatively_variance[] = async(function(){
                $value_cos_detailed_cumatively_variance = DB::connection('redshift')->select("SELECT entity_name, month_year, fiscal_month, cumulative_variance
            FROM reporting.pnl_cumulative_per_entity
            WHERE entity_name = '".$GLOBALS['entity_name']."'
                AND fiscal_year = '".$GLOBALS['fiscalyear']."'
                AND account_name = 'Cost of Sales'
            ORDER by fiscal_month      
                ");
                return $value_cos_detailed_cumatively_variance;
                DB::close($value_cos_detailed_cumatively_variance);
            });

            $cos_detailed_cumatively_variance_result = await($pool_cos_detailed_cumatively_variance);
            $cos_detailed_cumatively_variance = $cos_detailed_cumatively_variance_result[0];

            $cos_detailed_cumatively_variance_data_labels=[];
            $cos_detailed_cumatively_variance_data_actual=[];

            //dd($cos_detailed_cumatively_variance);

            foreach($cos_detailed_cumatively_variance as $breakdown){
                array_push($cos_detailed_cumatively_variance_data_labels, $breakdown->month_year);
                array_push($cos_detailed_cumatively_variance_data_actual, $breakdown->cumulative_variance);
            }


            //dd($cos_detailed_cumulative_data_target);
            // End COS - Detailed Data -  Cumatively Variance

            //Start COS - Detailed Data -  quarterly Variance
            $pool_cos_detailed_quarterly_variance = Pool::create();
            $pool_cos_detailed_quarterly_variance[] = async(function(){
                $value_cos_detailed_quarterly_variance = DB::connection('redshift')->select("SELECT 
                entity_name, 
                pm.fiscal_quarter as fq, 
                fiscal_quarter_str as fiscal_quarter, 
                sum(target_amount)-sum(dashboard_amount) as quarterly_variance   
                FROM reporting.pnl_monthly pm
                LEFT JOIN core.dim_calendar_month cm on pm.date = cm.date
                WHERE account_name = 'Cost of Sales'
                AND entity_name = '".$GLOBALS['entity_name']."'
                GROUP BY entity_name,fq,fiscal_quarter_str
                ORDER BY entity_name,fq
                ");
                return $value_cos_detailed_quarterly_variance;
                DB::close($value_cos_detailed_quarterly_variance);
            });

            $cos_detailed_quarterly_variance_result = await($pool_cos_detailed_quarterly_variance);
            $cos_detailed_quarterly_variance = $cos_detailed_quarterly_variance_result[0];

            $cos_detailed_quarterly_variance_data_labels=[];
            $cos_detailed_quarterly_variance_data_actual=[];

            //dd($cos_detailed_quarterly_variance);

            foreach($cos_detailed_quarterly_variance as $breakdown){
                array_push($cos_detailed_quarterly_variance_data_labels, $breakdown->fiscal_quarter);
                array_push($cos_detailed_quarterly_variance_data_actual, $breakdown->quarterly_variance);
            }


            //dd($cos_detailed_cumulative_data_target);
            // End COS - Detailed Data -  quarterly Variance


            // Start GROSS PROFIT / LOSS
            $pool_gpl = Pool::create();
            $pool_gpl[] = async(function(){
                $value_gpl = DB::connection('redshift')->select("SELECT entity_name, sum(dashboard_amount) AS ytd_revenue,
                sum(target_amount) as ytd_target,
                sum(target_amount)-sum(dashboard_amount) as ytd_variance
         FROM reporting.pnl_monthly
         WHERE entity_name='".$GLOBALS['entity_name']."'
             AND fiscal_year=".$GLOBALS['fiscalyear']."
             AND account_name='Gross profit/(loss)'
         GROUP BY entity_name;");                
                return $value_gpl;
                DB::close($value_gpl);
            });

            $gpl_result = await($pool_gpl);
            if(!empty($gpl_result[0][0])) {
                $gpl = $gpl_result[0][0];
            } else {
                $nodata_json='[[{"entity_name": "No data","ytd_revenue": "0.0000","ytd_target": "0.0000","ytd_variance": "0.0000"}]]';
                $gpl=json_decode($nodata_json)[0][0];
            }
            //dd($gpl);
            // End GROSS PROFIT / LOSS

            // Start GROSS PROFIT / LOSS SMALL OVERVIEW  GRAPH
            $pool_gpl_overview_graph = Pool::create();
            $pool_gpl_overview_graph[] = async(function(){
                $value_gpl_overview_graph = DB::connection('redshift')->select("SELECT 
                entity_name,
                date,
                dashboard_amount as dashboard_amount,
                target_amount as target_amount
                FROM reporting.pnl_monthly
                WHERE fiscal_year=".$GLOBALS['fiscalyear']."
                AND account_name = 'Gross profit/(loss)'
                AND entity_name = '".$GLOBALS['entity_name']."'
                order by entity_name, fiscal_month
                ");                
                return $value_gpl_overview_graph;
                DB::close($value_gpl_overview_graph);
            });

            $gpl_overview_graph_result = await($pool_gpl_overview_graph);
            $gpl_overview_graph = $gpl_overview_graph_result[0];
            //dd($revenue_overview_graph);
            // End GROSS PROFIT / LOSS SMALL OVERVIEW  GRAPH

            // Start GROSS PROFIT / LOSS Breakdown
            $pool_gpl_breakdown = Pool::create();
            $pool_gpl_breakdown[] = async(function(){
                $value_gpl_breakdown = DB::connection('redshift')->select("SELECT account_name, sum(dashboard_amount) AS revenue
                FROM reporting.pnl_monthly
                WHERE entity_name='".$GLOBALS['entity_name']."'
                    AND fiscal_year=".$GLOBALS['fiscalyear']."
                    AND parent_account_name='P&L'
                    AND account_name = 'Gross profit/(loss)'
                GROUP BY account_name
                Having revenue > 0;                
                ");                
                return $value_gpl_breakdown;
                DB::close($value_gpl_breakdown);
            });

            $gpl_breakdown_result = await($pool_gpl_breakdown);
            $gpl_breakdown = $gpl_breakdown_result[0];

            $gpl_breakdown_labels=[];
            $gpl_breakdown_data=[];
            $gpl_breakdown_total = 0;

            foreach($gpl_breakdown as $breakdown){
                $gpl_breakdown_total += $breakdown->revenue; 
            }

            foreach($gpl_breakdown as $breakdown){
                array_push($gpl_breakdown_labels, $breakdown->account_name);

                $percentage_breakdown = ($breakdown->revenue / $gpl_breakdown_total) * 100; 
                $percentage_breakdown = round($percentage_breakdown, 2);
                array_push($gpl_breakdown_data, $percentage_breakdown);
            }

            // End GROSS PROFIT / LOSS Breakdown Breakdown

            //Start GROSS PROFIT / LOSS - Detailed Data - Monthly / Actual
            $pool_gpl_detailed_monthly = Pool::create();
            $pool_gpl_detailed_monthly[] = async(function(){
                $value_gpl_detailed_monthly = DB::connection('redshift')->select("SELECT entity_name,fiscal_month,month_year, SUM(dashboard_amount) AS actual_month, SUM(target_amount) AS target_amount  
                FROM reporting.pnl_monthly
                WHERE account_name = 'Gross profit/(loss)'
                    AND entity_name = '".$GLOBALS['entity_name']."'
                GROUP BY entity_name, fiscal_month, month_year
                ORDER BY entity_name, fiscal_month
                ");
                return $value_gpl_detailed_monthly;
                DB::close($value_gpl_detailed_monthly);
            });

            $gpl_detailed_monthly_result = await($pool_gpl_detailed_monthly);
            $gpl_detailed_monthly = $gpl_detailed_monthly_result[0];

            $gpl_detailed_monthly_labels=[];
            $gpl_detailed_monthly_data_actual=[];
            $gpl_detailed_monthly_data_target=[];


            foreach($gpl_detailed_monthly as $breakdown){
                array_push($gpl_detailed_monthly_labels, $breakdown->month_year);
                array_push($gpl_detailed_monthly_data_actual, $breakdown->actual_month);
                array_push($gpl_detailed_monthly_data_target, $breakdown->target_amount);
 
            }


            //dd($revenue_detailed_monthly_actual_target_data_target);
            // End GROSS PROFIT / LOSS - Detailed Data - Monthly / Actual

            //Start GROSS PROFIT / LOSS - Detailed Data - Cumulative Actual / Target
           $pool_gpl_detailed_cumulative = Pool::create();
            $pool_gpl_detailed_cumulative[] = async(function(){
                $value_gpl_detailed_cumulative = DB::connection('redshift')->select("SELECT entity_name, month_year, fiscal_month, act_amount, trgt_amount
                FROM reporting.pnl_cumulative_per_entity
                WHERE entity_name = '".$GLOBALS['entity_name']."'
                    AND fiscal_year = '".$GLOBALS['fiscalyear']."'
                    AND account_name = 'Gross profit/(loss)'
                ORDER by fiscal_month
                ");
               return $value_gpl_detailed_cumulative;
               DB::close($value_gpl_detailed_cumulative);
           });

           $gpl_detailed_cumulative_result = await($pool_gpl_detailed_cumulative);
           $gpl_detailed_cumulative = $gpl_detailed_cumulative_result[0];

           $gpl_detailed_cumulative_data_labels=[];
           $gpl_detailed_cumulative_data_actual=[];
           $gpl_detailed_cumulative_data_target=[];

           //dd($gpl_detailed_cumulative);

           foreach($gpl_detailed_cumulative as $breakdown){
               array_push($gpl_detailed_cumulative_data_labels, $breakdown->month_year);
               array_push($gpl_detailed_cumulative_data_actual, $breakdown->act_amount);
               array_push($gpl_detailed_cumulative_data_target, $breakdown->trgt_amount);
           }


           //dd($revenue_detailed_cumulative_data_target);
           // End GROSS PROFIT / LOSS - Detailed Data - Cumulative Actual / Target

           //Start GROSS PROFIT / LOSS - Detailed Data - quarterly Actual / Target
           $pool_gpl_detailed_quarterly = Pool::create();
           $pool_gpl_detailed_quarterly[] = async(function(){
               $value_gpl_detailed_quarterly = DB::connection('redshift')->select("SELECT entity_name, 
               pm.fiscal_quarter as fq, 
               fiscal_quarter_str as fiscal_quarter,
               SUM(dashboard_amount) AS actual_amount, 
               SUM(target_amount) AS target_amount  
               FROM reporting.pnl_monthly pm
               LEFT JOIN core.dim_calendar_month cm on pm.date = cm.date
               WHERE account_name = 'Gross profit/(loss)'
               AND entity_name = '".$GLOBALS['entity_name']."'
               GROUP BY entity_name,fq,fiscal_quarter_str
               ORDER BY entity_name,fq
               ");
               return $value_gpl_detailed_quarterly;
               DB::close($value_gpl_detailed_quarterly);
           });
           
           $gpl_detailed_quarterly_result = await($pool_gpl_detailed_quarterly);
           $gpl_detailed_quarterly = $gpl_detailed_quarterly_result[0];

           $gpl_detailed_quarterly_data_labels=[];
           $gpl_detailed_quarterly_data_actual=[];
           $gpl_detailed_quarterly_data_target=[];

           //dd($gpl_detailed_quarterly);

           foreach($gpl_detailed_quarterly as $breakdown){
               array_push($gpl_detailed_quarterly_data_labels, $breakdown->fiscal_quarter);
               array_push($gpl_detailed_quarterly_data_actual, $breakdown->actual_amount);
               array_push($gpl_detailed_quarterly_data_target, $breakdown->target_amount);
           }


           //dd($gpl_detailed_cumulative_data_target);
           // End GROSS PROFIT / LOSS - Detailed Data - quarterly Actual / Target

            //Start GROSS PROFIT / LOSS - Detailed Data -  Monthly Variance
            $pool_gpl_detailed_monthly_variance = Pool::create();
            $pool_gpl_detailed_monthly_variance[] = async(function(){
                $value_gpl_detailed_monthly_variance = DB::connection('redshift')->select("SELECT entity_name, month_year, fiscal_month, sum(target_amount)-sum(dashboard_amount) as monthly_variance   
                FROM reporting.pnl_monthly
                WHERE account_name = 'Gross profit/(loss)'
                    AND entity_name = '".$GLOBALS['entity_name']."'
                GROUP BY entity_name, fiscal_month, month_year
                ORDER BY fiscal_month
                ");
                return $value_gpl_detailed_monthly_variance;
                DB::close($value_gpl_detailed_monthly_variance);
            });

            $gpl_detailed_monthly_variance_result = await($pool_gpl_detailed_monthly_variance);
            $gpl_detailed_monthly_variance = $gpl_detailed_monthly_variance_result[0];

            $gpl_detailed_monthly_variance_data_labels=[];
            $gpl_detailed_monthly_variance_data_actual=[];

            //dd($gpl_detailed_monthly_variance);

            foreach($gpl_detailed_monthly_variance as $breakdown){
                array_push($gpl_detailed_monthly_variance_data_labels, $breakdown->month_year);
                array_push($gpl_detailed_monthly_variance_data_actual, $breakdown->monthly_variance);
            }


            //dd($gpl_detailed_cumulative_data_target);
            // End GROSS PROFIT / LOSS - Detailed Data -  Monthly Variance

           //Start GROSS PROFIT / LOSS - Detailed Data -  Cumatively Variance
           $pool_gpl_detailed_cumatively_variance = Pool::create();
           $pool_gpl_detailed_cumatively_variance[] = async(function(){
               $value_gpl_detailed_cumatively_variance = DB::connection('redshift')->select("SELECT entity_name, month_year, fiscal_month, cumulative_variance
            FROM reporting.pnl_cumulative_per_entity
            WHERE entity_name = '".$GLOBALS['entity_name']."'
                AND fiscal_year = '".$GLOBALS['fiscalyear']."'
                AND account_name = 'Gross profit/(loss)'
            ORDER by fiscal_month
               ");
               return $value_gpl_detailed_cumatively_variance;
               DB::close($value_gpl_detailed_cumatively_variance);
           });

           $gpl_detailed_cumatively_variance_result = await($pool_gpl_detailed_cumatively_variance);
           $gpl_detailed_cumatively_variance = $gpl_detailed_cumatively_variance_result[0];

           $gpl_detailed_cumatively_variance_data_labels=[];
           $gpl_detailed_cumatively_variance_data_actual=[];

           //dd($gpl_detailed_cumatively_variance);

           foreach($gpl_detailed_cumatively_variance as $breakdown){
               array_push($gpl_detailed_cumatively_variance_data_labels, $breakdown->month_year);
               array_push($gpl_detailed_cumatively_variance_data_actual, $breakdown->cumulative_variance);
           }


           //dd($gpl_detailed_cumulative_data_target);
           // End GROSS PROFIT / LOSS - Detailed Data -  Cumatively Variance

           //Start GROSS PROFIT / LOSS - Detailed Data -  quarterly Variance
           $pool_gpl_detailed_quarterly_variance = Pool::create();
           $pool_gpl_detailed_quarterly_variance[] = async(function(){
               $value_gpl_detailed_quarterly_variance = DB::connection('redshift')->select("SELECT 
               entity_name, 
               pm.fiscal_quarter as fq, 
               fiscal_quarter_str as fiscal_quarter, 
               sum(target_amount)-sum(dashboard_amount) as quarterly_variance   
               FROM reporting.pnl_monthly pm
               LEFT JOIN core.dim_calendar_month cm on pm.date = cm.date
               WHERE account_name = 'Gross profit/(loss)'
               AND entity_name = '".$GLOBALS['entity_name']."'
               GROUP BY entity_name,fq,fiscal_quarter_str
               ORDER BY entity_name,fq
               ");
               return $value_gpl_detailed_quarterly_variance;
               DB::close($value_gpl_detailed_quarterly_variance);
           });

           $gpl_detailed_quarterly_variance_result = await($pool_gpl_detailed_quarterly_variance);
           $gpl_detailed_quarterly_variance = $gpl_detailed_quarterly_variance_result[0];

           $gpl_detailed_quarterly_variance_data_labels=[];
           $gpl_detailed_quarterly_variance_data_actual=[];

           //dd($gpl_detailed_quarterly_variance);

           foreach($gpl_detailed_quarterly_variance as $breakdown){
               array_push($gpl_detailed_quarterly_variance_data_labels, $breakdown->fiscal_quarter);
               array_push($gpl_detailed_quarterly_variance_data_actual, $breakdown->quarterly_variance);
           }


           //dd($gpl_detailed_cumulative_data_target);
           // End GROSS PROFIT / LOSS - Detailed Data -  quarterly Variance


            // Start OPEX
            $pool_opex = Pool::create();
            $pool_opex[] = async(function(){
                $value_opex = DB::connection('redshift')->select("SELECT entity_name, sum(dashboard_amount) AS ytd_revenue,
                sum(target_amount) as ytd_target,
                sum(target_amount)-sum(dashboard_amount) as ytd_variance
         FROM reporting.pnl_monthly
         WHERE entity_name='".$GLOBALS['entity_name']."'
                AND fiscal_year=".$GLOBALS['fiscalyear']."
             AND account_name='Operating expense'
         GROUP BY entity_name");                
                return $value_opex;
                DB::close($value_opex);
            });

            $opex_result = await($pool_opex);
            if(!empty($opex_result[0][0])) {
                $opex = $opex_result[0][0];
            } else {
                $nodata_json='[[{"entity_name": "No data","ytd_revenue": "0.0000","ytd_target": "0.0000","ytd_variance": "0.0000"}]]';
                $opex=json_decode($nodata_json)[0][0];
            }
            //dd($opex);
            // End OPEX

            // Start OPEX SMALL OVERVIEW  GRAPH
            $pool_opex_overview_graph = Pool::create();
            $pool_opex_overview_graph[] = async(function(){
                $value_opex_overview_graph = DB::connection('redshift')->select("SELECT 
                entity_name,
                date,
                dashboard_amount as dashboard_amount,
                target_amount as target_amount
                FROM reporting.pnl_monthly
                WHERE fiscal_year=".$GLOBALS['fiscalyear']."
                AND account_name = 'Operating expense'
                AND entity_name = '".$GLOBALS['entity_name']."'
                order by entity_name, fiscal_month
                ");                
                return $value_opex_overview_graph;
                DB::close($value_opex_overview_graph);
            });

            $opex_overview_graph_result = await($pool_opex_overview_graph);
            $opex_overview_graph = $opex_overview_graph_result[0];
            //dd($revenue_overview_graph);
            // End OPEX SMALL OVERVIEW  GRAPH

            // Start OPEX Breakdown
            $pool_opex_breakdown = Pool::create();
            $pool_opex_breakdown[] = async(function(){
                $value_opex_breakdown = DB::connection('redshift')->select("SELECT account_name, sum(dashboard_amount) AS revenue
                FROM reporting.pnl_monthly
                WHERE entity_name='".$GLOBALS['entity_name']."'
                AND fiscal_year=".$GLOBALS['fiscalyear']."
                    AND parent_account_name='Operating expense'
                GROUP BY account_name
                Having revenue > 0;     
                ");                
                return $value_opex_breakdown;
                DB::close($value_opex_breakdown);
            });

            $opex_breakdown_result = await($pool_opex_breakdown);
            $opex_breakdown = $opex_breakdown_result[0];

            $opex_breakdown_labels=[];
            $opex_breakdown_data=[];
            $opex_breakdown_total = 0;

            foreach($opex_breakdown as $breakdown){
                $opex_breakdown_total += $breakdown->revenue; 
            }

            foreach($opex_breakdown as $breakdown){
                array_push($opex_breakdown_labels, $breakdown->account_name);

                $percentage_breakdown = ($breakdown->revenue / $opex_breakdown_total) * 100; 
                $percentage_breakdown = round($percentage_breakdown, 2);
                array_push($opex_breakdown_data, $percentage_breakdown);
            }

            // End OPEX Breakdown Breakdown

            //Start OPEX - Detailed Data - Monthly / Actual
            $pool_opex_detailed_monthly = Pool::create();
            $pool_opex_detailed_monthly[] = async(function(){
                $value_opex_detailed_monthly = DB::connection('redshift')->select("SELECT entity_name,fiscal_month,month_year, SUM(dashboard_amount) AS actual_month, SUM(target_amount) AS target_amount  
                FROM reporting.pnl_monthly
                WHERE account_name = 'Operating expense'
                    AND entity_name = '".$GLOBALS['entity_name']."'
                GROUP BY entity_name, fiscal_month, month_year
                ORDER BY entity_name, fiscal_month
                ");
                return $value_opex_detailed_monthly;
                DB::close($value_opex_detailed_monthly);
            });

            $opex_detailed_monthly_result = await($pool_opex_detailed_monthly);
            $opex_detailed_monthly = $opex_detailed_monthly_result[0];

            $opex_detailed_monthly_labels=[];
            $opex_detailed_monthly_data_actual=[];
            $opex_detailed_monthly_data_target=[];


            foreach($opex_detailed_monthly as $breakdown){
                array_push($opex_detailed_monthly_labels, $breakdown->month_year);
                array_push($opex_detailed_monthly_data_actual, $breakdown->actual_month);
                array_push($opex_detailed_monthly_data_target, $breakdown->target_amount);
 
            }


            //dd($revenue_detailed_monthly_actual_target_data_target);
            // End OPEX - Detailed Data - Monthly / Actual

            //Start OPEX - Detailed Data - Cumulative Actual / Target
           $pool_opex_detailed_cumulative = Pool::create();
            $pool_opex_detailed_cumulative[] = async(function(){
                $value_opex_detailed_cumulative = DB::connection('redshift')->select("SELECT entity_name, month_year, fiscal_month, act_amount, trgt_amount
                FROM reporting.pnl_cumulative_per_entity
                WHERE entity_name = '".$GLOBALS['entity_name']."'
                    AND fiscal_year = '".$GLOBALS['fiscalyear']."'
                    AND account_name = 'Operating expense'
                ORDER by fiscal_month
                ");
               return $value_opex_detailed_cumulative;
               DB::close($value_opex_detailed_cumulative);
           });

           $opex_detailed_cumulative_result = await($pool_opex_detailed_cumulative);
           $opex_detailed_cumulative = $opex_detailed_cumulative_result[0];

           $opex_detailed_cumulative_data_labels=[];
           $opex_detailed_cumulative_data_actual=[];
           $opex_detailed_cumulative_data_target=[];

           //dd($opex_detailed_cumulative);

           foreach($opex_detailed_cumulative as $breakdown){
               array_push($opex_detailed_cumulative_data_labels, $breakdown->month_year);
               array_push($opex_detailed_cumulative_data_actual, $breakdown->act_amount);
               array_push($opex_detailed_cumulative_data_target, $breakdown->trgt_amount);
           }


           //dd($revenue_detailed_cumulative_data_target);
           // End OPEX - Detailed Data - Cumulative Actual / Target

           //Start OPEX - Detailed Data - quarterly Actual / Target
           $pool_opex_detailed_quarterly = Pool::create();
           $pool_opex_detailed_quarterly[] = async(function(){
               $value_opex_detailed_quarterly = DB::connection('redshift')->select("SELECT entity_name, 
               pm.fiscal_quarter as fq, 
               fiscal_quarter_str as fiscal_quarter,
               SUM(dashboard_amount) AS actual_amount, 
               SUM(target_amount) AS target_amount  
               FROM reporting.pnl_monthly pm
               LEFT JOIN core.dim_calendar_month cm on pm.date = cm.date
               WHERE account_name = 'Operating expense'
               AND entity_name = '".$GLOBALS['entity_name']."'
               GROUP BY entity_name,fq,fiscal_quarter_str
               ORDER BY entity_name,fq
               ");
               return $value_opex_detailed_quarterly;
               DB::close($value_opex_detailed_quarterly);
           });
           
           $opex_detailed_quarterly_result = await($pool_opex_detailed_quarterly);
           $opex_detailed_quarterly = $opex_detailed_quarterly_result[0];

           $opex_detailed_quarterly_data_labels=[];
           $opex_detailed_quarterly_data_actual=[];
           $opex_detailed_quarterly_data_target=[];

           //dd($opex_detailed_quarterly);

           foreach($opex_detailed_quarterly as $breakdown){
               array_push($opex_detailed_quarterly_data_labels, $breakdown->fiscal_quarter);
               array_push($opex_detailed_quarterly_data_actual, $breakdown->actual_amount);
               array_push($opex_detailed_quarterly_data_target, $breakdown->target_amount);
           }


           //dd($opex_detailed_cumulative_data_target);
           // End OPEX - Detailed Data - quarterly Actual / Target

            //Start OPEX - Detailed Data -  Monthly Variance
            $pool_opex_detailed_monthly_variance = Pool::create();
            $pool_opex_detailed_monthly_variance[] = async(function(){
                $value_opex_detailed_monthly_variance = DB::connection('redshift')->select("SELECT entity_name, month_year, fiscal_month, sum(target_amount)-sum(dashboard_amount) as monthly_variance   
                FROM reporting.pnl_monthly
                WHERE account_name = 'Operating expense'
                    AND entity_name = '".$GLOBALS['entity_name']."'
                GROUP BY entity_name, fiscal_month, month_year
                ORDER BY fiscal_month
                ");
                return $value_opex_detailed_monthly_variance;
                DB::close($value_opex_detailed_monthly_variance);
            });

            $opex_detailed_monthly_variance_result = await($pool_opex_detailed_monthly_variance);
            $opex_detailed_monthly_variance = $opex_detailed_monthly_variance_result[0];

            $opex_detailed_monthly_variance_data_labels=[];
            $opex_detailed_monthly_variance_data_actual=[];

            //dd($opex_detailed_monthly_variance);

            foreach($opex_detailed_monthly_variance as $breakdown){
                array_push($opex_detailed_monthly_variance_data_labels, $breakdown->month_year);
                array_push($opex_detailed_monthly_variance_data_actual, $breakdown->monthly_variance);
            }


            //dd($opex_detailed_cumulative_data_target);
            // End OPEX - Detailed Data -  Monthly Variance

           //Start OPEX - Detailed Data -  Cumatively Variance
           $pool_opex_detailed_cumatively_variance = Pool::create();
           $pool_opex_detailed_cumatively_variance[] = async(function(){
               $value_opex_detailed_cumatively_variance = DB::connection('redshift')->select("SELECT entity_name, month_year, fiscal_month, cumulative_variance
            FROM reporting.pnl_cumulative_per_entity
            WHERE entity_name = '".$GLOBALS['entity_name']."'
                AND fiscal_year = '".$GLOBALS['fiscalyear']."'
                AND account_name = 'Operating expense'
            ORDER by fiscal_month
               ");
               return $value_opex_detailed_cumatively_variance;
               DB::close($value_opex_detailed_cumatively_variance);
           });

           $opex_detailed_cumatively_variance_result = await($pool_opex_detailed_cumatively_variance);
           $opex_detailed_cumatively_variance = $opex_detailed_cumatively_variance_result[0];

           $opex_detailed_cumatively_variance_data_labels=[];
           $opex_detailed_cumatively_variance_data_actual=[];

           //dd($opex_detailed_cumatively_variance);

           foreach($opex_detailed_cumatively_variance as $breakdown){
               array_push($opex_detailed_cumatively_variance_data_labels, $breakdown->month_year);
               array_push($opex_detailed_cumatively_variance_data_actual, $breakdown->cumulative_variance);
           }


           //dd($opex_detailed_cumulative_data_target);
           // End OPEX - Detailed Data -  Cumatively Variance

           //Start OPEX - Detailed Data -  quarterly Variance
           $pool_opex_detailed_quarterly_variance = Pool::create();
           $pool_opex_detailed_quarterly_variance[] = async(function(){
               $value_opex_detailed_quarterly_variance = DB::connection('redshift')->select("SELECT 
               entity_name, 
               pm.fiscal_quarter as fq, 
               fiscal_quarter_str as fiscal_quarter, 
               sum(target_amount)-sum(dashboard_amount) as quarterly_variance   
               FROM reporting.pnl_monthly pm
               LEFT JOIN core.dim_calendar_month cm on pm.date = cm.date
               WHERE account_name = 'Operating expense'
               AND entity_name = '".$GLOBALS['entity_name']."'
               GROUP BY entity_name,fq,fiscal_quarter_str
               ORDER BY entity_name,fq
               ");
               return $value_opex_detailed_quarterly_variance;
               DB::close($value_opex_detailed_quarterly_variance);
           });

           $opex_detailed_quarterly_variance_result = await($pool_opex_detailed_quarterly_variance);
           $opex_detailed_quarterly_variance = $opex_detailed_quarterly_variance_result[0];

           $opex_detailed_quarterly_variance_data_labels=[];
           $opex_detailed_quarterly_variance_data_actual=[];

           //dd($opex_detailed_quarterly_variance);

           foreach($opex_detailed_quarterly_variance as $breakdown){
               array_push($opex_detailed_quarterly_variance_data_labels, $breakdown->fiscal_quarter);
               array_push($opex_detailed_quarterly_variance_data_actual, $breakdown->quarterly_variance);
           }


           //dd($opex_detailed_cumulative_data_target);
           // End OPEX - Detailed Data -  quarterly Variance

            // Start EBITDA
            $pool_ebitda = Pool::create();
            $pool_ebitda[] = async(function(){
                $value_ebitda = DB::connection('redshift')->select("SELECT entity_name, sum(dashboard_amount) AS ytd_revenue,
                sum(target_amount) as ytd_target,
                sum(target_amount)-sum(dashboard_amount) as ytd_variance
            FROM reporting.pnl_monthly
            WHERE fiscal_year=".$GLOBALS['fiscalyear']."
                AND account_name='EBITDA'
                AND entity_name='".$GLOBALS['entity_name']."'
            GROUP BY entity_name
            ");                
                return $value_ebitda;
                DB::close($value_ebitda);
            });

            $ebitda_result = await($pool_ebitda);
            if(!empty($ebitda_result[0][0])) {
                $ebitda = $ebitda_result[0][0];
            } else {
                $nodata_json='[[{"entity_name": "No data","ytd_revenue": "0.0000","ytd_target": "0.0000","ytd_variance": "0.0000"}]]';
                $ebitda=json_decode($nodata_json)[0][0];
            }
            //dd($ebitda);
            // End EBITDA

            // Start PAT
            $pool_pat = Pool::create();
            $pool_pat[] = async(function(){
                $value_pat = DB::connection('redshift')->select("SELECT entity_name, sum(dashboard_amount) AS ytd_revenue,
                sum(target_amount) as ytd_target,
                sum(target_amount)-sum(dashboard_amount) as ytd_variance
         FROM reporting.pnl_monthly
         WHERE entity_name='".$GLOBALS['entity_name']."'
                AND fiscal_year=".$GLOBALS['fiscalyear']."
             AND account_name='PAT'
         GROUP BY entity_name");                
                return $value_pat;
                DB::close($value_pat);
            });

            $pat_result = await($pool_pat);
            if(!empty($pat_result[0][0])) {
                $pat = $pat_result[0][0];
            } else {
                $nodata_json='[[{"entity_name": "No data","ytd_revenue": "0.0000","ytd_target": "0.0000","ytd_variance": "0.0000"}]]';
                $pat=json_decode($nodata_json)[0][0];
            }
            //dd($pat);
            // End PAT

            // Start PAT SMALL OVERVIEW  GRAPH
            $pool_pat_overview_graph = Pool::create();
            $pool_pat_overview_graph[] = async(function(){
                $value_pat_overview_graph = DB::connection('redshift')->select("SELECT 
                entity_name,
                date,
                dashboard_amount as dashboard_amount,
                target_amount as target_amount
                FROM reporting.pnl_monthly
                WHERE fiscal_year=".$GLOBALS['fiscalyear']."
                AND account_name = 'PAT'
                AND entity_name = '".$GLOBALS['entity_name']."'
                order by entity_name, fiscal_month
                ");                
                return $value_pat_overview_graph;
                DB::close($value_pat_overview_graph);
            });

            $pat_overview_graph_result = await($pool_pat_overview_graph);
            $pat_overview_graph = $pat_overview_graph_result[0];
            //dd($revenue_overview_graph);
            // End PAT SMALL OVERVIEW  GRAPH

            // Start PAT Breakdown
            $pool_pat_breakdown = Pool::create();
            $pool_pat_breakdown[] = async(function(){
                $value_pat_breakdown = DB::connection('redshift')->select("SELECT account_name, sum(dashboard_amount) AS revenue
                FROM reporting.pnl_monthly
                WHERE entity_name='".$GLOBALS['entity_name']."'
                AND fiscal_year=".$GLOBALS['fiscalyear']."
                    AND parent_account_name='PAT'
                GROUP BY account_name
                Having revenue > 0;                     
                ");                
                return $value_pat_breakdown;
                DB::close($value_pat_breakdown);
            });

            $pat_breakdown_result = await($pool_pat_breakdown);
            $pat_breakdown = $pat_breakdown_result[0];

            $pat_breakdown_labels=[];
            $pat_breakdown_data=[];
            $pat_breakdown_total = 0;

            foreach($pat_breakdown as $breakdown){
                $pat_breakdown_total += $breakdown->revenue; 
            }

            foreach($pat_breakdown as $breakdown){
                array_push($pat_breakdown_labels, $breakdown->account_name);

                $percentage_breakdown = ($breakdown->revenue / $pat_breakdown_total) * 100; 
                $percentage_breakdown = round($percentage_breakdown, 2);
                array_push($pat_breakdown_data, $percentage_breakdown);
            }

            // End PAT Breakdown

            //Start PAT - Detailed Data - Monthly / Actual
            $pool_pat_detailed_monthly = Pool::create();
            $pool_pat_detailed_monthly[] = async(function(){
                $value_pat_detailed_monthly = DB::connection('redshift')->select("SELECT entity_name,fiscal_month,month_year, SUM(dashboard_amount) AS actual_month, SUM(target_amount) AS target_amount  
                FROM reporting.pnl_monthly
                WHERE account_name = 'PAT'
                    AND entity_name = '".$GLOBALS['entity_name']."'
                GROUP BY entity_name, fiscal_month, month_year
                ORDER BY entity_name, fiscal_month
                ");
                return $value_pat_detailed_monthly;
                DB::close($value_pat_detailed_monthly);
            });

            $pat_detailed_monthly_result = await($pool_pat_detailed_monthly);
            $pat_detailed_monthly = $pat_detailed_monthly_result[0];

            $pat_detailed_monthly_labels=[];
            $pat_detailed_monthly_data_actual=[];
            $pat_detailed_monthly_data_target=[];


            foreach($pat_detailed_monthly as $breakdown){
                array_push($pat_detailed_monthly_labels, $breakdown->month_year);
                array_push($pat_detailed_monthly_data_actual, $breakdown->actual_month);
                array_push($pat_detailed_monthly_data_target, $breakdown->target_amount);
 
            }


            //dd($revenue_detailed_monthly_actual_target_data_target);
            // End PAT - Detailed Data - Monthly / Actual

            //Start PAT - Detailed Data - Cumulative Actual / Target
           $pool_pat_detailed_cumulative = Pool::create();
            $pool_pat_detailed_cumulative[] = async(function(){
                $value_pat_detailed_cumulative = DB::connection('redshift')->select("SELECT entity_name, month_year, fiscal_month, act_amount, trgt_amount
                FROM reporting.pnl_cumulative_per_entity
                WHERE entity_name = '".$GLOBALS['entity_name']."'
                    AND fiscal_year = '".$GLOBALS['fiscalyear']."'
                    AND account_name = 'PAT'
                ORDER by fiscal_month
                ");
               return $value_pat_detailed_cumulative;
               DB::close($value_pat_detailed_cumulative);
           });

           $pat_detailed_cumulative_result = await($pool_pat_detailed_cumulative);
           $pat_detailed_cumulative = $pat_detailed_cumulative_result[0];

           $pat_detailed_cumulative_data_labels=[];
           $pat_detailed_cumulative_data_actual=[];
           $pat_detailed_cumulative_data_target=[];

           //dd($pat_detailed_cumulative);

           foreach($pat_detailed_cumulative as $breakdown){
               array_push($pat_detailed_cumulative_data_labels, $breakdown->month_year);
               array_push($pat_detailed_cumulative_data_actual, $breakdown->act_amount);
               array_push($pat_detailed_cumulative_data_target, $breakdown->trgt_amount);
           }


           //dd($revenue_detailed_cumulative_data_target);
           // End PAT - Detailed Data - Cumulative Actual / Target

           //Start PAT - Detailed Data - quarterly Actual / Target
           $pool_pat_detailed_quarterly = Pool::create();
           $pool_pat_detailed_quarterly[] = async(function(){
               $value_pat_detailed_quarterly = DB::connection('redshift')->select("SELECT entity_name, 
               pm.fiscal_quarter as fq, 
               fiscal_quarter_str as fiscal_quarter,
               SUM(dashboard_amount) AS actual_amount, 
               SUM(target_amount) AS target_amount  
               FROM reporting.pnl_monthly pm
               LEFT JOIN core.dim_calendar_month cm on pm.date = cm.date
               WHERE account_name = 'PAT'
               AND entity_name = '".$GLOBALS['entity_name']."'
               GROUP BY entity_name,fq,fiscal_quarter_str
               ORDER BY entity_name,fq
               ");
               return $value_pat_detailed_quarterly;
               DB::close($value_pat_detailed_quarterly);
           });
           
           $pat_detailed_quarterly_result = await($pool_pat_detailed_quarterly);
           $pat_detailed_quarterly = $pat_detailed_quarterly_result[0];

           $pat_detailed_quarterly_data_labels=[];
           $pat_detailed_quarterly_data_actual=[];
           $pat_detailed_quarterly_data_target=[];

           //dd($pat_detailed_quarterly);

           foreach($pat_detailed_quarterly as $breakdown){
               array_push($pat_detailed_quarterly_data_labels, $breakdown->fiscal_quarter);
               array_push($pat_detailed_quarterly_data_actual, $breakdown->actual_amount);
               array_push($pat_detailed_quarterly_data_target, $breakdown->target_amount);
           }


           //dd($pat_detailed_cumulative_data_target);
           // End PAT - Detailed Data - quarterly Actual / Target

            //Start PAT - Detailed Data -  Monthly Variance
            $pool_pat_detailed_monthly_variance = Pool::create();
            $pool_pat_detailed_monthly_variance[] = async(function(){
                $value_pat_detailed_monthly_variance = DB::connection('redshift')->select("SELECT entity_name, month_year, fiscal_month, sum(target_amount)-sum(dashboard_amount) as monthly_variance   
                FROM reporting.pnl_monthly
                WHERE account_name = 'PAT'
                    AND entity_name = '".$GLOBALS['entity_name']."'
                GROUP BY entity_name, fiscal_month, month_year
                ORDER BY fiscal_month
                ");
                return $value_pat_detailed_monthly_variance;
                DB::close($value_pat_detailed_monthly_variance);
            });

            $pat_detailed_monthly_variance_result = await($pool_pat_detailed_monthly_variance);
            $pat_detailed_monthly_variance = $pat_detailed_monthly_variance_result[0];

            $pat_detailed_monthly_variance_data_labels=[];
            $pat_detailed_monthly_variance_data_actual=[];

            //dd($pat_detailed_monthly_variance);

            foreach($pat_detailed_monthly_variance as $breakdown){
                array_push($pat_detailed_monthly_variance_data_labels, $breakdown->month_year);
                array_push($pat_detailed_monthly_variance_data_actual, $breakdown->monthly_variance);
            }


            //dd($pat_detailed_cumulative_data_target);
            // End PAT - Detailed Data -  Monthly Variance

           //Start PAT - Detailed Data -  Cumatively Variance
           $pool_pat_detailed_cumatively_variance = Pool::create();
           $pool_pat_detailed_cumatively_variance[] = async(function(){
               $value_pat_detailed_cumatively_variance = DB::connection('redshift')->select("SELECT entity_name, month_year, fiscal_month, cumulative_variance
            FROM reporting.pnl_cumulative_per_entity
            WHERE entity_name = '".$GLOBALS['entity_name']."'
                AND fiscal_year = '".$GLOBALS['fiscalyear']."'
                AND account_name = 'PAT'
            ORDER by fiscal_month
               ");
               return $value_pat_detailed_cumatively_variance;
               DB::close($value_pat_detailed_cumatively_variance);
           });

           $pat_detailed_cumatively_variance_result = await($pool_pat_detailed_cumatively_variance);
           $pat_detailed_cumatively_variance = $pat_detailed_cumatively_variance_result[0];

           $pat_detailed_cumatively_variance_data_labels=[];
           $pat_detailed_cumatively_variance_data_actual=[];

           //dd($pat_detailed_cumatively_variance);

           foreach($pat_detailed_cumatively_variance as $breakdown){
               array_push($pat_detailed_cumatively_variance_data_labels, $breakdown->month_year);
               array_push($pat_detailed_cumatively_variance_data_actual, $breakdown->cumulative_variance);
           }


           //dd($pat_detailed_cumulative_data_target);
           // End PAT - Detailed Data -  Cumatively Variance

           //Start PAT - Detailed Data -  quarterly Variance
           $pool_pat_detailed_quarterly_variance = Pool::create();
           $pool_pat_detailed_quarterly_variance[] = async(function(){
               $value_pat_detailed_quarterly_variance = DB::connection('redshift')->select("SELECT 
               entity_name, 
               pm.fiscal_quarter as fq, 
               fiscal_quarter_str as fiscal_quarter, 
               sum(target_amount)-sum(dashboard_amount) as quarterly_variance   
               FROM reporting.pnl_monthly pm
               LEFT JOIN core.dim_calendar_month cm on pm.date = cm.date
               WHERE account_name = 'PAT'
               AND entity_name = '".$GLOBALS['entity_name']."'
               GROUP BY entity_name,fq,fiscal_quarter_str
               ORDER BY entity_name,fq
               ");
               return $value_pat_detailed_quarterly_variance;
               DB::close($value_pat_detailed_quarterly_variance);
           });

           $pat_detailed_quarterly_variance_result = await($pool_pat_detailed_quarterly_variance);
           $pat_detailed_quarterly_variance = $pat_detailed_quarterly_variance_result[0];

           $pat_detailed_quarterly_variance_data_labels=[];
           $pat_detailed_quarterly_variance_data_actual=[];

           //dd($pat_detailed_quarterly_variance);

           foreach($pat_detailed_quarterly_variance as $breakdown){
               array_push($pat_detailed_quarterly_variance_data_labels, $breakdown->fiscal_quarter);
               array_push($pat_detailed_quarterly_variance_data_actual, $breakdown->quarterly_variance);
           }


           //dd($pat_detailed_cumulative_data_target);
           // End PAT - Detailed Data -  quarterly Variance


           // Start GET BREAKDOWN DETAILED GRAPHS DATA

           function GetBreakdownDetailedData($breakdown_array,$parent_account_name)
           {
            $GLOBALS['parent_account_name'] = "";
            $GLOBALS['parent_account_name'] = $parent_account_name;

           foreach($breakdown_array as $breakdown){
               //GET BREAKDOWN DATA FOR EACH 
            
               $GLOBALS['breakdown_account_name'] = ""; //Used to reset as variable will get reused for each breakdown
               $GLOBALS['breakdown_account_name'] = $breakdown->account_name;


               // Start MONTHLY ACTUAL / TARGET BREAKDOWN 
                $pool_breakdown_monthly_actual_target = Pool::create();
                $pool_breakdown_monthly_actual_target[] = async(function(){
                    $value_breakdown_monthly_actual_target = DB::connection('redshift')->select("SELECT entity_name,fiscal_month,month_year, SUM(dashboard_amount) AS actual_amt, SUM(target_amount) AS target_amt  
                    FROM reporting.pnl_monthly
                    WHERE fiscal_year=".$GLOBALS['fiscalyear']."
                        AND parent_account_name = '".$GLOBALS['parent_account_name']."'
                        AND account_name = '".$GLOBALS['breakdown_account_name']."'
                        AND entity_name = '".$GLOBALS['entity_name']."'
                    GROUP BY entity_name, account_name, fiscal_month, month_year
                    ORDER BY entity_name, fiscal_month;
                    ");                
                    return $value_breakdown_monthly_actual_target;
                    DB::close($value_breakdown_monthly_actual_target);
                });

                $breakdown_monthly_actual_target_result = await($pool_breakdown_monthly_actual_target);


                $breakdown_detailed_monthly_labels = array();
                $breakdown_detailed_monthly_actual = array();
                $breakdown_detailed_monthly_target = array();

                foreach($breakdown_monthly_actual_target_result[0] as $breakdown_result){
                    array_push($breakdown_detailed_monthly_labels,$breakdown_result->month_year);
                    array_push($breakdown_detailed_monthly_actual,$breakdown_result->actual_amt);
                    array_push($breakdown_detailed_monthly_target,$breakdown_result->target_amt);
                }

                $breakdown_detailed_monthly_label_collection = array('Label' => $breakdown_detailed_monthly_labels);
                $breakdown_detailed_monthly_actual_collection = array('Actual' => $breakdown_detailed_monthly_actual);
                $breakdown_detailed_monthly_target_collection = array('Target' => $breakdown_detailed_monthly_target);

                $breakdown_detailed_monthly_collection = $breakdown_detailed_monthly_label_collection + $breakdown_detailed_monthly_actual_collection + $breakdown_detailed_monthly_target_collection;

                $breakdown_monthly_actual_target_array = array('Monthly Actual / Target' => $breakdown_detailed_monthly_collection);
                
                
                // End MONTHLY ACTUAL / TARGET BREAKDOWN

                // Start CUMULATIVE ACTUAL / TARGET BREAKDOWN
                $pool_breakdown_cumulative_actual_target = Pool::create();
                $pool_breakdown_cumulative_actual_target[] = async(function(){
                    $value_breakdown_cumulative_actual_target = DB::connection('redshift')->select("SELECT entity_name, month_year, fiscal_month,
                    (SELECT sum(dashboard_amount) FROM reporting.pnl_monthly pnl WHERE pnl.fiscal_month::int<=rpt.fiscal_month::int AND parent_account_name = '".$GLOBALS['parent_account_name']."' AND entity_name=rpt.entity_name AND account_name=rpt.account_name) as act_amount,
                    (SELECT sum(target_amount) FROM reporting.pnl_monthly pnl WHERE pnl.fiscal_month::int<=rpt.fiscal_month::int AND parent_account_name = '".$GLOBALS['parent_account_name']."' AND entity_name=rpt.entity_name AND account_name=rpt.account_name) as trgt_amount
                FROM (SELECT DISTINCT entity_name, account_name, month_year, fiscal_month FROM reporting.pnl_monthly) AS rpt
                WHERE entity_name = '".$GLOBALS['entity_name']."'
                    AND account_name = '".$GLOBALS['breakdown_account_name']."'
                ORDER BY fiscal_month
                ");                
                    return $value_breakdown_cumulative_actual_target;
                    DB::close($value_breakdown_cumulative_actual_target);
                });

                $breakdown_cumulative_actual_target_result = await($pool_breakdown_cumulative_actual_target);

                $breakdown_detailed_cumulative_monthly_labels = array();
                $breakdown_detailed_cumulative_monthly_actual = array();
                $breakdown_detailed_cumulative_monthly_target = array();

                foreach($breakdown_cumulative_actual_target_result[0] as $breakdown_result){
                    array_push($breakdown_detailed_cumulative_monthly_labels,$breakdown_result->month_year);
                    array_push($breakdown_detailed_cumulative_monthly_actual,$breakdown_result->act_amount);
                    array_push($breakdown_detailed_cumulative_monthly_target,$breakdown_result->trgt_amount);
                }

                $breakdown_detailed_cumulative_monthly_label_collection = array('Label' => $breakdown_detailed_cumulative_monthly_labels);
                $breakdown_detailed_cumulative_monthly_actual_collection = array('Actual' => $breakdown_detailed_cumulative_monthly_actual);
                $breakdown_detailed_cumulative_monthly_target_collection = array('Target' => $breakdown_detailed_cumulative_monthly_target);

                $breakdown_detailed_cumulative_monthly_collection = $breakdown_detailed_cumulative_monthly_label_collection + $breakdown_detailed_cumulative_monthly_actual_collection + $breakdown_detailed_cumulative_monthly_target_collection;

                $breakdown_cumulative_actual_target_array = array('Cumulative Actual / Target' => $breakdown_detailed_cumulative_monthly_collection);
                
                
                // End CUMLATIVE ACTUAL / TARGET BREAKDOWN
                

                // Start quarterly ACTUAL / TARGET BREAKDOWN
                $pool_breakdown_quarterly_actual_target = Pool::create();
                $pool_breakdown_quarterly_actual_target[] = async(function(){
                    $value_breakdown_quarterly_actual_target = DB::connection('redshift')->select("SELECT 
                    entity_name, 
                    pm.fiscal_quarter as fq, 
                    fiscal_quarter_str as fiscal_quarter,
                    SUM(dashboard_amount) AS actual_amount, 
                    SUM(target_amount) AS target_amount  
                    FROM reporting.pnl_monthly pm
                    LEFT JOIN core.dim_calendar_month cm on pm.date = cm.date
                    WHERE parent_account_name = '".$GLOBALS['parent_account_name']."'
                    AND account_name = '".$GLOBALS['breakdown_account_name']."'
                    AND entity_name = '".$GLOBALS['entity_name']."'
                    GROUP BY entity_name,fq,fiscal_quarter_str
                    ORDER BY entity_name,fq
                    ");                
                    return $value_breakdown_quarterly_actual_target;
                    DB::close($value_breakdown_quarterly_actual_target);
                });

                $breakdown_quarterly_actual_target_result = await($pool_breakdown_quarterly_actual_target);

                $breakdown_detailed_quarterly_actual_target_labels = array();
                $breakdown_detailed_quarterly_actual_target_actual = array();
                $breakdown_detailed_quarterly_actual_target_target = array();

                foreach($breakdown_quarterly_actual_target_result[0] as $breakdown_result){
                    array_push($breakdown_detailed_quarterly_actual_target_labels,$breakdown_result->fiscal_quarter);
                    array_push($breakdown_detailed_quarterly_actual_target_actual,$breakdown_result->actual_amount);
                    array_push($breakdown_detailed_quarterly_actual_target_target,$breakdown_result->target_amount);
                }

                $breakdown_detailed_quarterly_actual_target_label_collection = array('Label' => $breakdown_detailed_quarterly_actual_target_labels);
                $breakdown_detailed_quarterly_actual_target_actual_collection = array('Actual' => $breakdown_detailed_quarterly_actual_target_actual);
                $breakdown_detailed_quarterly_actual_target_target_collection = array('Target' => $breakdown_detailed_quarterly_actual_target_target);

                $breakdown_detailed_quarterly_actual_target_collection = $breakdown_detailed_quarterly_actual_target_label_collection + $breakdown_detailed_quarterly_actual_target_actual_collection + $breakdown_detailed_quarterly_actual_target_target_collection;

                $breakdown_quarterly_actual_target_array = array('quarterly Actual / Target' => $breakdown_detailed_quarterly_actual_target_collection);
                
                
                // End quarterly ACTUAL / TARGET BREAKDOWN

                // Start MONTHLY VARIANCE BREAKDOWN
                $pool_breakdown_monthly_variance = Pool::create();
                $pool_breakdown_monthly_variance[] = async(function(){
                    $value_breakdown_monthly_variance = DB::connection('redshift')->select("SELECT entity_name, fiscal_month, sum(target_amount)-sum(dashboard_amount) as monthly_variance   
                    FROM reporting.pnl_monthly
                    WHERE fiscal_year=".$GLOBALS['fiscalyear']."
                        AND parent_account_name = '".$GLOBALS['parent_account_name']."'
                        AND account_name = '".$GLOBALS['breakdown_account_name']."'
                        AND entity_name = '".$GLOBALS['entity_name']."'
                    GROUP BY entity_name, fiscal_month
                    ORDER BY fiscal_month
                    ");                
                    return $value_breakdown_monthly_variance;
                    DB::close($value_breakdown_monthly_variance);
                });

                $breakdown_monthly_variance_result = await($pool_breakdown_monthly_variance);

                $breakdown_monthly_variance_labels = array();
                $breakdown_monthly_variance_actual = array();

                foreach($breakdown_monthly_variance_result[0] as $breakdown_result){
                    array_push($breakdown_monthly_variance_labels,$breakdown_result->fiscal_month);
                    array_push($breakdown_monthly_variance_actual,$breakdown_result->monthly_variance);
                }

                $breakdown_monthly_variance_label_collection = array('Label' => $breakdown_monthly_variance_labels);
                $breakdown_monthly_variance_actual_collection = array('Actual' => $breakdown_monthly_variance_actual);

                $breakdown_monthly_variance_collection = $breakdown_monthly_variance_label_collection + $breakdown_monthly_variance_actual_collection;

                $breakdown_monthly_variance_array = array('Monthly Variance' => $breakdown_monthly_variance_collection);
                
                // End MONTHLY VARIANCE BREAKDOWN

                // Start CUMATIVELY VARIANCE BREAKDOWN
                $pool_breakdown_cumatively_variance = Pool::create();
                $pool_breakdown_cumatively_variance[] = async(function(){
                    $value_breakdown_cumatively_variance = DB::connection('redshift')->select("SELECT entity_name, month_year, fiscal_month,
                    (SELECT sum(target_amount)-sum(dashboard_amount) FROM reporting.pnl_monthly pnl WHERE pnl.fiscal_month::int<=rpt.fiscal_month::int AND parent_account_name = '".$GLOBALS['parent_account_name']."' AND entity_name=rpt.entity_name AND account_name=rpt.account_name) as cumulative_variance
                FROM (SELECT DISTINCT entity_name, month_year, account_name, fiscal_month FROM reporting.pnl_monthly) AS rpt
                WHERE entity_name = '".$GLOBALS['entity_name']."'
                    AND account_name = 'Waste management income'
                ORDER BY fiscal_month
             ");                
                    return $value_breakdown_cumatively_variance;
                    DB::close($value_breakdown_cumatively_variance);
                });

                $breakdown_cumatively_variance_result = await($pool_breakdown_cumatively_variance);

                $breakdown_cumatively_variance_labels = array();
                $breakdown_cumatively_variance_actual = array();

                foreach($breakdown_cumatively_variance_result[0] as $breakdown_result){
                    array_push($breakdown_cumatively_variance_labels,$breakdown_result->month_year);
                    array_push($breakdown_cumatively_variance_actual,$breakdown_result->cumulative_variance);
                }

                $breakdown_cumatively_variance_label_collection = array('Label' => $breakdown_cumatively_variance_labels);
                $breakdown_cumatively_variance_actual_collection = array('Actual' => $breakdown_cumatively_variance_actual);

                $breakdown_cumatively_variance_collection = $breakdown_cumatively_variance_label_collection + $breakdown_cumatively_variance_actual_collection;

                $breakdown_cumatively_variance_array = array('Cumatively Variance' => $breakdown_cumatively_variance_collection);
                
                // End CUMATIVELY VARIANCE BREAKDOWN

                // Start quarterly VARIANCE BREAKDOWN
                $pool_breakdown_quarterly_variance = Pool::create();
                $pool_breakdown_quarterly_variance[] = async(function(){
                    $value_breakdown_quarterly_variance = DB::connection('redshift')->select("SELECT 
                    entity_name, 
                    pm.fiscal_quarter as fq, 
                    fiscal_quarter_str as fiscal_quarter, 
                    sum(target_amount)-sum(dashboard_amount) as quarterly_variance   
                    FROM reporting.pnl_monthly pm
                    LEFT JOIN core.dim_calendar_month cm on pm.date = cm.date
                    WHERE parent_account_name = '".$GLOBALS['parent_account_name']."'
                    AND account_name = '".$GLOBALS['breakdown_account_name']."'
                    AND entity_name = '".$GLOBALS['entity_name']."'
                    GROUP BY entity_name,fq,fiscal_quarter_str
                    ORDER BY entity_name,fq
                    ");                
                    return $value_breakdown_quarterly_variance;
                    DB::close($value_breakdown_quarterly_variance);
                });

                $breakdown_quarterly_variance_result = await($pool_breakdown_quarterly_variance);

                $breakdown_quarterly_variance_labels = array();
                $breakdown_quarterly_variance_actual = array();

                foreach($breakdown_quarterly_variance_result[0] as $breakdown_result){
                    array_push($breakdown_quarterly_variance_labels,$breakdown_result->fiscal_quarter);
                    array_push($breakdown_quarterly_variance_actual,$breakdown_result->quarterly_variance);
                }

                $breakdown_quarterly_variance_label_collection = array('Label' => $breakdown_quarterly_variance_labels);
                $breakdown_quarterly_variance_actual_collection = array('Actual' => $breakdown_quarterly_variance_actual);

                $breakdown_quarterly_variance_collection = $breakdown_quarterly_variance_label_collection + $breakdown_quarterly_variance_actual_collection;

                $breakdown_quarterly_variance_array = array('quarterly Variance' => $breakdown_quarterly_variance_collection);
                
                // End quarterly VARIANCE BREAKDOWN


                $breakdown_account_array = $breakdown_monthly_actual_target_array + $breakdown_cumulative_actual_target_array + $breakdown_quarterly_actual_target_array + $breakdown_monthly_variance_array + $breakdown_cumatively_variance_array + $breakdown_quarterly_variance_array;

                
                $breakdown_account_array_collection = array($GLOBALS['breakdown_account_name'] => $breakdown_account_array);

                $GLOBALS['detailed_breakdown_collection'] += $breakdown_account_array_collection;


           }
        };

        GetBreakdownDetailedData($revenue_breakdown,'Revenue');
        GetBreakdownDetailedData($cos_breakdown,'Cost of Sales');
        GetBreakdownDetailedData($gpl_breakdown,'Gross profit/(loss)'); //NO BREAKDOWNS TO SHOW
        GetBreakdownDetailedData($opex_breakdown,'Operating expense');
        GetBreakdownDetailedData($pat_breakdown,'PAT'); //NO BREAKDOWNS TO SHOW

        // End REVENUE BREAKDOWN DETAILED GRAPHS
            
        //dd($GLOBALS['detailed_breakdown_collection']);

            if($r_berjaya) {
                return view('property.investment_cempaka', [
                'update_date'=>$update_date,
                'berjaya_access'=>$berjaya_access, 
                'hospitality_access'=>$hospitality_access,
                'property_access'=>$property_access, 
                'retail_access'=>$retail_access, 
                'services_access'=>$services_access,
                'userName'=>session('email'),
                'userID'=>$userID,
                'property'=>true,
                'revenue'=>$revenue,
                'revenue_overview_graph' => $revenue_overview_graph,
                'revenue_breakdown_labels'=>$revenue_breakdown_labels,
                'revenue_breakdown_data'=>$revenue_breakdown_data, 
                'revenue_detailed_monthly_labels' => $revenue_detailed_monthly_labels, 'revenue_detailed_monthly_data_actual' => $revenue_detailed_monthly_data_actual, 'revenue_detailed_monthly_data_target' => $revenue_detailed_monthly_data_target, 'revenue_detailed_cumulative_data_labels' => $revenue_detailed_cumulative_data_labels, 'revenue_detailed_cumulative_data_actual' => $revenue_detailed_cumulative_data_actual, 'revenue_detailed_cumulative_data_target' => $revenue_detailed_cumulative_data_target, 'revenue_detailed_quarterly_data_labels' => $revenue_detailed_quarterly_data_labels, 'revenue_detailed_quarterly_data_actual' => $revenue_detailed_quarterly_data_actual, 'revenue_detailed_quarterly_data_target' => $revenue_detailed_quarterly_data_target, 'revenue_detailed_monthly_variance_data_labels' => $revenue_detailed_monthly_variance_data_labels, 'revenue_detailed_monthly_variance_data_actual' => $revenue_detailed_monthly_variance_data_actual, 'revenue_detailed_cumatively_variance_data_labels' => $revenue_detailed_cumatively_variance_data_labels, 'revenue_detailed_cumatively_variance_data_actual' => $revenue_detailed_cumatively_variance_data_actual, 'revenue_detailed_quarterly_variance_data_labels' => $revenue_detailed_quarterly_variance_data_labels, 'revenue_detailed_quarterly_variance_data_actual' => $revenue_detailed_quarterly_variance_data_actual,
                'cos'=>$cos,
                'cos_overview_graph' => $cos_overview_graph,
                'cos_breakdown_labels'=>$cos_breakdown_labels,
                'cos_breakdown_data'=>$cos_breakdown_data,
                'cos_detailed_monthly_labels' => $cos_detailed_monthly_labels,
                'cos_detailed_monthly_data_actual' => $cos_detailed_monthly_data_actual, 'cos_detailed_monthly_data_target' => $cos_detailed_monthly_data_target, 'cos_detailed_cumulative_data_labels' => $cos_detailed_cumulative_data_labels, 'cos_detailed_cumulative_data_actual' => $cos_detailed_cumulative_data_actual, 'cos_detailed_cumulative_data_target' => $cos_detailed_cumulative_data_target,'cos_detailed_quarterly_data_labels' => $cos_detailed_quarterly_data_labels, 'cos_detailed_quarterly_data_actual' => $cos_detailed_quarterly_data_actual, 'cos_detailed_quarterly_data_target' => $cos_detailed_quarterly_data_target, 'cos_detailed_monthly_variance_data_labels' => $cos_detailed_monthly_variance_data_labels, 'cos_detailed_monthly_variance_data_actual' => $cos_detailed_monthly_variance_data_actual, 'cos_detailed_cumatively_variance_data_labels' => $cos_detailed_cumatively_variance_data_labels, 'cos_detailed_cumatively_variance_data_actual' => $cos_detailed_cumatively_variance_data_actual, 'cos_detailed_quarterly_variance_data_labels' => $cos_detailed_quarterly_variance_data_labels, 'cos_detailed_quarterly_variance_data_actual' => $cos_detailed_quarterly_variance_data_actual,
                'gpl'=>$gpl,
                'gpl_overview_graph' => $gpl_overview_graph,
                'gpl_breakdown_labels'=>$gpl_breakdown_labels,
                'gpl_breakdown_data'=>$gpl_breakdown_data,
                'gpl_detailed_monthly_labels' => $gpl_detailed_monthly_labels,
                'gpl_detailed_monthly_data_actual' => $gpl_detailed_monthly_data_actual, 'gpl_detailed_monthly_data_target' => $gpl_detailed_monthly_data_target, 'gpl_detailed_cumulative_data_labels' => $gpl_detailed_cumulative_data_labels, 'gpl_detailed_cumulative_data_actual' => $gpl_detailed_cumulative_data_actual, 'gpl_detailed_cumulative_data_target' => $gpl_detailed_cumulative_data_target,'gpl_detailed_quarterly_data_labels' => $gpl_detailed_quarterly_data_labels, 'gpl_detailed_quarterly_data_actual' => $gpl_detailed_quarterly_data_actual, 'gpl_detailed_quarterly_data_target' => $gpl_detailed_quarterly_data_target, 'gpl_detailed_monthly_variance_data_labels' => $gpl_detailed_monthly_variance_data_labels, 'gpl_detailed_monthly_variance_data_actual' => $gpl_detailed_monthly_variance_data_actual, 'gpl_detailed_cumatively_variance_data_labels' => $gpl_detailed_cumatively_variance_data_labels, 'gpl_detailed_cumatively_variance_data_actual' => $gpl_detailed_cumatively_variance_data_actual, 'gpl_detailed_quarterly_variance_data_labels' => $gpl_detailed_quarterly_variance_data_labels, 'gpl_detailed_quarterly_variance_data_actual' => $gpl_detailed_quarterly_variance_data_actual,
                'opex'=>$opex,
                'opex_overview_graph' => $opex_overview_graph,
                'opex_breakdown_labels'=>$opex_breakdown_labels,
                'opex_breakdown_data'=>$opex_breakdown_data,
                'opex_detailed_monthly_labels' => $opex_detailed_monthly_labels,
                'opex_detailed_monthly_data_actual' => $opex_detailed_monthly_data_actual, 'opex_detailed_monthly_data_target' => $opex_detailed_monthly_data_target, 'opex_detailed_cumulative_data_labels' => $opex_detailed_cumulative_data_labels, 'opex_detailed_cumulative_data_actual' => $opex_detailed_cumulative_data_actual, 'opex_detailed_cumulative_data_target' => $opex_detailed_cumulative_data_target,'opex_detailed_quarterly_data_labels' => $opex_detailed_quarterly_data_labels, 'opex_detailed_quarterly_data_actual' => $opex_detailed_quarterly_data_actual, 'opex_detailed_quarterly_data_target' => $opex_detailed_quarterly_data_target, 'opex_detailed_monthly_variance_data_labels' => $opex_detailed_monthly_variance_data_labels, 'opex_detailed_monthly_variance_data_actual' => $opex_detailed_monthly_variance_data_actual, 'opex_detailed_cumatively_variance_data_labels' => $opex_detailed_cumatively_variance_data_labels, 'opex_detailed_cumatively_variance_data_actual' => $opex_detailed_cumatively_variance_data_actual, 'opex_detailed_quarterly_variance_data_labels' => $opex_detailed_quarterly_variance_data_labels, 'opex_detailed_quarterly_variance_data_actual' => $opex_detailed_quarterly_variance_data_actual,
                'pat'=>$pat,
                'pat_overview_graph' => $pat_overview_graph,
                'pat_breakdown_labels'=>$pat_breakdown_labels,
                'pat_breakdown_data'=>$pat_breakdown_data,
                'pat_detailed_monthly_labels' => $pat_detailed_monthly_labels,
                'pat_detailed_monthly_data_actual' => $pat_detailed_monthly_data_actual, 'pat_detailed_monthly_data_target' => $pat_detailed_monthly_data_target, 'pat_detailed_cumulative_data_labels' => $pat_detailed_cumulative_data_labels, 'pat_detailed_cumulative_data_actual' => $pat_detailed_cumulative_data_actual, 'pat_detailed_cumulative_data_target' => $pat_detailed_cumulative_data_target,'pat_detailed_quarterly_data_labels' => $pat_detailed_quarterly_data_labels, 'pat_detailed_quarterly_data_actual' => $pat_detailed_quarterly_data_actual, 'pat_detailed_quarterly_data_target' => $pat_detailed_quarterly_data_target, 'pat_detailed_monthly_variance_data_labels' => $pat_detailed_monthly_variance_data_labels, 'pat_detailed_monthly_variance_data_actual' => $pat_detailed_monthly_variance_data_actual, 'pat_detailed_cumatively_variance_data_labels' => $pat_detailed_cumatively_variance_data_labels, 'pat_detailed_cumatively_variance_data_actual' => $pat_detailed_cumatively_variance_data_actual, 'pat_detailed_quarterly_variance_data_labels' => $pat_detailed_quarterly_variance_data_labels, 'pat_detailed_quarterly_variance_data_actual' => $pat_detailed_quarterly_variance_data_actual,
                'ebitda' => $ebitda,
                'detailed_breakdown_collection' => $GLOBALS['detailed_breakdown_collection']
                ]);
            } else {
                return redirect()->route($landingPage);
            }

        }
        return redirect()->route('main');

    }

}
