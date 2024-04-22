<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Async\Pool;

class PropertyInvestmentController extends Controller {

    public function __construct() { }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke() {

        //$viewData = $this->loadViewData();
        $GLOBALS['entity_name'] = "Investment";
        $GLOBALS['vertical'] = "Property";
        $GLOBALS['industry'] = "Investment";

        $GLOBALS['fiscalyear'] = ""; //Value is calculated below
        $GLOBALS['breakdown_account_name'] = ""; //Don't delete or alter, used below
        $GLOBALS['parent_account_name'] = ""; //Don't delete or alter, used below

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
            // End
            
            // Start REVENUE
            $pool_revenue = Pool::create();
            $pool_revenue[] = async(function(){
                $value_revenue = DB::connection('redshift')->select("SELECT 
                industry,ytd_revenue,ytd_target,ytd_variance
                FROM reporting.industryrevenue_lvl
                WHERE industry='".$GLOBALS['industry']."';");                
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

            // Start REVENUE SMALL OVERVIEW  GRAPH
            $pool_revenue_overview_graph = Pool::create();
            $pool_revenue_overview_graph[] = async(function(){
                $value_revenue_overview_graph = DB::connection('redshift')->select("SELECT 
                industry,
                date,
                dashboard_amount as dashboard_amount,
                target_amount as target_amount
                FROM reporting.pnl_monthly
                WHERE fiscal_year=".$GLOBALS['fiscalyear']."
                AND account_name = 'Revenue'
                AND vertical='".$GLOBALS['vertical']."'
                AND industry='".$GLOBALS['industry']."'
                order by industry, fiscal_month
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
                $value_revenue_breakdown = DB::connection('redshift')->select("SELECT industry, entity_name,revenue 
                FROM reporting.industrypiechart_revenue
                WHERE industry='".$GLOBALS['industry']."'
             ");                
                return $value_revenue_breakdown;
                DB::close($value_revenue_breakdown);
            });

            $revenue_breakdown_result = await($pool_revenue_breakdown);
            //dd($revenue_breakdown_result);
            $revenue_breakdown = $revenue_breakdown_result[0];

            $revenue_breakdown_labels=[];
            $revenue_breakdown_data=[];
            $revenue_breakdown_total = 0;

            foreach($revenue_breakdown as $breakdown){
                $revenue_breakdown_total += $breakdown->revenue; 
            }

            foreach($revenue_breakdown as $breakdown){
                array_push($revenue_breakdown_labels, $breakdown->entity_name);

                $percentage_breakdown = ($breakdown->revenue / $revenue_breakdown_total) * 100; 
                $percentage_breakdown = round($percentage_breakdown, 2);
                array_push($revenue_breakdown_data, $percentage_breakdown);
            }

            // End Revenue Breakdown

            //Start Revenue - Detailed Data - Monthly / Actual
            $pool_revenue_detailed_monthly = Pool::create();
            $pool_revenue_detailed_monthly[] = async(function(){
                $value_revenue_detailed_monthly = DB::connection('redshift')->select("SELECT industry, month_year , dashboard_amount, target_amount
                FROM reporting.industrygraph1_revenue
                WHERE industry='".$GLOBALS['industry']."'
                ");                
                return $value_revenue_detailed_monthly;
                DB::close($value_revenue_detailed_monthly);
            });

            $revenue_detailed_monthly_result = await($pool_revenue_detailed_monthly);
            $revenue_detailed_monthly = $revenue_detailed_monthly_result[0];

            //dd($revenue_detailed_monthly_result[0]);

            $revenue_detailed_monthly_labels=[];
            $revenue_detailed_monthly_data_actual=[];
            $revenue_detailed_monthly_data_target=[];

            foreach($revenue_detailed_monthly as $breakdown){
                array_push($revenue_detailed_monthly_labels, $breakdown->month_year);
                array_push($revenue_detailed_monthly_data_actual, $breakdown->dashboard_amount);
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
                $value_revenue_detailed_quarterly = DB::connection('redshift')->select("SELECT industry,fiscal_quarter, dashboard_amount, target_amount
                FROM reporting.industryquartely_revenue
                WHERE industry='".$GLOBALS['industry']."'
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
                array_push($revenue_detailed_quarterly_data_actual, $breakdown->dashboard_amount);
                array_push($revenue_detailed_quarterly_data_target, $breakdown->target_amount);
            }


            //dd($revenue_detailed_cumulative_data_target);
            // End Revenue - Detailed Data - quarterly Actual / Target

            //Start Revenue - Detailed Data -  Monthly Variance
            $pool_revenue_detailed_monthly_variance = Pool::create();
            $pool_revenue_detailed_monthly_variance[] = async(function(){
                $value_revenue_detailed_monthly_variance = DB::connection('redshift')->select("SELECT industry, fiscal_month, month_year, monthly_variance
                FROM reporting.industrymonthly_revenue
                WHERE industry='".$GLOBALS['industry']."'
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
                $value_revenue_detailed_quarterly_variance = DB::connection('redshift')->select("SELECT industry,fiscal_quarter,quarterly_variance
                FROM reporting.industryqrtvariance_revenue
                WHERE industry='".$GLOBALS['industry']."'
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
                $value_cos = DB::connection('redshift')->select("SELECT 
                industry,ytd_revenue,ytd_target,ytd_variance
                FROM reporting.industrycos_lvl
                WHERE industry='".$GLOBALS['industry']."';");                
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
                industry,
                date,
                dashboard_amount as dashboard_amount,
                target_amount as target_amount
                FROM reporting.pnl_monthly
                WHERE fiscal_year=".$GLOBALS['fiscalyear']."
                AND account_name = 'Cost of Sales'
                AND vertical='".$GLOBALS['vertical']."'
                AND industry='".$GLOBALS['industry']."'
                order by industry, fiscal_month
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
                $value_cos_breakdown = DB::connection('redshift')->select("SELECT industry, entity_name,revenue
                FROM reporting.industrypiechart_cos
                WHERE industry='".$GLOBALS['industry']."'
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
                array_push($cos_breakdown_labels, $breakdown->entity_name);

                $percentage_breakdown = ($breakdown->revenue / $cos_breakdown_total) * 100; 
                $percentage_breakdown = round($percentage_breakdown, 2);
                array_push($cos_breakdown_data, $percentage_breakdown);
            }

            // End COS Breakdown

             //Start COS - Detailed Data - Monthly / Actual
             $pool_cos_detailed_monthly = Pool::create();
             $pool_cos_detailed_monthly[] = async(function(){
                 $value_cos_detailed_monthly = DB::connection('redshift')->select("SELECT industry, month_year , dashboard_amount, target_amount
                 FROM reporting.industrygraph1_cos
                 WHERE industry='".$GLOBALS['industry']."'
                 ;");
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
                 array_push($cos_detailed_monthly_data_actual, $breakdown->month_year);
                 array_push($cos_detailed_monthly_data_target, $breakdown->target_amount);
  
             }
 
 
             //dd($cos_detailed_monthly);
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
                $value_cos_detailed_quarterly = DB::connection('redshift')->select("SELECT industry,fiscal_quarter, dashboard_amount, target_amount
                FROM reporting.industryquartely_cos
                WHERE industry='".$GLOBALS['industry']."'
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
                array_push($cos_detailed_quarterly_data_actual, $breakdown->dashboard_amount);
                array_push($cos_detailed_quarterly_data_target, $breakdown->target_amount);
            }


            //dd($cos_detailed_cumulative_data_target);
            // End COS - Detailed Data - quarterly Actual / Target

             //Start COS - Detailed Data -  Monthly Variance
             $pool_cos_detailed_monthly_variance = Pool::create();
             $pool_cos_detailed_monthly_variance[] = async(function(){
                 $value_cos_detailed_monthly_variance = DB::connection('redshift')->select("SELECT industry, fiscal_month,month_year,monthly_variance
                 FROM reporting.industrymonthly_cos
                 WHERE industry='".$GLOBALS['industry']."'
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
                $value_cos_detailed_cumatively_variance = DB::connection('redshift')->select("SELECT industry, fiscal_month, month_year, cum_variance
                FROM reporting.industrycmuvariance_cos
                WHERE industry='".$GLOBALS['industry']."'
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
                array_push($cos_detailed_cumatively_variance_data_actual, $breakdown->cum_variance);
            }


            //dd($cos_detailed_cumulative_data_target);
            // End COS - Detailed Data -  Cumatively Variance

            //Start COS - Detailed Data -  quarterly Variance
            $pool_cos_detailed_quarterly_variance = Pool::create();
            $pool_cos_detailed_quarterly_variance[] = async(function(){
                $value_cos_detailed_quarterly_variance = DB::connection('redshift')->select("SELECT industry,fiscal_quarter,quarterly_variance
                FROM reporting.industryqrtvariance_cos
                WHERE industry='".$GLOBALS['industry']."'
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
                $value_gpl = DB::connection('redshift')->select("SELECT 
                industry,ytd_revenue,ytd_target,ytd_variance
                FROM reporting.industrygpl_lvl
                WHERE industry='".$GLOBALS['industry']."';");                
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
                industry,
                date,
                dashboard_amount as dashboard_amount,
                target_amount as target_amount
                FROM reporting.pnl_monthly
                WHERE fiscal_year=".$GLOBALS['fiscalyear']."
                AND account_name = 'Gross profit/(loss)'
                AND vertical='".$GLOBALS['vertical']."'
                AND industry='".$GLOBALS['industry']."'
                order by industry, fiscal_month
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
                $value_gpl_breakdown = DB::connection('redshift')->select("SELECT industry, entity_name,revenue
                FROM reporting.industrypiechart_gpl
                WHERE industry='".$GLOBALS['industry']."'
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
                array_push($gpl_breakdown_labels, $breakdown->entity_name);

                $percentage_breakdown = ($breakdown->revenue / $gpl_breakdown_total) * 100; 
                $percentage_breakdown = round($percentage_breakdown, 2);
                array_push($gpl_breakdown_data, $percentage_breakdown);
            }

            // End GROSS PROFIT / LOSS Breakdown Breakdown

            //Start GROSS PROFIT / LOSS - Detailed Data - Monthly / Actual
            $pool_gpl_detailed_monthly = Pool::create();
            $pool_gpl_detailed_monthly[] = async(function(){
                $value_gpl_detailed_monthly = DB::connection('redshift')->select("SELECT industry, month_year , dashboard_amount, target_amount
                FROM reporting.industrygraph1_gpl
                WHERE industry='".$GLOBALS['industry']."'
                ;");
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
                array_push($gpl_detailed_monthly_data_actual, $breakdown->month_year);
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
               $value_gpl_detailed_quarterly = DB::connection('redshift')->select("SELECT industry,fiscal_quarter, dashboard_amount, target_amount
               FROM reporting.industryquartely_gpl
               WHERE industry='".$GLOBALS['industry']."'
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
               array_push($gpl_detailed_quarterly_data_actual, $breakdown->dashboard_amount);
               array_push($gpl_detailed_quarterly_data_target, $breakdown->target_amount);
           }


           //dd($gpl_detailed_cumulative_data_target);
           // End GROSS PROFIT / LOSS - Detailed Data - quarterly Actual / Target

            //Start GROSS PROFIT / LOSS - Detailed Data -  Monthly Variance
            $pool_gpl_detailed_monthly_variance = Pool::create();
            $pool_gpl_detailed_monthly_variance[] = async(function(){
                $value_gpl_detailed_monthly_variance = DB::connection('redshift')->select("SELECT industry, fiscal_month,month_year,monthly_variance
                FROM reporting.industrymonthly_gpl
                WHERE industry='".$GLOBALS['industry']."'
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
               $value_gpl_detailed_quarterly_variance = DB::connection('redshift')->select("SELECT industry,fiscal_quarter,quarterly_variance
               FROM reporting.industryqrtvariance_gpl
               WHERE industry='".$GLOBALS['industry']."'
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
                $value_opex = DB::connection('redshift')->select("SELECT 
                industry,ytd_revenue,ytd_target,ytd_variance
                FROM reporting.industryoperating_lvl
                WHERE industry='".$GLOBALS['industry']."';");                
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
                industry,
                date,
                dashboard_amount as dashboard_amount,
                target_amount as target_amount
                FROM reporting.pnl_monthly
                WHERE fiscal_year=".$GLOBALS['fiscalyear']."
                AND account_name = 'Operating expense'
                AND vertical='".$GLOBALS['vertical']."'
                AND industry='".$GLOBALS['industry']."'
                order by industry, fiscal_month
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
                $value_opex_breakdown = DB::connection('redshift')->select("SELECT industry, entity_name,revenue
                FROM reporting.industrypiechart_op
                WHERE industry='".$GLOBALS['industry']."'
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
                array_push($opex_breakdown_labels, $breakdown->entity_name);

                $percentage_breakdown = ($breakdown->revenue / $opex_breakdown_total) * 100; 
                $percentage_breakdown = round($percentage_breakdown, 2);
                array_push($opex_breakdown_data, $percentage_breakdown);
            }

            // End OPEX Breakdown Breakdown

            //Start OPEX - Detailed Data - Monthly / Actual
            $pool_opex_detailed_monthly = Pool::create();
            $pool_opex_detailed_monthly[] = async(function(){
                $value_opex_detailed_monthly = DB::connection('redshift')->select("SELECT industry, month_year , dashboard_amount, target_amount
                FROM reporting.industrygraph1_op
                WHERE industry='".$GLOBALS['industry']."'
                ;");
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
                array_push($opex_detailed_monthly_data_actual, $breakdown->month_year);
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
               $value_opex_detailed_quarterly = DB::connection('redshift')->select("SELECT industry,fiscal_quarter, dashboard_amount, target_amount
               FROM reporting.industryquartely_op
               WHERE industry='".$GLOBALS['industry']."'
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
               array_push($opex_detailed_quarterly_data_actual, $breakdown->dashboard_amount);
               array_push($opex_detailed_quarterly_data_target, $breakdown->target_amount);
           }


           //dd($opex_detailed_cumulative_data_target);
           // End OPEX - Detailed Data - quarterly Actual / Target

            //Start OPEX - Detailed Data -  Monthly Variance
            $pool_opex_detailed_monthly_variance = Pool::create();
            $pool_opex_detailed_monthly_variance[] = async(function(){
                $value_opex_detailed_monthly_variance = DB::connection('redshift')->select("SELECT industry, fiscal_month,month_year,monthly_variance
                FROM reporting.industrymonthly_op
                WHERE industry='".$GLOBALS['industry']."'
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
               $value_opex_detailed_quarterly_variance = DB::connection('redshift')->select("SELECT industry,fiscal_quarter,quarterly_variance
               FROM reporting.industryqrtvariance_op
               WHERE industry='".$GLOBALS['industry']."'
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
                $value_ebitda = DB::connection('redshift')->select("SELECT 
                industry,ytd_revenue,ytd_target,ytd_variance
                FROM reporting.industryebitda_lvl
                WHERE industry='".$GLOBALS['industry']."';");                
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

            // Start EBITDA Breakdown
            $pool_ebitda_breakdown = Pool::create();
            $pool_ebitda_breakdown[] = async(function(){
                $value_ebitda_breakdown = DB::connection('redshift')->select("SELECT industry, entity_name,revenue
                FROM reporting.industrypiechart_ebitda
                WHERE industry='".$GLOBALS['industry']."'
                ");                
                return $value_ebitda_breakdown;
                DB::close($value_ebitda_breakdown);
            });

            $ebitda_breakdown_result = await($pool_ebitda_breakdown);
            $ebitda_breakdown = $ebitda_breakdown_result[0];

            $ebitda_breakdown_labels=[];
            $ebitda_breakdown_data=[];
            $ebitda_breakdown_total = 0;

            foreach($ebitda_breakdown as $breakdown){
                $ebitda_breakdown_total += $breakdown->revenue; 
            }

            foreach($ebitda_breakdown as $breakdown){
                array_push($ebitda_breakdown_labels, $breakdown->entity_name);

                $percentage_breakdown = ($breakdown->revenue / $ebitda_breakdown_total) * 100; 
                $percentage_breakdown = round($percentage_breakdown, 2);
                array_push($ebitda_breakdown_data, $percentage_breakdown);
            }

            // End EBITDA Breakdown

            // Start PAT
            $pool_pat = Pool::create();
            $pool_pat[] = async(function(){
                $value_pat = DB::connection('redshift')->select("SELECT 
                industry,ytd_revenue,ytd_target,ytd_variance
                FROM reporting.industrypat_lvl
                WHERE industry='".$GLOBALS['industry']."';");                
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
                industry,
                date,
                dashboard_amount as dashboard_amount,
                target_amount as target_amount
                FROM reporting.pnl_monthly
                WHERE fiscal_year=".$GLOBALS['fiscalyear']."
                AND account_name = 'PAT'
                AND vertical='".$GLOBALS['vertical']."'
                AND industry='".$GLOBALS['industry']."'
                order by industry, fiscal_month
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
                $value_pat_breakdown = DB::connection('redshift')->select("SELECT industry, entity_name,revenue
                FROM reporting.industrypiechart_pat
                WHERE industry='".$GLOBALS['industry']."'
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
                array_push($pat_breakdown_labels, $breakdown->entity_name);

                $percentage_breakdown = ($breakdown->revenue / $pat_breakdown_total) * 100; 
                $percentage_breakdown = round($percentage_breakdown, 2);
                array_push($pat_breakdown_data, $percentage_breakdown);
            }

            // End PAT Breakdown

            //Start PAT - Detailed Data - Monthly / Actual
            $pool_pat_detailed_monthly = Pool::create();
            $pool_pat_detailed_monthly[] = async(function(){
                $value_pat_detailed_monthly = DB::connection('redshift')->select("SELECT industry, month_year , dashboard_amount, target_amount
                FROM reporting.industrygraph1_pat
                WHERE industry='".$GLOBALS['industry']."'
                ;");
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
                array_push($pat_detailed_monthly_data_actual, $breakdown->month_year);
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
               $value_pat_detailed_quarterly = DB::connection('redshift')->select("SELECT industry,fiscal_quarter, dashboard_amount, target_amount
               FROM reporting.industryquartely_pat
               WHERE industry='".$GLOBALS['industry']."'
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
               array_push($pat_detailed_quarterly_data_actual, $breakdown->dashboard_amount);
               array_push($pat_detailed_quarterly_data_target, $breakdown->target_amount);
           }


           //dd($pat_detailed_cumulative_data_target);
           // End PAT - Detailed Data - quarterly Actual / Target

            //Start PAT - Detailed Data -  Monthly Variance
            $pool_pat_detailed_monthly_variance = Pool::create();
            $pool_pat_detailed_monthly_variance[] = async(function(){
                $value_pat_detailed_monthly_variance = DB::connection('redshift')->select("SELECT industry, fiscal_month,month_year,monthly_variance
                FROM reporting.industrymonthly_pat
                WHERE industry='".$GLOBALS['industry']."'
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
               $value_pat_detailed_quarterly_variance = DB::connection('redshift')->select("SELECT industry,fiscal_quarter,quarterly_variance
               FROM reporting.industryqrtvariance_pat
               WHERE industry='".$GLOBALS['industry']."'
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


            
        //dd($GLOBALS['detailed_breakdown_collection']);

            if($r_berjaya) {
                return view('property.investment', [
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
                'ebitda_breakdown_labels'=>$ebitda_breakdown_labels,
                'ebitda_breakdown_data'=>$ebitda_breakdown_data,
                ]);
            } else {
                return redirect()->route($landingPage);
            }

        }
        return redirect()->route('main');

    }

}
