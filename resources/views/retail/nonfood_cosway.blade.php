@extends('services.index')

@section('title') Retail > Non-Food > Cosway (M) Sdn Bhd @endsection

@section('content')
<script>
var companyPage = true;
var actualColour = "#0088cc";
var actualTextLabel = "Actual";
var targetColour = "#dc3546";
var targetTextLabel = "Target";
var preChosenBackgroundColors = [actualColour,"#2aaab1","#734ba9","#e3615a","#dc3546","#cadc35","#6edc35","#dc75d4","#c5d29c","#363b54"];

var pieChartBreakdownData = [];

var detailedDataCollection ={"dataGroups" :[]};
  // START DETAILED REVENUE DATA
var revenueDetailedData = [];
</script>


<div class="slim-mainpanel company">
    <div class="container">
    <div class="slim-pageheader">
        <div>
        @if($userID == 1)
          <div class="level-up">
            <a href="/retail/non-food"><button class="btn btn-secondary mg-r-10">Non-Food <i class="fa fa-level-up"></i></button></a>
          </div>
            <a href="/retail/non-food/cosway"><button class="btn btn-success mg-r-10">Cosway MY</button></a>
            <a href="/retail/non-food/coswaytw"><button class="btn btn-primary mg-r-10">Cosway TW</button></a>
            <a href="/retail/non-food/coswayhk"><button class="btn btn-primary mg-r-10">Cosway HK</button></a>
            <a href="/retail/non-food/hrowen"><button class="btn btn-primary mg-r-10">H.R. Owen</button></a>
        @else 
            <div class="level-up">
            @if($cosway || $coswaytw || $coswayhk || $country_farms)
            @else
              <a href="/retail/non-food"><button class="btn btn-secondary mg-r-10">Non-Food <i class="fa fa-level-up"></i></button></a>
            @endif
            </div>
            @if($cosway)
              <a href="/retail/non-food/cosway"><button class="btn btn-success mg-r-10">Cosway MY</button></a>
            @endif
            @if($coswaytw)
              <a href="/retail/non-food/coswaytw"><button class="btn btn-primary mg-r-10">Cosway TW</button></a>
            @endif
            @if($coswayhk)
              <a href="/retail/non-food/coswayhk"><button class="btn btn-primary mg-r-10">Cosway HK</button></a>
            @endif
            @if($country_farms)
              <a href="/retail/non-food/hrowen"><button class="btn btn-primary mg-r-10">H.R. Owen</button></a>
            @endif
        @endif
        </div>
        <h6 class="slim-pagetitle">Retail - Non-Food - Cosway (M) Sdn Bhd</h6>
    </div>

    <div class="row row-xs">
        <div class="col-md-2">
        <div id="PrimaryRevenueCard" class="card">
            <div id="PrimaryRevenue" class="card-body pd-b-0 changable-data-group" dataGroupName="Revenue">
            <h6 class="slim-card-title"> Revenue (MYR)</h6>
            <h1 class="">{{number_format(round($revenue->ytd_revenue, 0))}}</h1>
            <p class="tx-14 mb-0"><span class="target">Target:</span> MYR {{number_format(round($revenue->ytd_target, 0))}}</p>
            <p class="tx-14"><span class="target">Variance:</span> MYR {{number_format(round($revenue->ytd_variance, 0))}}</p>
            </div>
            <div id="RevenueOverviewGraph" class="ht-50 ht-sm-70 mg-r--1"></div>
            <div class="card card-sub-data ">
                <div class="card-body">
                    <h6 class="card-sub-data-title tx-center mg-b-20">Breakdown (%)</h6>                  
                    <canvas id="Revenue_Current_Month_Breakdown"></canvas>
                    <script>
                        var revenueCurrentMonthBreakdownData = {
                                datasets: [{
                                data: <?php echo json_encode($revenue_breakdown_data); ?>,
                                backgroundColor: preChosenBackgroundColors
                                }],
                                labels: <?php echo json_encode($revenue_breakdown_labels); ?>,
                                elementId: ["Revenue_Current_Month_Breakdown"],
                                chartType: ['pie'],
                                legend: false, 
                                breakdown: true
                            };
                        pieChartBreakdownData.push(revenueCurrentMonthBreakdownData);
                    </script>
                </div>
            </div>
        </div>
        </div>
        <div class="col-md-2">
        <div id="PrimaryCOSCard" class="card">
            <div id="PrimaryCostOfSale" class="card-body pd-b-0 changable-data-group" dataGroupName="Cost Of Sale">
            <h6 class="slim-card-title"> Cost of Sale (MYR)</h6>
            <h1 class="">{{number_format(round($cos->ytd_revenue, 0))}}</h1>
            <!-- <p class="tx-16">YTD (MYR'000)</p> -->
            <p class="tx-14 mb-0"><span class="target">Target:</span> MYR {{number_format(round($cos->ytd_target, 0))}}</p>
            <!--p class="tx-14 mb-0"><span class="target">Forecast:</span> MYR XXX,XXX</p-->
            <p class="tx-14"><span class="target">Variance:</span> MYR {{number_format(round($cos->ytd_variance, 0))}}</p>
            </div><!-- card-body -->
            <div id="COSOverviewGraph" class="ht-50 ht-sm-70 mg-r--1"></div>
            <div class="card card-sub-data ">
                <div class="card-body">
                    <h6 class="card-sub-data-title tx-center mg-b-20">Breakdown (%)</h6>                  
                    <canvas id="Cost_Of_Sale_Current_Month_Breakdown"></canvas>
                    <script>
                            var costOfSaleCurrentMonthBreakdownData = {
                            datasets: [{
                            data: <?php echo json_encode($cos_breakdown_data); ?>,
                            backgroundColor: preChosenBackgroundColors
                            }],
                            labels: <?php echo json_encode($cos_breakdown_labels); ?>,
                            elementId: ["Cost_Of_Sale_Current_Month_Breakdown"],
                            chartType: ['pie'],
                            legend: false, 
                            breakdown: true
                        };
                        pieChartBreakdownData.push(costOfSaleCurrentMonthBreakdownData);                        
                    </script>
                </div>
            </div>
        </div><!-- card -->
        </div>
        <div class="col-md-2">
        <div id="PrimaryGPLCard" class="card">
            <div id="PrimaryGrossProfitLoss" class="card-body pd-b-0 changable-data-group" dataGroupName="Gross Profit / Loss">
            <h6 class="slim-card-title"> Gross Profit / Loss (MYR)</h6>
            <h1 class="">{{number_format(round($gpl->ytd_revenue, 0))}}</h1>
            <!-- <p class="tx-16">YTD (MYR'000)</p> -->
            <p class="tx-14 mb-0"><span class="target">Target:</span> MYR {{number_format(round($gpl->ytd_target, 0))}}</p>
            <!--p class="tx-14 mb-0"><span class="target">Forecast:</span> MYR XXX,XXX</p-->
            <p class="tx-14"><span class="target">Variance:</span> MYR {{number_format(round($gpl->ytd_variance, 0))}}</p>
            </div><!-- card-body -->
            <div id="GPLOverviewGraph" class="ht-50 ht-sm-70 mg-r--1"></div>
            <div class="card card-sub-data ">
                <div class="card-body">
                    <h6 class="card-sub-data-title tx-center mg-b-20">Breakdown (%)</h6>                  
                    <canvas id="Gross_Profit_Loss_Current_Month_Breakdown"></canvas>
                    <script>
                        var grossProfitLossCurrentMonthBreakdownData = {
                            datasets: [{
                            data: <?php echo json_encode($gpl_breakdown_data); ?>,
                            backgroundColor: preChosenBackgroundColors
                            }],
                            labels: <?php echo json_encode($gpl_breakdown_labels); ?>,
                            elementId: ["Gross_Profit_Loss_Current_Month_Breakdown"],
                            chartType: ['pie'],
                            legend: false, 
                            breakdown: true
                        };
                        pieChartBreakdownData.push(grossProfitLossCurrentMonthBreakdownData);
                    </script>
                </div>
            </div>
        </div><!-- card -->
        </div>
        <div class="col-md-2">
        <div id="PrimaryOPEXCard" class="card">
            <div id="PrimaryOperatingExpense" class="card-body pd-b-0 changable-data-group" dataGroupName="Operating Expense">
            <h6 class="slim-card-title"> Operating Expense (MYR)</h6>
            <h1 class="">{{number_format(round($opex->ytd_revenue, 0))}}</h1>
            <!-- <p class="tx-16">YTD (MYR'000)</p> -->
            <p class="tx-14 mb-0"><span class="target">Target:</span> MYR {{number_format(round($opex->ytd_target, 0))}}</p>
            <!--p class="tx-14 mb-0"><span class="target">Forecast:</span> MYR XXX,XXX</p-->
            <p class="tx-14"><span class="target">Variance:</span> MYR {{number_format(round($opex->ytd_variance, 0))}}</p>
            </div><!-- card-body -->
            <div id="OPEXOverviewGraph" class="ht-50 ht-sm-70 mg-r--1"></div>
            <div class="card card-sub-data ">
                <div class="card-body">
                    <h6 class="card-sub-data-title tx-center mg-b-20">Breakdown (%)</h6>                  
                    <canvas id="Operating_Expense_Current_Month_Breakdown"></canvas>
                    <script>
                            var operatingExpenseCurrentMonthBreakdownData = {
                                datasets: [{
                                data: <?php echo json_encode($opex_breakdown_data); ?>,
                                backgroundColor: preChosenBackgroundColors
                                }],
                                labels: <?php echo json_encode($opex_breakdown_labels); ?>,
                                elementId: ["Operating_Expense_Current_Month_Breakdown"],
                                chartType: ['pie'],
                                legend: false, 
                                breakdown: true
                            };

                            pieChartBreakdownData.push(operatingExpenseCurrentMonthBreakdownData);
                    </script>
                </div>
            </div>
        </div><!-- card -->
        </div>
        
        
        <div class="col-md-2">
        <div id="PrimaryEBITDACard" class="card">
            <div id="PrimaryEBITDA" class="card-body pd-b-0 changable-data-group" dataGroupName="EBITDA">
            <h6 class="slim-card-title"> EBITDA (MYR)</h6>
            <h1 class="">{{number_format(round($ebitda->ytd_revenue, 0))}}</h1>
            <p class="tx-14 mb-0"><span class="target">Target:</span> MYR {{number_format(round($ebitda->ytd_target, 0))}}</p>
            <p class="tx-14"><span class="target">Variance:</span> MYR {{number_format(round($ebitda->ytd_variance, 0))}}</p>
            </div><!-- card-body -->
            <div id="EBITDAOverviewGraph" class="ht-50 ht-sm-70 mg-r--1"></div>
            <div class="card card-sub-data ">
                <div class="card-body">
                    <h6 class="card-sub-data-title mg-b-20" style='opacity: 0'>Breakdown (%)</h6>
                    <canvas id="EBITDA_Current_Month_Breakdown"></canvas>
                    <script>
                            var ebitdaCurrentMonthBreakdownData = {
                                datasets: [{
                                data: [100],
                                backgroundColor: preChosenBackgroundColors
                                }],
                                labels: ["EBITDA(NO BREAKDOWN DATA)"],
                                elementId: ["EBITDA_Current_Month_Breakdown"],
                                chartType: ['pie'],
                                legend: false, 
                                breakdown: true
                            };
                            pieChartBreakdownData.push(ebitdaCurrentMonthBreakdownData);
                    </script>
                </div>
            </div>
        </div><!-- card -->
        </div>

        <div class="col-md-2">
        <div id="PrimaryPATCard" class="card">
            <div id="PrimaryPAT" class="card-body pd-b-0 changable-data-group" dataGroupName="PAT">
            <h6 class="slim-card-title"> PAT (MYR)</h6>
            <h1 class="">{{number_format(round($pat->ytd_revenue, 0))}}</h1>
            <!-- <p class="tx-16">YTD (MYR'000)</p> -->
            <p class="tx-14 mb-0"><span class="target">Target:</span> MYR {{number_format(round($pat->ytd_target, 0))}}</p>
            <!--p class="tx-14 mb-0"><span class="target">Forecast:</span> MYR XXX,XXX</p-->
            <p class="tx-14"><span class="target">Variance:</span> MYR {{number_format(round($pat->ytd_variance, 0))}}</p>
            </div><!-- card-body -->
            <div id="PATOverviewGraph" class="ht-50 ht-sm-70 mg-r--1"></div>
            <div class="card card-sub-data ">
                <div class="card-body">
                    <h6 class="card-sub-data-title tx-center mg-b-20">Breakdown (%)</h6>                  
                    <canvas id="PAT_Current_Month_Breakdown" class='p-2'></canvas>
                    <script>
                          var patCurrentMonthBreakdownData = {
                                datasets: [{
                                data: <?php echo json_encode($pat_breakdown_data); ?>,
                                backgroundColor: preChosenBackgroundColors
                                }],
                                labels: <?php echo json_encode($pat_breakdown_labels); ?>,
                                elementId: ["PAT_Current_Month_Breakdown"],
                                chartType: ['pie'],
                                legend: false, 
                                breakdown: true
                            };
                            pieChartBreakdownData.push(patCurrentMonthBreakdownData);
                    </script>
                </div>
            </div>
        </div><!-- card -->
        </div>
    </div>

    <div style="display:">
    <div class="slim-pageheader">
        <div></div>
        <h6 id="DetailedData" class="slim-pagetitle">Detailed Data - <span id="DetailedDataLabel">Revenue</span></h6>          
    </div>


    <div class="row row-xs">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body pd-b-0">
                <h6 class="slim-card-title">Monthly Actual / Target </h6>
                <canvas id="MonthlyActualTargetChart" class='p-4 detailedDataChart'></canvas>
                <script>
                      var revenueMonthlyActualTargetData = {
                            datasets: [
                            {data: <?php echo json_encode($revenue_detailed_monthly_data_actual); ?>,borderColor: actualColour,label: actualTextLabel},
                            {data: <?php echo json_encode($revenue_detailed_monthly_data_target); ?>,borderColor: targetColour,label:targetTextLabel}
                            ],
                            labels:  <?php echo json_encode($revenue_detailed_monthly_labels); ?>,
                            elementId: ["MonthlyActualTargetChart"],
                            chartType: ['line'],
                            legend: true 
                        };

                        revenueDetailedData.push(revenueMonthlyActualTargetData);
                </script>
            </div>
        </div>              
        </div>
        <div class="col-md-6">
            <div class="card">
            <div class="card-body pd-b-0">
                <h6 class="slim-card-title">Cumulative Actual / Target</h6>
                <canvas id="CumulativeActualTargetChart" class='p-4 detailedDataChart'></canvas>
                <script>
                     var revenueCumulativeActualTargetData = {
                        datasets: [
                        {data: <?php echo json_encode($revenue_detailed_cumulative_data_actual) ?>,borderColor: actualColour,label:actualTextLabel},
                        {data: <?php echo json_encode($revenue_detailed_cumulative_data_target) ?>,borderColor: targetColour,label:targetTextLabel}
                        ],
                        labels: <?php echo json_encode($revenue_detailed_cumulative_data_labels) ?>,
                        elementId: ["CumulativeActualTargetChart"],
                        chartType: ['line'],
                        legend: true 
                    };
                    
                    revenueDetailedData.push(revenueCumulativeActualTargetData);
                </script>
            </div>
            </div>              
        </div>
    </div>

    <div class="row row-xs mt-4">
        <div class="col-md-6">
        <div class="card">
            <div class="card-body pd-b-0">
            <h6 class="slim-card-title">quarterly Actual / Target </h6>
            <canvas id="quarterlyActualTargetChart" class='p-4 detailedDataChart'></canvas>
            <script>
                  var revenuequarterlyActualTargetData = {
                    datasets: [
                    {data: <?php echo json_encode($revenue_detailed_quarterly_data_actual) ?>,borderColor: actualColour,label:actualTextLabel},
                    {data: <?php echo json_encode($revenue_detailed_quarterly_data_target) ?>,borderColor: targetColour,label:targetTextLabel}
                    ],
                    labels: <?php echo json_encode($revenue_detailed_quarterly_data_labels) ?>,
                    elementId: ["quarterlyActualTargetChart"],
                    chartType: ['line'],
                    legend: true 
                };
                revenueDetailedData.push(revenuequarterlyActualTargetData);
            </script>
            </div>
        </div>              
        </div>
        <div class="col-md-6">
            <div class="card">
            <div class="card-body pd-b-0">
                <h6 class="slim-card-title">Monthly Variance</h6>
                <canvas id="MonthlyVarianceChart" class='p-4 detailedDataChart'></canvas>
                <script>
                    var revenueMonthlyVarianceData = {
                        datasets: [
                        {data: <?php echo json_encode($revenue_detailed_monthly_variance_data_actual) ?>,borderColor: actualColour,label:actualTextLabel},
                        ],
                        labels: <?php echo json_encode($revenue_detailed_monthly_variance_data_labels) ?>,
                        elementId: ["MonthlyVarianceChart"],
                        chartType: ['line'],
                        legend: true 
                    };
                    
                    revenueDetailedData.push(revenueMonthlyVarianceData);
                </script>
            </div>
            </div>              
        </div>
    </div>


    <div class="row row-xs mt-4">
        <div class="col-md-6">
            <div class="card">
            <div class="card-body pd-b-0">
                <h6 class="slim-card-title">CUMULATIVE VARIANCES </h6>
                <canvas id="CumativelyVariancesChart" class='p-4 detailedDataChart'></canvas>
                <script>
                    var revenueCumativelyVariancesData = {
                        datasets: [
                            {data: <?php echo json_encode($revenue_detailed_cumatively_variance_data_actual) ?>,borderColor: actualColour,label:actualTextLabel},
                        ],
                        labels: <?php echo json_encode($revenue_detailed_cumatively_variance_data_labels) ?>,
                        elementId: ["CumativelyVariancesChart"],
                        chartType: ['line'],
                        legend: true 
                        };
                    
                    revenueDetailedData.push(revenueCumativelyVariancesData);
                </script>
            </div>
            </div>              
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body pd-b-0">
                <h6 class="slim-card-title">quarterly Variances</h6>
                <canvas id="quarterlyVariancesChart" class='p-4 detailedDataChart'></canvas>
                <script>
                    var revenuequarterlyVariancesData = {
                        datasets: [
                            {data: <?php echo json_encode($revenue_detailed_quarterly_variance_data_actual) ?>,borderColor: actualColour,label:actualTextLabel},
                        ],
                        labels: <?php echo json_encode($revenue_detailed_quarterly_variance_data_labels) ?>,
                        elementId: ["quarterlyVariancesChart"],
                        chartType: ['line'],
                        legend: true 
                        };
                    
                    revenueDetailedData.push(revenuequarterlyVariancesData);
                </script>
                </div>
            </div>              
            </div>
        </div>
        </div>



    </div><!-- container -->
</div><!-- slim-mainpanel -->

<script>
// START DETAILED COST OF SALE DATA
var costOfSaleDetailedData = [];
  // START DETAILED COST OF SALE MONTHLY ACTUAL / TARGET DATA
  var costOfSaleMonthlyActualTargetData = {
    datasets: [
      {data: <?php echo json_encode($cos_detailed_monthly_data_actual); ?>,borderColor: actualColour,label: actualTextLabel},
      {data: <?php echo json_encode($cos_detailed_monthly_data_target); ?>,borderColor: targetColour,label:targetTextLabel}
    ],
    labels: <?php echo json_encode($cos_detailed_monthly_labels); ?>,
    elementId: ["MonthlyActualTargetChart"],
    chartType: ['line'],
    legend: true 
  };

  costOfSaleDetailedData.push(costOfSaleMonthlyActualTargetData);
  
  // END DETAILED COST OF SALE MONTHLY ACTUAL / TARGET DATA

  // START DETAILED COST OF SALE CUMULATIVE ACTUAL / TARGET DATA
  var costOfSaleCumulativeActualTargetData = {
    datasets: [
      {data: <?php echo json_encode($cos_detailed_cumulative_data_actual); ?>,borderColor: actualColour,label:actualTextLabel},
      {data: <?php echo json_encode($cos_detailed_cumulative_data_target); ?>,borderColor: targetColour,label:targetTextLabel}
    ],
    labels: <?php echo json_encode($cos_detailed_cumulative_data_labels); ?>,
    elementId: ["CumulativeActualTargetChart"],
    chartType: ['line'],
    legend: true 
  };
  
  costOfSaleDetailedData.push(costOfSaleCumulativeActualTargetData);
  // END DETAILED COST OF SALE CUMULATIVE ACTUAL / TARGET DATA

  // START DETAILED COST OF SALE quarterly ACTUAL / TARGET DATA
  var costOfSalequarterlyActualTargetData = {
    datasets: [
      {data: <?php echo json_encode($cos_detailed_quarterly_data_actual); ?>,borderColor: actualColour,label:actualTextLabel},
      {data: <?php echo json_encode($cos_detailed_quarterly_data_target); ?>,borderColor: targetColour,label:targetTextLabel}
    ],
    labels: <?php echo json_encode($cos_detailed_quarterly_data_labels); ?>,
    elementId: ["quarterlyActualTargetChart"],
    chartType: ['line'],
    legend: true 
  };
  
  costOfSaleDetailedData.push(costOfSalequarterlyActualTargetData);
  // END DETAILED COST OF SALE quarterly ACTUAL / TARGET DATA   

  // START DETAILED COST OF SALE MONTHLY VARIANCE DATA
  var costOfSaleMonthlyVarianceData = {
    datasets: [
      {data: <?php echo json_encode($cos_detailed_monthly_variance_data_actual); ?>,borderColor: actualColour,label:actualTextLabel},
    ],
    labels:  <?php echo json_encode($cos_detailed_monthly_variance_data_labels); ?>,
    elementId: ["MonthlyVarianceChart"],
    chartType: ['line'],
    legend: true 
  };
  
  costOfSaleDetailedData.push(costOfSaleMonthlyVarianceData);
  // END DETAILED COST OF SALE MONTHLY VARIANCE DATA  

  // START DETAILED COST OF SALE CUMULATIVE VARIANCES DATA
  var costOfSaleCumativelyVariancesData = {
      datasets: [
        {data: <?php echo json_encode($cos_detailed_cumatively_variance_data_actual); ?>,borderColor: actualColour,label:actualTextLabel},
      ],
      labels: <?php echo json_encode($cos_detailed_cumatively_variance_data_labels); ?>,
      elementId: ["CumativelyVariancesChart"],
      chartType: ['line'],
      legend: true 
    };
  
  costOfSaleDetailedData.push(costOfSaleCumativelyVariancesData);
  // END DETAILED COST OF SALE CUMULATIVE VARIANCES DATA  

  // START DETAILED COST OF SALE quarterly VARIANCES DATA
  var costOfSalequarterlyVariancesData = {
      datasets: [
        {data: <?php echo json_encode($cos_detailed_quarterly_variance_data_actual); ?>,borderColor: actualColour,label:actualTextLabel},
      ],
      labels: <?php echo json_encode($cos_detailed_quarterly_variance_data_labels); ?>,
      elementId: ["quarterlyVariancesChart"],
      chartType: ['line'],
      legend: true 
    };
  
  costOfSaleDetailedData.push(costOfSalequarterlyVariancesData);
  // END DETAILED COST OF SALE quarterly VARIANCES DATA



  // START DETAILED GPL DATA
var gplDetailedData = [];
  // START DETAILED GPL MONTHLY ACTUAL / TARGET DATA
  var gplMonthlyActualTargetData = {
    datasets: [
      {data: <?php echo json_encode($gpl_detailed_monthly_data_actual); ?>,borderColor: actualColour,label: actualTextLabel},
      {data: <?php echo json_encode($gpl_detailed_monthly_data_target); ?>,borderColor: targetColour,label:targetTextLabel}
    ],
    labels: <?php echo json_encode($gpl_detailed_monthly_labels); ?>,
    elementId: ["MonthlyActualTargetChart"],
    chartType: ['line'],
    legend: true 
  };

  gplDetailedData.push(gplMonthlyActualTargetData);
  
  // END DETAILED GPL MONTHLY ACTUAL / TARGET DATA

  // START DETAILED GPL CUMULATIVE ACTUAL / TARGET DATA
  var gplCumulativeActualTargetData = {
    datasets: [
      {data: <?php echo json_encode($gpl_detailed_cumulative_data_actual); ?>,borderColor: actualColour,label:actualTextLabel},
      {data: <?php echo json_encode($gpl_detailed_cumulative_data_target); ?>,borderColor: targetColour,label:targetTextLabel}
    ],
    labels: <?php echo json_encode($gpl_detailed_cumulative_data_labels); ?>,
    elementId: ["CumulativeActualTargetChart"],
    chartType: ['line'],
    legend: true 
  };
  
  gplDetailedData.push(gplCumulativeActualTargetData);
  // END DETAILED GPL CUMULATIVE ACTUAL / TARGET DATA

  // START DETAILED GPL quarterly ACTUAL / TARGET DATA
  var gplquarterlyActualTargetData = {
    datasets: [
      {data: <?php echo json_encode($gpl_detailed_quarterly_data_actual); ?>,borderColor: actualColour,label:actualTextLabel},
      {data: <?php echo json_encode($gpl_detailed_quarterly_data_target); ?>,borderColor: targetColour,label:targetTextLabel}
    ],
    labels: <?php echo json_encode($gpl_detailed_quarterly_data_labels); ?>,
    elementId: ["quarterlyActualTargetChart"],
    chartType: ['line'],
    legend: true 
  };
  
  gplDetailedData.push(gplquarterlyActualTargetData);
  // END DETAILED GPL quarterly ACTUAL / TARGET DATA   

  // START DETAILED GPL MONTHLY VARIANCE DATA
  var gplMonthlyVarianceData = {
    datasets: [
      {data: <?php echo json_encode($gpl_detailed_monthly_variance_data_actual); ?>,borderColor: actualColour,label:actualTextLabel},
    ],
    labels:  <?php echo json_encode($gpl_detailed_monthly_variance_data_labels); ?>,
    elementId: ["MonthlyVarianceChart"],
    chartType: ['line'],
    legend: true 
  };
  
  gplDetailedData.push(gplMonthlyVarianceData);
  // END DETAILED GPL MONTHLY VARIANCE DATA  

  // START DETAILED GPL CUMULATIVE VARIANCES DATA
  var gplCumativelyVariancesData = {
      datasets: [
        {data: <?php echo json_encode($gpl_detailed_cumatively_variance_data_actual); ?>,borderColor: actualColour,label:actualTextLabel},
      ],
      labels: <?php echo json_encode($gpl_detailed_cumatively_variance_data_labels); ?>,
      elementId: ["CumativelyVariancesChart"],
      chartType: ['line'],
      legend: true 
    };
  
  gplDetailedData.push(gplCumativelyVariancesData);
  // END DETAILED GPL CUMULATIVE VARIANCES DATA  

  // START DETAILED GPL quarterly VARIANCES DATA
  var gplquarterlyVariancesData = {
      datasets: [
        {data: <?php echo json_encode($gpl_detailed_quarterly_variance_data_actual); ?>,borderColor: actualColour,label:actualTextLabel},
      ],
      labels: <?php echo json_encode($gpl_detailed_quarterly_variance_data_labels); ?>,
      elementId: ["quarterlyVariancesChart"],
      chartType: ['line'],
      legend: true 
    };
  
  gplDetailedData.push(gplquarterlyVariancesData);
  // END DETAILED GPL quarterly VARIANCES DATA





    // START DETAILED OPEX DATA
var opexDetailedData = [];
  // START DETAILED OPEX MONTHLY ACTUAL / TARGET DATA
  var opexMonthlyActualTargetData = {
    datasets: [
      {data: <?php echo json_encode($opex_detailed_monthly_data_actual); ?>,borderColor: actualColour,label: actualTextLabel},
      {data: <?php echo json_encode($opex_detailed_monthly_data_target); ?>,borderColor: targetColour,label:targetTextLabel}
    ],
    labels: <?php echo json_encode($opex_detailed_monthly_labels); ?>,
    elementId: ["MonthlyActualTargetChart"],
    chartType: ['line'],
    legend: true 
  };

  opexDetailedData.push(opexMonthlyActualTargetData);
  
  // END DETAILED OPEX MONTHLY ACTUAL / TARGET DATA

  // START DETAILED OPEX CUMULATIVE ACTUAL / TARGET DATA
  var opexCumulativeActualTargetData = {
    datasets: [
      {data: <?php echo json_encode($opex_detailed_cumulative_data_actual); ?>,borderColor: actualColour,label:actualTextLabel},
      {data: <?php echo json_encode($opex_detailed_cumulative_data_target); ?>,borderColor: targetColour,label:targetTextLabel}
    ],
    labels: <?php echo json_encode($opex_detailed_cumulative_data_labels); ?>,
    elementId: ["CumulativeActualTargetChart"],
    chartType: ['line'],
    legend: true 
  };
  
  opexDetailedData.push(opexCumulativeActualTargetData);
  // END DETAILED OPEX CUMULATIVE ACTUAL / TARGET DATA

  // START DETAILED OPEX quarterly ACTUAL / TARGET DATA
  var opexquarterlyActualTargetData = {
    datasets: [
      {data: <?php echo json_encode($opex_detailed_quarterly_data_actual); ?>,borderColor: actualColour,label:actualTextLabel},
      {data: <?php echo json_encode($opex_detailed_quarterly_data_target); ?>,borderColor: targetColour,label:targetTextLabel}
    ],
    labels: <?php echo json_encode($opex_detailed_quarterly_data_labels); ?>,
    elementId: ["quarterlyActualTargetChart"],
    chartType: ['line'],
    legend: true 
  };
  
  opexDetailedData.push(opexquarterlyActualTargetData);
  // END DETAILED OPEX quarterly ACTUAL / TARGET DATA   

  // START DETAILED OPEX MONTHLY VARIANCE DATA
  var opexMonthlyVarianceData = {
    datasets: [
      {data: <?php echo json_encode($opex_detailed_monthly_variance_data_actual); ?>,borderColor: actualColour,label:actualTextLabel},
    ],
    labels:  <?php echo json_encode($opex_detailed_monthly_variance_data_labels); ?>,
    elementId: ["MonthlyVarianceChart"],
    chartType: ['line'],
    legend: true 
  };
  
  opexDetailedData.push(opexMonthlyVarianceData);
  // END DETAILED OPEX MONTHLY VARIANCE DATA  

  // START DETAILED OPEX CUMULATIVE VARIANCES DATA
  var opexCumativelyVariancesData = {
      datasets: [
        {data: <?php echo json_encode($opex_detailed_cumatively_variance_data_actual); ?>,borderColor: actualColour,label:actualTextLabel},
      ],
      labels: <?php echo json_encode($opex_detailed_cumatively_variance_data_labels); ?>,
      elementId: ["CumativelyVariancesChart"],
      chartType: ['line'],
      legend: true 
    };
  
  opexDetailedData.push(opexCumativelyVariancesData);
  // END DETAILED OPEX CUMULATIVE VARIANCES DATA  

  // START DETAILED OPEX quarterly VARIANCES DATA
  var opexquarterlyVariancesData = {
      datasets: [
        {data: <?php echo json_encode($opex_detailed_quarterly_variance_data_actual); ?>,borderColor: actualColour,label:actualTextLabel},
      ],
      labels: <?php echo json_encode($opex_detailed_quarterly_variance_data_labels); ?>,
      elementId: ["quarterlyVariancesChart"],
      chartType: ['line'],
      legend: true 
    };
  
  opexDetailedData.push(opexquarterlyVariancesData);
  // END DETAILED OPEX quarterly VARIANCES DATA


  
    // START DETAILED PAT DATA
var patDetailedData = [];
  // START DETAILED PAT MONTHLY ACTUAL / TARGET DATA
  var patMonthlyActualTargetData = {
    datasets: [
      {data: <?php echo json_encode($pat_detailed_monthly_data_actual); ?>,borderColor: actualColour,label: actualTextLabel},
      {data: <?php echo json_encode($pat_detailed_monthly_data_target); ?>,borderColor: targetColour,label:targetTextLabel}
    ],
    labels: <?php echo json_encode($pat_detailed_monthly_labels); ?>,
    elementId: ["MonthlyActualTargetChart"],
    chartType: ['line'],
    legend: true 
  };

  patDetailedData.push(patMonthlyActualTargetData);
  
  // END DETAILED PAT MONTHLY ACTUAL / TARGET DATA

  // START DETAILED PAT CUMULATIVE ACTUAL / TARGET DATA
  var patCumulativeActualTargetData = {
    datasets: [
      {data: <?php echo json_encode($pat_detailed_cumulative_data_actual); ?>,borderColor: actualColour,label:actualTextLabel},
      {data: <?php echo json_encode($pat_detailed_cumulative_data_target); ?>,borderColor: targetColour,label:targetTextLabel}
    ],
    labels: <?php echo json_encode($pat_detailed_cumulative_data_labels); ?>,
    elementId: ["CumulativeActualTargetChart"],
    chartType: ['line'],
    legend: true 
  };
  
  patDetailedData.push(patCumulativeActualTargetData);
  // END DETAILED PAT CUMULATIVE ACTUAL / TARGET DATA

  // START DETAILED PAT quarterly ACTUAL / TARGET DATA
  var patquarterlyActualTargetData = {
    datasets: [
      {data: <?php echo json_encode($pat_detailed_quarterly_data_actual); ?>,borderColor: actualColour,label:actualTextLabel},
      {data: <?php echo json_encode($pat_detailed_quarterly_data_target); ?>,borderColor: targetColour,label:targetTextLabel}
    ],
    labels: <?php echo json_encode($pat_detailed_quarterly_data_labels); ?>,
    elementId: ["quarterlyActualTargetChart"],
    chartType: ['line'],
    legend: true 
  };
  
  patDetailedData.push(patquarterlyActualTargetData);
  // END DETAILED PAT quarterly ACTUAL / TARGET DATA   

  // START DETAILED PAT MONTHLY VARIANCE DATA
  var patMonthlyVarianceData = {
    datasets: [
      {data: <?php echo json_encode($pat_detailed_monthly_variance_data_actual); ?>,borderColor: actualColour,label:actualTextLabel},
    ],
    labels:  <?php echo json_encode($pat_detailed_monthly_variance_data_labels); ?>,
    elementId: ["MonthlyVarianceChart"],
    chartType: ['line'],
    legend: true 
  };
  
  patDetailedData.push(patMonthlyVarianceData);
  // END DETAILED PAT MONTHLY VARIANCE DATA  

  // START DETAILED PAT CUMULATIVE VARIANCES DATA
  var patCumativelyVariancesData = {
      datasets: [
        {data: <?php echo json_encode($pat_detailed_cumatively_variance_data_actual); ?>,borderColor: actualColour,label:actualTextLabel},
      ],
      labels: <?php echo json_encode($pat_detailed_cumatively_variance_data_labels); ?>,
      elementId: ["CumativelyVariancesChart"],
      chartType: ['line'],
      legend: true 
    };
  
  patDetailedData.push(patCumativelyVariancesData);
  // END DETAILED PAT CUMULATIVE VARIANCES DATA  

  // START DETAILED PAT quarterly VARIANCES DATA
  var patquarterlyVariancesData = {
      datasets: [
        {data: <?php echo json_encode($pat_detailed_quarterly_variance_data_actual); ?>,borderColor: actualColour,label:actualTextLabel},
      ],
      labels: <?php echo json_encode($pat_detailed_quarterly_variance_data_labels); ?>,
      elementId: ["quarterlyVariancesChart"],
      chartType: ['line'],
      legend: true 
    };
  
  patDetailedData.push(patquarterlyVariancesData);
  // END DETAILED PAT quarterly VARIANCES DATA
</script>
<script type="module" defer>

  //STAR BREAKDOWN DETAILED DATA
<?php foreach($detailed_breakdown_collection as $breakdown_name => $breakdown){   ?>

var breakdownDetailedData = [];
  // START BREAKDOWN DETAILED MONTHLY ACTUAL / TARGET DATA
  var breakdownMonthlyActualTargetData = {
    datasets: [
      {data: <?php echo json_encode($breakdown["Monthly Actual / Target"]["Actual"]); ?>,borderColor: actualColour,label: actualTextLabel},
      {data: <?php echo json_encode($breakdown["Monthly Actual / Target"]["Target"]); ?>,borderColor: targetColour,label:targetTextLabel}
    ],
    labels: <?php echo json_encode($breakdown["Monthly Actual / Target"]["Label"]); ?>,
    elementId: ["MonthlyActualTargetChart"],
    chartType: ['line'],
    legend: true 
  };

  breakdownDetailedData.push(breakdownMonthlyActualTargetData);
  
  // END BREAKDOWN DETAILED MONTHLY ACTUAL / TARGET DATA

  // START BREAKDOWN DETAILED CUMULATIVE ACTUAL / TARGET DATA
  var breakdownCumulativeActualTargetData = {
    datasets: [
      {data: <?php echo json_encode($breakdown["Cumulative Actual / Target"]["Actual"]); ?>,borderColor: actualColour,label:actualTextLabel},
      {data: <?php echo json_encode($breakdown["Cumulative Actual / Target"]["Target"]); ?>,borderColor: targetColour,label:targetTextLabel}
    ],
    labels: <?php echo json_encode($breakdown["Cumulative Actual / Target"]["Label"]); ?>,
    elementId: ["CumulativeActualTargetChart"],
    chartType: ['line'],
    legend: true 
  };
  
  breakdownDetailedData.push(breakdownCumulativeActualTargetData);
  // END BREAKDOWN DETAILED CUMULATIVE ACTUAL / TARGET DATA

  // START BREAKDOWN DETAILED quarterly ACTUAL / TARGET DATA
  var breakdownquarterlyActualTargetData = {
    datasets: [
      {data: <?php echo json_encode($breakdown["quarterly Actual / Target"]["Actual"]); ?>,borderColor: actualColour,label:actualTextLabel},
      {data: <?php echo json_encode($breakdown["quarterly Actual / Target"]["Target"]); ?>,borderColor: targetColour,label:targetTextLabel}
    ],
    labels: <?php echo json_encode($breakdown["quarterly Actual / Target"]["Label"]); ?>,
    elementId: ["quarterlyActualTargetChart"],
    chartType: ['line'],
    legend: true 
  };
  
  breakdownDetailedData.push(breakdownquarterlyActualTargetData);
  // END BREAKDOWN DETAILED quarterly ACTUAL / TARGET DATA   

  // START BREAKDOWN DETAILED MONTHLY VARIANCE DATA
  var breakdownMonthlyVarianceData = {
    datasets: [
      {data: <?php echo json_encode($breakdown["Monthly Variance"]["Actual"]); ?>,borderColor: actualColour,label:actualTextLabel},
    ],
    labels:  <?php echo json_encode($breakdown["Monthly Variance"]["Label"]); ?>,
    elementId: ["MonthlyVarianceChart"],
    chartType: ['line'],
    legend: true 
  };
  
  breakdownDetailedData.push(breakdownMonthlyVarianceData);
  // END BREAKDOWN DETAILED MONTHLY VARIANCE DATA  

  // START BREAKDOWN DETAILED CUMULATIVE VARIANCES DATA
  var breakdownCumativelyVariancesData = {
      datasets: [
        {data: <?php echo json_encode($breakdown["Cumatively Variance"]["Actual"]); ?>,borderColor: actualColour,label:actualTextLabel},
      ],
      labels: <?php echo json_encode($breakdown["Cumatively Variance"]["Label"]); ?>,
      elementId: ["CumativelyVariancesChart"],
      chartType: ['line'],
      legend: true 
    };
  
  breakdownDetailedData.push(breakdownCumativelyVariancesData);
  // END BREAKDOWN DETAILED CUMULATIVE VARIANCES DATA  

  // START BREAKDOWN DETAILED quarterly VARIANCES DATA
  var breakdownquarterlyVariancesData = {
      datasets: [
        {data: <?php echo json_encode($breakdown["quarterly Variance"]["Actual"]); ?>,borderColor: actualColour,label:actualTextLabel},
      ],
      labels: <?php echo json_encode($breakdown["quarterly Variance"]["Label"]); ?>,
      elementId: ["quarterlyVariancesChart"],
      chartType: ['line'],
      legend: true 
    };
  
  breakdownDetailedData.push(breakdownquarterlyVariancesData);
  // END BREAKDOWN DETAILED quarterly VARIANCES DATA
  pushToDetailedCollectionDataset("{{$breakdown_name}}",breakdownDetailedData);
<?php }; ?>



</script>




<script type="module" defer>
  //START OVERVIEW GRAPHS

  $(function(){
    'use strict'

    var revenueOverviewGraph = new Rickshaw.Graph({
      element: document.querySelector('#RevenueOverviewGraph'),
      renderer: 'line',
      series: [{
        data: [
            <?php for($i = 0; $i < count($revenue_overview_graph); $i++){ 
            if($revenue_overview_graph[$i]->dashboard_amount != null){    ?>
            { x: <?php echo $i ?>, y: <?php echo $revenue_overview_graph[$i]->dashboard_amount ?> },
            <?php } } ?>
        ],
        color: '#ffffff',
        name: 'Revenue',
      }]
    });
    
    revenueOverviewGraph.render();

    // Responsive Mode
    new ResizeSensor($('.slim-mainpanel'), function(){
      revenueOverviewGraph.configure({
        width: $('#RevenueOverviewGraph').width(),
        height: $('#RevenueOverviewGraph').height()
      });
      revenueOverviewGraph.render();
    });

    var cosOverviewGraph = new Rickshaw.Graph({
      element: document.querySelector('#COSOverviewGraph'),
      renderer: 'line',
      series: [{
        data: [
            <?php for($i = 0; $i < count($cos_overview_graph); $i++){ 
            if($cos_overview_graph[$i]->dashboard_amount != null){    ?>
            { x: <?php echo $i ?>, y: <?php echo $cos_overview_graph[$i]->dashboard_amount ?> },
            <?php } } ?>
        ],
        color: '#ffffff',
        name: 'Revenue',
      }]
    });
    
    cosOverviewGraph.render();

    // Responsive Mode
    new ResizeSensor($('.slim-mainpanel'), function(){
      cosOverviewGraph.configure({
        width: $('#COSOverviewGraph').width(),
        height: $('#COSOverviewGraph').height()
      });
      cosOverviewGraph.render();
    });


    var gplOverviewGraph = new Rickshaw.Graph({
      element: document.querySelector('#GPLOverviewGraph'),
      renderer: 'line',
      series: [{
        data: [
            <?php for($i = 0; $i < count($gpl_overview_graph); $i++){ 
            if($gpl_overview_graph[$i]->dashboard_amount != null){    ?>
            { x: <?php echo $i ?>, y: <?php echo $gpl_overview_graph[$i]->dashboard_amount ?> },
            <?php } } ?>
        ],
        color: '#ffffff',
        name: 'Revenue',
      }]
    });
    
    gplOverviewGraph.render();

    // Responsive Mode
    new ResizeSensor($('.slim-mainpanel'), function(){
      gplOverviewGraph.configure({
        width: $('#GPLOverviewGraph').width(),
        height: $('#GPLOverviewGraph').height()
      });
      gplOverviewGraph.render();
    });

    var opexOverviewGraph = new Rickshaw.Graph({
      element: document.querySelector('#OPEXOverviewGraph'),
      renderer: 'line',
      series: [{
        data: [
            <?php for($i = 0; $i < count($opex_overview_graph); $i++){ 
            if($opex_overview_graph[$i]->dashboard_amount != null){    ?>
            { x: <?php echo $i ?>, y: <?php echo $opex_overview_graph[$i]->dashboard_amount ?> },
            <?php } } ?>
        ],
        color: '#ffffff',
        name: 'Revenue',
      }]
    });
    
    opexOverviewGraph.render();

    // Responsive Mode
    new ResizeSensor($('.slim-mainpanel'), function(){
      opexOverviewGraph.configure({
        width: $('#OPEXOverviewGraph').width(),
        height: $('#OPEXOverviewGraph').height()
      });
      opexOverviewGraph.render();
    });

    var patOverviewGraph = new Rickshaw.Graph({
      element: document.querySelector('#PATOverviewGraph'),
      renderer: 'line',
      series: [{
        data: [
            <?php for($i = 0; $i < count($pat_overview_graph); $i++){ 
            if($pat_overview_graph[$i]->dashboard_amount != null){    ?>
            { x: <?php echo $i ?>, y: <?php echo $pat_overview_graph[$i]->dashboard_amount ?> },
            <?php } } ?>
        ],
        color: '#ffffff',
        name: 'Revenue',
      }]
    });
    
    patOverviewGraph.render();

    // Responsive Mode
    new ResizeSensor($('.slim-mainpanel'), function(){
      patOverviewGraph.configure({
        width: $('#PATOverviewGraph').width(),
        height: $('#PATOverviewGraph').height()
      });
      patOverviewGraph.render();
    });



  });


//END RICKSHAW CHARTS


//START CHECKING WHAT COLOUR PRIMARY CARD SHOULD BE


var revenuePercentage = ({{$revenue->ytd_revenue}}/{{$revenue->ytd_target}}) * 100;

if(revenuePercentage <= 80){
    $("#PrimaryRevenueCard").addClass('bg-danger');
}
else if(revenuePercentage < 100){
    $("#PrimaryRevenueCard").addClass('bg-warning');
}
else{
    $("#PrimaryRevenueCard").addClass('bg-success');
}


var cosPercentage = ({{$cos->ytd_revenue}}/{{$cos->ytd_target}}) * 100;

if(cosPercentage <= 80){
    $("#PrimaryCOSCard").addClass('bg-success');
}
else if(cosPercentage > 100){
    $("#PrimaryCOSCard").addClass('bg-warning');
}
else{
    $("#PrimaryCOSCard").addClass('bg-danger');
}

var gplPercentage = ({{$gpl->ytd_revenue}}/{{$gpl->ytd_target}}) * 100;

if(gplPercentage <= 80){
    $("#PrimaryGPLCard").addClass('bg-danger');
}
else if(gplPercentage < 100){
    $("#PrimaryGPLCard").addClass('bg-warning');
}
else{
    $("#PrimaryGPLCard").addClass('bg-success');
}

var opexPercentage = ({{$opex->ytd_revenue}}/{{$opex->ytd_target}}) * 100;

if(opexPercentage <= 80){
    $("#PrimaryOPEXCard").addClass('bg-success');
}
else if(opexPercentage < 100){
    $("#PrimaryOPEXCard").addClass('bg-warning');
}
else{
    $("#PrimaryOPEXCard").addClass('bg-danger');
}

var patPercentage = ({{$pat->ytd_revenue}}/{{$pat->ytd_target}}) * 100;

if(patPercentage <= 80){
    $("#PrimaryPATCard").addClass('bg-danger');
}
else if(patPercentage < 100){
    $("#PrimaryPATCard").addClass('bg-warning');
}
else{
    $("#PrimaryPATCard").addClass('bg-success');
}

var ebitdaPercentage = ({{$ebitda->ytd_revenue}}/{{$ebitda->ytd_target}}) * 100;

if(ebitdaPercentage <= 80){
    $("#PrimaryEBITDACard").addClass('bg-danger');
}
else if(ebitdaPercentage < 100){
    $("#PrimaryEBITDACard").addClass('bg-warning');
}
else{
    $("#PrimaryEBITDACard").addClass('bg-success');
}






//END CHECKING WHAT COLOUR PRIMARY CARD SHOULD BE 


pushToDetailedCollectionDataset('Revenue',revenueDetailedData);
pushToDetailedCollectionDataset('Cost Of Sale',costOfSaleDetailedData);
pushToDetailedCollectionDataset('Gross Profit / Loss',gplDetailedData);
pushToDetailedCollectionDataset('Operating Expense',opexDetailedData);
pushToDetailedCollectionDataset('PAT',patDetailedData);


outputDetailedDataGroup('Revenue',false); //Output Detailed Data Revenue by Default

pieChartBreakdownData.forEach(i => {
    outputChart(i);
  });

</script>


@endsection