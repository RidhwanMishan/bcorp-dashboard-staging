var actualColour = "#0088cc";
var actualTextLabel = "Actual";
var targetColour = "#dc3546";
var targetTextLabel = "Target";
var preChosenBackgroundColors = [actualColour,"#2aaab1","#734ba9","#e3615a","#dc3546","#cadc35","#6edc35","#dc75d4","#c5d29c","#363b54"];

var pieChartBreakdownData = [];

var detailedDataCollection ={"dataGroups" :[]};


  // START DETAILED REVENUE DATA
  var revenueDetailedData = [];
  // START DETAILED REVENUE MONTHLY ACTUAL / TARGET DATA
  var revenueMonthlyActualTargetData = {
    datasets: [
      {data: [10, 12],borderColor: actualColour,label: actualTextLabel},
      {data: [12, 10, 6,11,15,10,12,10,6,11,15,10],borderColor: targetColour,label:targetTextLabel}
    ],
    labels: ["July","August","September","October","November","December","January","February","March","April","May","June"],
    elementId: ["MonthlyActualTargetChart"],
    chartType: ['line'],
    legend: true 
  };

  revenueDetailedData.push(revenueMonthlyActualTargetData);
  
  // END DETAILED REVENUE MONTHLY ACTUAL / TARGET DATA

  // START DETAILED REVENUE CUMULATIVE ACTUAL / TARGET DATA
  var revenueCumulativeActualTargetData = {
    datasets: [
      {data: [10, 22],borderColor: actualColour,label:actualTextLabel},
      {data: [5, 20, 40,50,60,85,90,94,110,130,150,155],borderColor: targetColour,label:targetTextLabel}
    ],
    labels: ["July","August","September","October","November","December","January","February","March","April","May","June"],
    elementId: ["CumulativeActualTargetChart"],
    chartType: ['line'],
    legend: true 
  };
  
  revenueDetailedData.push(revenueCumulativeActualTargetData);
  // END DETAILED REVENUE CUMULATIVE ACTUAL / TARGET DATA

  // START DETAILED REVENUE quarterly ACTUAL / TARGET DATA
  var revenuequarterlyActualTargetData = {
    datasets: [
      {data: [],borderColor: actualColour,label:actualTextLabel},
      {data: [29, 25, 40,30],borderColor: targetColour,label:targetTextLabel}
    ],
    labels: ["September","December","March","June"],
    elementId: ["quarterlyActualTargetChart"],
    chartType: ['line'],
    legend: true 
  };
  
  revenueDetailedData.push(revenuequarterlyActualTargetData);
  // END DETAILED REVENUE quarterly ACTUAL / TARGET DATA   

  // START DETAILED REVENUE MONTHLY VARIANCE DATA
  var revenueMonthlyVarianceData = {
    datasets: [
      {data: [5, -2],borderColor: actualColour,label:actualTextLabel},
    ],
    labels: ["July","August","September","October","November","December","January","February","March","April","May","June"],
    elementId: ["MonthlyVarianceChart"],
    chartType: ['line'],
    legend: true 
  };
  
  revenueDetailedData.push(revenueMonthlyVarianceData);
  // END DETAILED REVENUE MONTHLY VARIANCE DATA  

  // START DETAILED REVENUE CUMULATIVE VARIANCES DATA
  var revenueCumativelyVariancesData = {
      datasets: [
        {data: [5, 10,5,10,5,10,5,10,5,10,5,10],borderColor: actualColour,label:actualTextLabel},
      ],
      labels: ["July","August","September","October","November","December","January","February","March","April","May","June"],
      elementId: ["CumativelyVariancesChart"],
      chartType: ['line'],
      legend: true 
    };
  
  revenueDetailedData.push(revenueCumativelyVariancesData);
  // END DETAILED REVENUE CUMULATIVE VARIANCES DATA  

  // START DETAILED REVENUE quarterly VARIANCES DATA
  var revenuequarterlyVariancesData = {
      datasets: [
        {data: [10,5,1],borderColor: actualColour,label:actualTextLabel},
      ],
      labels: ["September","December","March","June"],
      elementId: ["quarterlyVariancesChart"],
      chartType: ['line'],
      legend: true 
    };
  
  revenueDetailedData.push(revenuequarterlyVariancesData);
  // END DETAILED REVENUE quarterly VARIANCES DATA

  pushToDetailedCollectionDataset('Revenue',revenueDetailedData);
  // END DETAILED REVENUE DATA



  // START DETAILED COST OF SALE DATA
  var costOfSaleDetailedData = [];
  // START DETAILED COST OF SALE MONTHLY ACTUAL / TARGET DATA
  var costOfSaleMonthlyActualTargetData = {
    datasets: [
      {data: [10, 12],borderColor: actualColour,label: actualTextLabel},
      {data: [5, 10, 15,10,15,10,15,10,15,10,15,10],borderColor: targetColour,label:targetTextLabel}
    ],
    labels: ["July","August","September","October","November","December","January","February","March","April","May","June"],
    elementId: ["MonthlyActualTargetChart"],
    chartType: ['line'],
    legend: true 
  };

  costOfSaleDetailedData.push(costOfSaleMonthlyActualTargetData);
  
  // END DETAILED COST OF SALE MONTHLY ACTUAL / TARGET DATA

  // START DETAILED COST OF SALE CUMULATIVE ACTUAL / TARGET DATA
  var costOfSaleCumulativeActualTargetData = {
    datasets: [
      {data: [5,10,15,20,45,30],borderColor: actualColour,label:actualTextLabel},
      {data: [5,10,15,20,25,30,35,40,45,50,55,60],borderColor: targetColour,label:targetTextLabel}
    ],
    labels: ["July","August","September","October","November","December","January","February","March","April","May","June"],
    elementId: ["CumulativeActualTargetChart"],
    chartType: ['line'],
    legend: true 
  };
  
  costOfSaleDetailedData.push(costOfSaleCumulativeActualTargetData);
  // END DETAILED COST OF SALE CUMULATIVE ACTUAL / TARGET DATA

  // START DETAILED COST OF SALE quarterly ACTUAL / TARGET DATA
  var costOfSalequarterlyActualTargetData = {
    datasets: [
      {data: [10,20],borderColor: actualColour,label:actualTextLabel},
      {data: [5,10,15,20],borderColor: targetColour,label:targetTextLabel}
    ],
    labels: ["September","December","March","June"],
    elementId: ["quarterlyActualTargetChart"],
    chartType: ['line'],
    legend: true 
  };
  
  costOfSaleDetailedData.push(costOfSalequarterlyActualTargetData);
  // END DETAILED COST OF SALE quarterly ACTUAL / TARGET DATA   

  // START DETAILED COST OF SALE MONTHLY VARIANCE DATA
  var costOfSaleMonthlyVarianceData = {
    datasets: [
      {data: [2,-2,5,2,1],borderColor: actualColour,label:actualTextLabel},
    ],
    labels: ["July","August","September","October","November","December","January","February","March","April","May","June"],
    elementId: ["MonthlyVarianceChart"],
    chartType: ['line'],
    legend: true 
  };
  
  costOfSaleDetailedData.push(costOfSaleMonthlyVarianceData);
  // END DETAILED COST OF SALE MONTHLY VARIANCE DATA  

  // START DETAILED COST OF SALE CUMULATIVE VARIANCES DATA
  var costOfSaleCumativelyVariancesData = {
      datasets: [
        {data: [2,4,6,8,10,8,6,8,3,-2],borderColor: actualColour,label:actualTextLabel},
      ],
      labels: ["July","August","September","October","November","December","January","February","March","April","May","June"],
      elementId: ["CumativelyVariancesChart"],
      chartType: ['line'],
      legend: true 
    };
  
  costOfSaleDetailedData.push(costOfSaleCumativelyVariancesData);
  // END DETAILED COST OF SALE CUMULATIVE VARIANCES DATA  

  // START DETAILED COST OF SALE quarterly VARIANCES DATA
  var costOfSalequarterlyVariancesData = {
      datasets: [
        {data: [20,10,5,-5],borderColor: actualColour,label:actualTextLabel},
      ],
      labels: ["September","December","March","June"],
      elementId: ["quarterlyVariancesChart"],
      chartType: ['line'],
      legend: true 
    };
  
  costOfSaleDetailedData.push(costOfSalequarterlyVariancesData);
  // END DETAILED COST OF SALE quarterly VARIANCES DATA
  pushToDetailedCollectionDataset('Cost Of Sale',costOfSaleDetailedData);
  // END DETAILED COST OF SALE DATA


    // START DETAILED REVENUE BREAKDOWN CHART STORES DATA
    var revenueBreakdownStores = [];
    // START DETAILED REVENUE BREAKDOWN CHART STORES MONTHLY ACTUAL / TARGET DATA
    var revenueBreakdownStoresMonthlyActualTargetData = {
        datasets: [
        {data: [2, 10],borderColor: actualColour,label: actualTextLabel},
        {data: [1, 2, 4, 5, 4,3,2,1,15,10,15,10],borderColor: targetColour,label:targetTextLabel}
        ],
        labels: ["July","August","September","October","November","December","January","February","March","April","May","June"],
        elementId: ["MonthlyActualTargetChart"],
        chartType: ['line'],
        legend: true 
    };

    revenueBreakdownStores.push(revenueBreakdownStoresMonthlyActualTargetData);

    // END DETAILED REVENUE BREAKDOWN CHART STORES MONTHLY ACTUAL / TARGET DATA

    // START DETAILED REVENUE BREAKDOWN CHART STORES CUMULATIVE ACTUAL / TARGET DATA
    var revenueBreakdownStoresCumulativeActualTargetData = {
        datasets: [
        {data: [5,10,15,20,45,30],borderColor: actualColour,label:actualTextLabel},
        {data: [5,10,15,20,25,30,35,40,45,50,55,60],borderColor: targetColour,label:targetTextLabel}
        ],
        labels: ["July","August","September","October","November","December","January","February","March","April","May","June"],
        elementId: ["CumulativeActualTargetChart"],
        chartType: ['line'],
        legend: true 
    };

    revenueBreakdownStores.push(revenueBreakdownStoresCumulativeActualTargetData);
    // END DETAILED REVENUE BREAKDOWN CHART STORES CUMULATIVE ACTUAL / TARGET DATA

    // START DETAILED REVENUE BREAKDOWN CHART STORES quarterly ACTUAL / TARGET DATA
    var revenueBreakdownStoresquarterlyActualTargetData = {
        datasets: [
        {data: [10,20],borderColor: actualColour,label:actualTextLabel},
        {data: [5,10,15,20],borderColor: targetColour,label:targetTextLabel}
        ],
        labels: ["September","December","March","June"],
        elementId: ["quarterlyActualTargetChart"],
        chartType: ['line'],
        legend: true 
    };

    revenueBreakdownStores.push(revenueBreakdownStoresquarterlyActualTargetData);
    // END DETAILED REVENUE BREAKDOWN CHART STORES quarterly ACTUAL / TARGET DATA   

    // START DETAILED REVENUE BREAKDOWN CHART STORES MONTHLY VARIANCE DATA
    var revenueBreakdownStoresMonthlyVarianceData = {
        datasets: [
        {data: [2,-2,5,2,1],borderColor: actualColour,label:actualTextLabel},
        ],
        labels: ["July","August","September","October","November","December","January","February","March","April","May","June"],
        elementId: ["MonthlyVarianceChart"],
        chartType: ['line'],
        legend: true 
    };

    revenueBreakdownStores.push(revenueBreakdownStoresMonthlyVarianceData);
    // END DETAILED REVENUE BREAKDOWN CHART STORES MONTHLY VARIANCE DATA  

    // START DETAILED REVENUE BREAKDOWN CHART STORES CUMULATIVE VARIANCES DATA
    var revenueBreakdownStoresCumativelyVariancesData = {
        datasets: [
            {data: [2,4,6,8,10,8,6,8,3,-2],borderColor: actualColour,label:actualTextLabel},
        ],
        labels: ["July","August","September","October","November","December","January","February","March","April","May","June"],
        elementId: ["CumativelyVariancesChart"],
        chartType: ['line'],
        legend: true 
        };

    revenueBreakdownStores.push(revenueBreakdownStoresCumativelyVariancesData);
    // END DETAILED REVENUE BREAKDOWN CHART STORES CUMULATIVE VARIANCES DATA  

    // START DETAILED REVENUE BREAKDOWN CHART STORES quarterly VARIANCES DATA
    var revenueBreakdownStoresquarterlyVariancesData = {
        datasets: [
            {data: [20,10,5,-5],borderColor: actualColour,label:actualTextLabel},
        ],
        labels: ["September","December","March","June"],
        elementId: ["quarterlyVariancesChart"],
        chartType: ['line'],
        legend: true 
        };

    revenueBreakdownStores.push(revenueBreakdownStoresquarterlyVariancesData);
    // END DETAILED REVENUE BREAKDOWN CHART STORES quarterly VARIANCES DATA
    pushToDetailedCollectionDataset('Revenue_Current_Month_Breakdown_Stores',revenueBreakdownStores);
    // END DETAILED REVENUE BREAKDOWN CHART STORES DATA


  ///////////////////////


  // START PIE CHART BREAKDOWNS SECTION
  // START REVENUE CURRENT MONTH BREAKDOWN PIE CHART
  var revenueCurrentMonthBreakdownData = {
    datasets: [{
      data: [15, 15, 25, 45],
      //data: [15, 10, 20, 10, 10, 5, 30],
      backgroundColor: preChosenBackgroundColors
    }],
    labels: ["Kenny Rogers", "Krispy Kream", "Sala", "Starbucks"],
    //labels: ["Country Farms", "Jollibean", "Joybean", "Kenny Rogers", "Krispy Kream", "Sala", "Starbucks"],
    elementId: ["Revenue_Current_Month_Breakdown"],
    chartType: ['pie'],
    legend: true, 
    breakdown: true
  };

  pieChartBreakdownData.push(revenueCurrentMonthBreakdownData);
  
  // END REVENUE CURRENT MONTH BREAKDOWN PIE CHART

  // START COST OF SALE CURRENT MONTH BREAKDOWN PIE CHART
  var costOfSaleCurrentMonthBreakdownData = {
    datasets: [{
      data: [15, 15, 25, 45],
      //data: [15, 10, 20, 10, 10, 5, 30],
      backgroundColor: preChosenBackgroundColors
    }],
    labels: ["Kenny Rogers", "Krispy Kream", "Sala", "Starbucks"],
    //labels: ["Country Farms", "Jollibean", "Joybean", "Kenny Rogers", "Krispy Kream", "Sala", "Starbucks"],
    elementId: ["Cost_Of_Sale_Current_Month_Breakdown"],
    chartType: ['pie'],
    legend: true, 
    breakdown: true
  };

  pieChartBreakdownData.push(costOfSaleCurrentMonthBreakdownData);
  
  // END COST OF SALE CURRENT MONTH BREAKDOWN PIE CHART

  // START GROSS PROFIT / LOSS CURRENT MONTH BREAKDOWN PIE CHART
  var grossProfitLossCurrentMonthBreakdownData = {
    datasets: [{
      data: [15, 15, 25, 45],
      //data: [15, 10, 20, 10, 10, 5, 30],
      backgroundColor: preChosenBackgroundColors
    }],
    labels: ["Kenny Rogers", "Krispy Kream", "Sala", "Starbucks"],
    //labels: ["Country Farms", "Jollibean", "Joybean", "Kenny Rogers", "Krispy Kream", "Sala", "Starbucks"],
    elementId: ["Gross_Profit_Loss_Current_Month_Breakdown"],
    chartType: ['pie'],
    legend: true, 
    breakdown: true
  };

  pieChartBreakdownData.push(grossProfitLossCurrentMonthBreakdownData);
  
  // END GROSS PROFIT / LOSS CURRENT MONTH BREAKDOWN PIE CHART  


  // START OPERATING EXPENSE CURRENT MONTH BREAKDOWN PIE CHART
  var operatingExpenseCurrentMonthBreakdownData = {
    datasets: [{
      data: [15, 15, 25, 45],
      //data: [15, 10, 20, 10, 10, 5, 30],
      backgroundColor: preChosenBackgroundColors
    }],
    labels: ["Kenny Rogers", "Krispy Kream", "Sala", "Starbucks"],
    //labels: ["Country Farms", "Jollibean", "Joybean", "Kenny Rogers", "Krispy Kream", "Sala", "Starbucks"],
    elementId: ["Operating_Expense_Current_Month_Breakdown"],
    chartType: ['pie'],
    legend: true, 
    breakdown: true
  };

  pieChartBreakdownData.push(operatingExpenseCurrentMonthBreakdownData);

  // END OPERATING EXPENSE CURRENT MONTH BREAKDOWN PIE CHART


  // START EBITDA CURRENT MONTH BREAKDOWN PIE CHART
  var ebitdaCurrentMonthBreakdownData = {
    datasets: [{
      data: [15, 15, 25, 45],
      //data: [15, 10, 20, 10, 10, 5, 30],
      backgroundColor: preChosenBackgroundColors
    }],
    labels: ["Kenny Rogers", "Krispy Kream", "Sala", "Starbucks"],
    //labels: ["Country Farms", "Jollibean", "Joybean", "Kenny Rogers", "Krispy Kream", "Sala", "Starbucks"],
    elementId: ["EBITDA_Current_Month_Breakdown"],
    chartType: ['pie'],
    legend: true, 
    breakdown: true
  };

  pieChartBreakdownData.push(ebitdaCurrentMonthBreakdownData);
  
  // END EBITDA CURRENT MONTH BREAKDOWN PIE CHART


  // START COGS CURRENT MONTH BREAKDOWN PIE CHART
  var cogsCurrentMonthBreakdownData = {
    datasets: [{
      data: [15, 15, 25, 45],
      //data: [15, 10, 20, 10, 10, 5, 30],
      backgroundColor: preChosenBackgroundColors
    }],
    labels: ["Kenny Rogers", "Krispy Kream", "Sala", "Starbucks"],
    //labels: ["Country Farms", "Jollibean", "Joybean", "Kenny Rogers", "Krispy Kream", "Sala", "Starbucks"],
    elementId: ["COGS_Current_Month_Breakdown"],
    chartType: ['pie'],
    legend: true, 
    breakdown: true
  };

  pieChartBreakdownData.push(cogsCurrentMonthBreakdownData);
  
  // END PAT CURRENT MONTH BREAKDOWN PIE CHART
  // END PIE CHART BREAKDOWNS SECTION


