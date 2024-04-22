<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use Spatie\Async\Pool;

class APIController extends Controller {


    public function __construct() {}

    public function berjaya_group() { /* Overall Level */

        /** 
         * Revenue
        */

        $pool_revenue = Pool::create();
        $pool_revenue[] = async(function(){
            $value_revenue = DB::connection('redshift')->select("SELECT overall_segment,ytd_revenue,ytd_target,ytd_variance
            FROM reporting.overallrevenue_lvl
            ;");                
            return $value_revenue;
            DB::close($value_revenue);
        });

        $revenue_result = await($pool_revenue);
        $revenue = $revenue_result[0][0];

        $data_revenue['data_revenue'] = $revenue;

        /** 
         * Cost of Sale
        */

        $pool_cos = Pool::create();
        $pool_cos[] = async(function(){
            $value_cos = DB::connection('redshift')->select("SELECT overall_segment,ytd_revenue,ytd_target,ytd_variance
            FROM reporting.overallcos_lvl
            ;");                
            return $value_cos;
            DB::close($value_cos);
        });

        $cos_result = await($pool_cos);
        $cos = $cos_result[0][0];

        $data_cost_of_sale['data_cost_of_sale'] = $cos;
        
        /** 
         * Gross Profit / Loss
        */

        $pool_gpl = Pool::create();
        $pool_gpl[] = async(function(){
            $value_gpl = DB::connection('redshift')->select("SELECT overall_segment,ytd_revenue,ytd_target,ytd_variance
            FROM reporting.overallgpl_lvl
            ;");                
            return $value_gpl;
            DB::close($value_gpl);
        });

        $gpl_result = await($pool_gpl);
        $gpl = $gpl_result[0][0];

        $data_profit_loss['data_profit_loss'] = $gpl;

        /** 
         * Operating Expenses
        */

        $pool_opex = Pool::create();
        $pool_opex[] = async(function(){
            $value_opex = DB::connection('redshift')->select("SELECT overall_segment,ytd_revenue,ytd_target,ytd_variance
            FROM reporting.overalloperating_lvl
            ;");                
            return $value_opex;
            DB::close($value_opex);
        });

        $opex_result = await($pool_opex);
        $opex = $opex_result[0][0];

        $data_operating_expenses['data_operating_expenses'] = $opex;

        /** 
         * PAT
        */

        $pool_pat = Pool::create();
        $pool_pat[] = async(function(){
            $value_pat = DB::connection('redshift')->select("SELECT overall_segment,ytd_revenue,ytd_target,ytd_variance
            FROM reporting.overallpat_lvl
            ;");                
            return $value_pat;
            DB::close($value_pat);
        });

        $pat_result = await($pool_pat);
        $pat = $pat_result[0][0];

        $data_pat['data_pat'] = $pat;

        /** 
         * EBITDA
        */

        $pool_ebitda = Pool::create();
        $pool_ebitda[] = async(function(){
            $value_ebitda = DB::connection('redshift')->select("SELECT overall_segment,ytd_revenue,ytd_target,ytd_variance
            FROM reporting.overallebitda_lvl
            ;");                
            return $value_ebitda;
            DB::close($value_ebitda);
        });

        $ebitda_result = await($pool_ebitda);
        $ebitda = $ebitda_result[0][0];

        $data_ebitda['data_ebitda'] = $ebitda;


        $response['data'] = array($data_revenue, $data_cost_of_sale, $data_profit_loss, $data_operating_expenses, $data_pat, $data_ebitda);
        $response['result'] = 'success';

        return response()->json($response); 

    }

    public function vertical(Request $request) { /* Vertical Level */

        $GLOBALS['vertical'] = $request['vertical'];

        /** 
         * Revenue
        */

        $pool_revenue = Pool::create();
        $pool_revenue[] = async(function(){
            $value_revenue = DB::connection('redshift')->select("SELECT vertical,ytd_revenue,ytd_target,ytd_variance FROM
            reporting.pnl_vertical_services_revenue WHERE vertical = '".$GLOBALS['vertical']."'
            ;");                
            return $value_revenue;
            DB::close($value_revenue);
        });

        $revenue_result = await($pool_revenue);
        if(!empty($revenue_result[0][0])) {
            $revenue = $revenue_result[0][0];
        } else {
            $nodata_json = '[[{"entity_name": "No data","ytd_revenue": "0.0000","ytd_target": "0.0000","ytd_variance": "0.0000"}]]';
            $revenue = json_decode($nodata_json)[0][0];
        }

        $data_revenue['data_revenue'] = $revenue;

        /** 
         * Cost of Sale
        */

        $pool_cos = Pool::create();
        $pool_cos[] = async(function(){
            $value_cos = DB::connection('redshift')->select("SELECT vertical,ytd_revenue,ytd_target,ytd_variance FROM 
            reporting.pnl_vertical_costofsales WHERE vertical = '".$GLOBALS['vertical']."'
            ;");                
            return $value_cos;
            DB::close($value_cos);
        });

        $cos_result = await($pool_cos);
        if(!empty($cos_result[0][0])) {
            $cos = $cos_result[0][0];
        } else {
            $nodata_json = '[[{"entity_name": "No data","ytd_revenue": "0.0000","ytd_target": "0.0000","ytd_variance": "0.0000"}]]';
            $cos = json_decode($nodata_json)[0][0];
        }

        $data_cost_of_sale['data_cost_of_sale'] = $cos;
        
        /** 
         * Gross Profit / Loss
        */

        $pool_gpl = Pool::create();
        $pool_gpl[] = async(function(){
            $value_gpl = DB::connection('redshift')->select("SELECT vertical,ytd_revenue,ytd_target,ytd_variance FROM
            reporting.pnl_vertical_grossprofit WHERE vertical = '".$GLOBALS['vertical']."'
            ;");                
            return $value_gpl;
            DB::close($value_gpl);
        });

        $gpl_result = await($pool_gpl);
        if(!empty($gpl_result[0][0])) {
            $gpl = $gpl_result[0][0];
        } else {
            $nodata_json = '[[{"entity_name": "No data","ytd_revenue": "0.0000","ytd_target": "0.0000","ytd_variance": "0.0000"}]]';
            $gpl = json_decode($nodata_json)[0][0];
        }

        $data_profit_loss['data_profit_loss'] = $gpl;

        /** 
         * Operating Expenses
        */

        $pool_opex = Pool::create();
        $pool_opex[] = async(function(){
            $value_opex = DB::connection('redshift')->select("SELECT vertical,ytd_revenue,ytd_target,ytd_variance FROM
            reporting.pnl_vertical_operatingexpense WHERE vertical = '".$GLOBALS['vertical']."'
            ;");                
            return $value_opex;
            DB::close($value_opex);
        });

        $opex_result = await($pool_opex);
        if(!empty($opex_result[0][0])) {
            $opex = $opex_result[0][0];
        } else {
            $nodata_json = '[[{"entity_name": "No data","ytd_revenue": "0.0000","ytd_target": "0.0000","ytd_variance": "0.0000"}]]';
            $opex = json_decode($nodata_json)[0][0];
        }

        $data_operating_expenses['data_operating_expenses'] = $opex;

        /** 
         * PAT
        */

        $pool_pat = Pool::create();
        $pool_pat[] = async(function(){
            $value_pat = DB::connection('redshift')->select("SELECT vertical,ytd_revenue,ytd_target,ytd_variance FROM
            reporting.pnl_vertical_pat WHERE vertical = '".$GLOBALS['vertical']."'
            ;");                
            return $value_pat;
            DB::close($value_pat);
        });

        $pat_result = await($pool_pat);
        if(!empty($pat_result[0][0])) {
            $pat = $pat_result[0][0];
        } else {
            $nodata_json = '[[{"entity_name": "No data","ytd_revenue": "0.0000","ytd_target": "0.0000","ytd_variance": "0.0000"}]]';
            $pat = json_decode($nodata_json)[0][0];
        }

        $data_pat['data_pat'] = $pat;

        /** 
         * EBITDA
        */

        $pool_ebitda = Pool::create();
        $pool_ebitda[] = async(function(){
            $value_ebitda = DB::connection('redshift')->select("SELECT vertical,ytd_revenue,ytd_target,ytd_variance FROM
            reporting.pnl_vertical_ebitda WHERE vertical = '".$GLOBALS['vertical']."'
            ;");                
            return $value_ebitda;
            DB::close($value_ebitda);
        });

        $ebitda_result = await($pool_ebitda);
        if(!empty($ebitda_result[0][0])) {
            $ebitda = $ebitda_result[0][0];
        } else {
            $nodata_json = '[[{"entity_name": "No data","ytd_revenue": "0.0000","ytd_target": "0.0000","ytd_variance": "0.0000"}]]';
            $ebitda = json_decode($nodata_json)[0][0];
        }

        $data_ebitda['data_ebitda'] = $ebitda;


        $response['data'] = array($data_revenue, $data_cost_of_sale, $data_profit_loss, $data_operating_expenses, $data_pat, $data_ebitda);
        $response['result'] = 'success';

        return response()->json($response); 

    }

    public function industry(Request $request) { /* Industry Level */

        $GLOBALS['vertical'] = $request['vertical'];
        $GLOBALS['industry'] = $request['industry'];

        /** 
         * Revenue
        */

        $pool_revenue = Pool::create();
        $pool_revenue[] = async(function(){
            $value_revenue = DB::connection('redshift')->select("SELECT industry,ytd_revenue,ytd_target,ytd_variance FROM reporting.industryrevenue_lvl WHERE industry='".$GLOBALS['industry']."';");                
            return $value_revenue;
            DB::close($value_revenue);
        });

        $revenue_result = await($pool_revenue);
        if(!empty($revenue_result[0][0])) {
            $revenue = $revenue_result[0][0];
        } else {
            $nodata_json = '[[{"entity_name": "No data","ytd_revenue": "0.0000","ytd_target": "0.0000","ytd_variance": "0.0000"}]]';
            $revenue = json_decode($nodata_json)[0][0];
        }

        $data_revenue['data_revenue'] = $revenue;

        /** 
         * Cost of Sale
        */

        $pool_cos = Pool::create();
        $pool_cos[] = async(function(){
            $value_cos = DB::connection('redshift')->select("SELECT industry,ytd_revenue,ytd_target,ytd_variance FROM reporting.industrycos_lvl WHERE industry='".$GLOBALS['industry']."';");                
            return $value_cos;
            DB::close($value_cos);
        });

        $cos_result = await($pool_cos);
        if(!empty($cos_result[0][0])) {
            $cos = $cos_result[0][0];
        } else {
            $nodata_json = '[[{"entity_name": "No data","ytd_revenue": "0.0000","ytd_target": "0.0000","ytd_variance": "0.0000"}]]';
            $cos = json_decode($nodata_json)[0][0];
        }

        $data_cost_of_sale['data_cost_of_sale'] = $cos;

        /** 
         * Gross Profit / Loss
        */

        $pool_gpl = Pool::create();
        $pool_gpl[] = async(function(){
            $value_gpl = DB::connection('redshift')->select("SELECT industry,ytd_revenue,ytd_target,ytd_variance FROM reporting.industrygpl_lvl WHERE industry='".$GLOBALS['industry']."';");                
            return $value_gpl;
            DB::close($value_gpl);
        });

        $gpl_result = await($pool_gpl);
        if(!empty($gpl_result[0][0])) {
            $gpl = $gpl_result[0][0];
        } else {
            $nodata_json = '[[{"entity_name": "No data","ytd_revenue": "0.0000","ytd_target": "0.0000","ytd_variance": "0.0000"}]]';
            $gpl = json_decode($nodata_json)[0][0];
        }

        $data_profit_loss['data_profit_loss'] = $gpl;

        /** 
         * Operating Expenses
        */

        $pool_opex = Pool::create();
        $pool_opex[] = async(function(){
            $value_opex = DB::connection('redshift')->select("SELECT industry,ytd_revenue,ytd_target,ytd_variance FROM reporting.industryoperating_lvl WHERE industry='".$GLOBALS['industry']."';");                
            return $value_opex;
            DB::close($value_opex);
        });

        $opex_result = await($pool_opex);
        if(!empty($opex_result[0][0])) {
            $opex = $opex_result[0][0];
        } else {
            $nodata_json = '[[{"entity_name": "No data","ytd_revenue": "0.0000","ytd_target": "0.0000","ytd_variance": "0.0000"}]]';
            $opex = json_decode($nodata_json)[0][0];
        }

        $data_operating_expenses['data_operating_expenses'] = $opex;

        /** 
         * PAT
        */

        $pool_pat = Pool::create();
        $pool_pat[] = async(function(){
            $value_pat = DB::connection('redshift')->select("SELECT industry,ytd_revenue,ytd_target,ytd_variance FROM reporting.industrypat_lvl WHERE industry='".$GLOBALS['industry']."';");                
            return $value_pat;
            DB::close($value_pat);
        });

        $pat_result = await($pool_pat);
        if(!empty($pat_result[0][0])) {
            $pat = $pat_result[0][0];
        } else {
            $nodata_json = '[[{"entity_name": "No data","ytd_revenue": "0.0000","ytd_target": "0.0000","ytd_variance": "0.0000"}]]';
            $pat = json_decode($nodata_json)[0][0];
        }

        $data_pat['data_pat'] = $pat;

        /** 
         * EBITDA
        */

        $pool_ebitda = Pool::create();
        $pool_ebitda[] = async(function(){
            $value_ebitda = DB::connection('redshift')->select("SELECT industry,ytd_revenue,ytd_target,ytd_variance FROM reporting.industryebitda_lvl WHERE industry='".$GLOBALS['industry']."';");                
            return $value_ebitda;
            DB::close($value_ebitda);
        });

        $ebitda_result = await($pool_ebitda);
        if(!empty($ebitda_result[0][0])) {
            $ebitda = $ebitda_result[0][0];
        } else {
            $nodata_json = '[[{"entity_name": "No data","ytd_revenue": "0.0000","ytd_target": "0.0000","ytd_variance": "0.0000"}]]';
            $ebitda = json_decode($nodata_json)[0][0];
        }

        $data_ebitda['data_ebitda'] = $ebitda;


        $response['data'] = array($data_revenue, $data_cost_of_sale, $data_profit_loss, $data_operating_expenses, $data_pat, $data_ebitda);
        $response['result'] = 'success';

        return response()->json($response); 

    }

    public function company(Request $request) { /* Company Level */

        $GLOBALS['entity_name'] = $request['entity_name'];

        /** 
         * Revenue
        */

        $pool_revenue = Pool::create();
        $pool_revenue[] = async(function(){
            $value_revenue = DB::connection('redshift')->select("SELECT entity_name, sum(dashboard_amount) AS ytd_revenue, sum(CASE WHEN pnl.date <= maxd.max_data_date THEN target_amount ELSE 0 END) AS ytd_target,
            (ytd_target-ytd_revenue) as ytd_variance FROM reporting.pnl_monthly pnl INNER JOIN reporting.max_date_with_actual maxd ON 1=1 WHERE account_name='Revenue'
            AND entity_name = '".$GLOBALS['entity_name']."'GROUP BY entity_name");                
            return $value_revenue;
            DB::close($value_revenue);
        });

        $revenue_result = await($pool_revenue);
        if(!empty($revenue_result[0][0])) {
            $revenue = $revenue_result[0][0];
        } else {
            $nodata_json = '[[{"entity_name": "No data","ytd_revenue": "0.0000","ytd_target": "0.0000","ytd_variance": "0.0000"}]]';
            $revenue = json_decode($nodata_json)[0][0];
        }

        $data_revenue['data_revenue'] = $revenue;

        /** 
         * Cost of Sale
        */

        $pool_cos = Pool::create();
        $pool_cos[] = async(function(){
            $value_cos = DB::connection('redshift')->select("SELECT entity_name, sum(dashboard_amount) AS ytd_revenue, sum(CASE WHEN pnl.date <= maxd.max_data_date THEN target_amount ELSE 0 END) AS ytd_target,
            (ytd_target-ytd_revenue) as ytd_variance FROM reporting.pnl_monthly pnl INNER JOIN reporting.max_date_with_actual maxd ON 1=1 WHERE account_name='Cost of Sales'
            AND entity_name = '".$GLOBALS['entity_name']."'GROUP BY entity_name");                
            return $value_cos;
            DB::close($value_cos);
        });

        $cos_result = await($pool_cos);
        if(!empty($cos_result[0][0])) {
            $cos = $cos_result[0][0];
        } else {
            $nodata_json = '[[{"entity_name": "No data","ytd_revenue": "0.0000","ytd_target": "0.0000","ytd_variance": "0.0000"}]]';
            $cos = json_decode($nodata_json)[0][0];
        }

        $data_cost_of_sale['data_cost_of_sale'] = $cos;

        /** 
         * Gross Profit / Loss
        */

        $pool_gpl = Pool::create();
        $pool_gpl[] = async(function(){
            $value_gpl = DB::connection('redshift')->select("SELECT entity_name, sum(dashboard_amount) AS ytd_revenue, sum(CASE WHEN pnl.date <= maxd.max_data_date THEN target_amount ELSE 0 END) AS ytd_target,
            (ytd_target-ytd_revenue) as ytd_variance FROM reporting.pnl_monthly pnl INNER JOIN reporting.max_date_with_actual maxd ON 1=1 WHERE account_name='Gross profit/(loss)'
            AND entity_name = '".$GLOBALS['entity_name']."'GROUP BY entity_name;");                
            return $value_gpl;
            DB::close($value_gpl);
        });

        $gpl_result = await($pool_gpl);
        if(!empty($gpl_result[0][0])) {
            $gpl = $gpl_result[0][0];
        } else {
            $nodata_json = '[[{"entity_name": "No data","ytd_revenue": "0.0000","ytd_target": "0.0000","ytd_variance": "0.0000"}]]';
            $gpl = json_decode($nodata_json)[0][0];
        }

        $data_profit_loss['data_profit_loss'] = $gpl;

        /** 
         * Operating Expenses
        */

        $pool_opex = Pool::create();
        $pool_opex[] = async(function(){
            $value_opex = DB::connection('redshift')->select("SELECT entity_name, sum(dashboard_amount) AS ytd_revenue, sum(CASE WHEN pnl.date <= maxd.max_data_date THEN target_amount ELSE 0 END) AS ytd_target,
            (ytd_target-ytd_revenue) as ytd_variance FROM reporting.pnl_monthly pnl INNER JOIN reporting.max_date_with_actual maxd ON 1=1 WHERE account_name='Operating expense'
            AND entity_name = '".$GLOBALS['entity_name']."'GROUP BY entity_name");                
            return $value_opex;
            DB::close($value_opex);
        });

        $opex_result = await($pool_opex);
        if(!empty($opex_result[0][0])) {
            $opex = $opex_result[0][0];
        } else {
            $nodata_json = '[[{"entity_name": "No data","ytd_revenue": "0.0000","ytd_target": "0.0000","ytd_variance": "0.0000"}]]';
            $opex = json_decode($nodata_json)[0][0];
        }

        $data_operating_expenses['data_operating_expenses'] = $opex;

        /** 
         * PAT
        */

        $pool_pat = Pool::create();
        $pool_pat[] = async(function(){
            $value_pat = DB::connection('redshift')->select("SELECT entity_name, sum(dashboard_amount) AS ytd_revenue, sum(CASE WHEN pnl.date <= maxd.max_data_date THEN target_amount ELSE 0 END) AS ytd_target,
            (ytd_target-ytd_revenue) as ytd_variance FROM reporting.pnl_monthly pnl INNER JOIN reporting.max_date_with_actual maxd ON 1=1 WHERE account_name='PAT'
            AND entity_name = '".$GLOBALS['entity_name']."'GROUP BY entity_name");                
            return $value_pat;
            DB::close($value_pat);
        });

        $pat_result = await($pool_pat);
        if(!empty($pat_result[0][0])) {
            $pat = $pat_result[0][0];
        } else {
            $nodata_json = '[[{"entity_name": "No data","ytd_revenue": "0.0000","ytd_target": "0.0000","ytd_variance": "0.0000"}]]';
            $pat = json_decode($nodata_json)[0][0];
        }

        $data_pat['data_pat'] = $pat;

        /** 
         * EBITDA
        */

        $pool_ebitda = Pool::create();
        $pool_ebitda[] = async(function(){
            $value_ebitda = DB::connection('redshift')->select("SELECT entity_name, sum(dashboard_amount) AS ytd_revenue, sum(CASE WHEN pnl.date <= maxd.max_data_date THEN target_amount ELSE 0 END) AS ytd_target,
            (ytd_target-ytd_revenue) as ytd_variance FROM reporting.pnl_monthly pnl INNER JOIN reporting.max_date_with_actual maxd ON 1=1 WHERE account_name='EBITDA'
            AND entity_name = '".$GLOBALS['entity_name']."'GROUP BY entity_name");                
            return $value_ebitda;
            DB::close($value_ebitda);
        });

        $ebitda_result = await($pool_ebitda);
        if(!empty($ebitda_result[0][0])) {
            $ebitda = $ebitda_result[0][0];
        } else {
            $nodata_json = '[[{"entity_name": "No data","ytd_revenue": "0.0000","ytd_target": "0.0000","ytd_variance": "0.0000"}]]';
            $ebitda = json_decode($nodata_json)[0][0];
        }

        $data_ebitda['data_ebitda'] = $ebitda;


        $response['data'] = array($data_revenue, $data_cost_of_sale, $data_profit_loss, $data_operating_expenses, $data_pat, $data_ebitda);
        $response['result'] = 'success';

        return response()->json($response); 

    }

}

?>