///////////////////////


  //START INITIAL OUTPUT  OF DETAILED CHARTS
  
  outputDetailedDataGroup('Revenue',false);

  pieChartBreakdownData.forEach(i => {
    outputChart(i);
  });

  //END INITIAL OUTPUT  OF DETAILED CHARTS



///////////////////////

  //START RICKSHAW CHARTS
  $(function(){
    'use strict'

    var rs1 = new Rickshaw.Graph({
      element: document.querySelector('#rs1'),
      renderer: 'bar',
      series: [{
        data: [
          { x: 0, y: 5 },
          { x: 1, y: 7 },
          { x: 2, y: 10 },
          { x: 3, y: 11 },
          { x: 4, y: 12 },
          { x: 5, y: 10 },
          { x: 6, y: 9 },
          { x: 7, y: 7 },
          { x: 8, y: 6 },
          { x: 9, y: 8 },
          { x: 10, y: 9 },
          { x: 11, y: 10 },
          { x: 12, y: 7 },
          { x: 13, y: 10 }
        ],
        color: '#ffffff',
        name: 'Revenue',
      }]
    });
    rs1.render();

    // var hoverDetail = new Rickshaw.Graph.HoverDetail( {
    //     graph: rs1
    //   } );

    // Responsive Mode
    new ResizeSensor($('.slim-mainpanel'), function(){
      rs1.configure({
        width: $('#rs1').width(),
        height: $('#rs1').height()
      });
      rs1.render();
    });

    var rs2 = new Rickshaw.Graph({
      element: document.querySelector('#rs2'),
      renderer: 'line',
      series: [{
        data: [
          { x: 0, y: 5 },
          { x: 1, y: 7 },
          { x: 2, y: 10 },
          { x: 3, y: 11 },
          { x: 4, y: 12 },
          { x: 5, y: 10 },
          { x: 6, y: 9 },
          { x: 7, y: 7 },
          { x: 8, y: 6 },
          { x: 9, y: 8 },
          { x: 10, y: 9 },
          { x: 11, y: 10 },
          { x: 12, y: 7 },
          { x: 13, y: 10 }
        ],
        color: targetColour
      },
      {
        data: [
          { x: 0, y: 3 },
          { x: 1, y: 5 },
          { x: 2, y: 8 },
          { x: 3, y: 15 },
          { x: 4, y: 10 },
          { x: 5, y: 8 },
          { x: 6, y: 12 },
          { x: 7, y: 14 },
          { x: 8, y: 7 },
          { x: 9, y: 3 },
          { x: 10, y: 2 },
          { x: 11, y: 9 },
          { x: 12, y: 11 },
          { x: 13, y: 12 }
        ],
        color: actualColour
      }]
    });
    rs2.render();

    // Responsive Mode
    new ResizeSensor($('.slim-mainpanel'), function(){
      rs2.configure({
        width: $('#rs2').width(),
        height: $('#rs2').height()
      });
      rs2.render();
    });

    var rs3 = new Rickshaw.Graph({
      element: document.querySelector('#rs3'),
      renderer: 'bar',
      series: [{
        data: [
          { x: 0, y: 5 },
          { x: 1, y: 7 },
          { x: 2, y: 10 },
          { x: 3, y: 2 },
          { x: 4, y: 12 },
          { x: 5, y: 10 },
          { x: 6, y: 9 },
          { x: 7, y: 12 },
          { x: 8, y: 6 },
          { x: 9, y: 8 },
          { x: 10, y: 9 },
          { x: 11, y: 10 },
          { x: 12, y: 7 },
          { x: 13, y: 15 }
        ],
        color: '#ffffff',
      }]
    });
    rs3.render();

    // Responsive Mode
    new ResizeSensor($('.slim-mainpanel'), function(){
      rs3.configure({
        width: $('#rs3').width(),
        height: $('#rs3').height()
      });
      rs3.render();
    });

    var rs4 = new Rickshaw.Graph({
      element: document.querySelector('#rs4'),
      renderer: 'line',
      series: [{
        data: [
          { x: 0, y: 5 },
          { x: 1, y: 7 },
          { x: 2, y: 10 },
          { x: 3, y: 3 },
          { x: 4, y: 12 },
          { x: 5, y: 10 },
          { x: 6, y: 9 },
          { x: 7, y: 7 },
          { x: 8, y: 6 },
          { x: 9, y: 8 },
          { x: 10, y: 9 },
          { x: 11, y: 10 },
          { x: 12, y: 10 },
          { x: 13, y: 10 }
        ],
        color: '#ffffff',
      }]
    });
    rs4.render();

    // Responsive Mode
    new ResizeSensor($('.slim-mainpanel'), function(){
      rs4.configure({
        width: $('#rs4').width(),
        height: $('#rs4').height()
      });
      rs4.render();
    });

    var rs5 = new Rickshaw.Graph({
      element: document.querySelector('#rs5'),
      renderer: 'bar',
      series: [{
        data: [
          { x: 0, y: 5 },
          { x: 1, y: 2 },
          { x: 2, y: 10 },
          { x: 3, y: 11 },
          { x: 4, y: 12 },
          { x: 5, y: 10 },
          { x: 6, y: 12 },
          { x: 7, y: 16 },
          { x: 8, y: 6 },
          { x: 9, y: 3 },
          { x: 10, y: 9 },
          { x: 11, y: 10 },
          { x: 12, y: 7 },
          { x: 13, y: 5 }
        ],
        color: '#ffffff',
      }]
    });
    rs5.render();

    // Responsive Mode
    new ResizeSensor($('.slim-mainpanel'), function(){
      rs5.configure({
        width: $('#rs5').width(),
        height: $('#rs5').height()
      });
      rs5.render();
    });

    var rs6 = new Rickshaw.Graph({
      element: document.querySelector('#rs6'),
      renderer: 'line',
      series: [{
        data: [
          { x: 0, y: 5 },
          { x: 1, y: 7 },
          { x: 2, y: 10 },
          { x: 3, y: 11 },
          { x: 4, y: 12 },
          { x: 5, y: 10 },
          { x: 6, y: 9 },
          { x: 7, y: 7 },
          { x: 8, y: 6 },
          { x: 9, y: 8 },
          { x: 10, y: 9 },
          { x: 11, y: 10 },
          { x: 12, y: 7 },
          { x: 13, y: 3 }
        ],
        color: '#ffffff',
      }]
    });
    rs6.render();

    // Responsive Mode
    new ResizeSensor($('.slim-mainpanel'), function(){
      rs6.configure({
        width: $('#rs6').width(),
        height: $('#rs6').height()
      });
      rs6.render();
    });





  });


//END RICKSHAW CHARTS


//DEBUG START
// var outputElement = document.getElementById("output");
// outputElement.innerHTML = JSON.stringify(detailedDataCollection);
//DEBUG END