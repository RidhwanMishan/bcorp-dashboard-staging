function outputDetailedDataGroup(dataGroupName,scroll = true){

    var foundDataGroupFlag = false;
    dataGroupNameText = dataGroupName.replace(/_/g, ' ');

    for (var i = 0; i < detailedDataCollection.dataGroups.length; i++) {
      if(detailedDataCollection.dataGroups[i]["name"] == dataGroupName){
          
          detailedDataCollection.dataGroups[i]["charts"].forEach(i => {
            outputChart(i);
          });

          $("#DetailedDataLabel").text(dataGroupNameText);

          foundDataGroupFlag = true;
          break;
      }
    }

    if(foundDataGroupFlag == false){

      $("#DetailedDataLabel").text(dataGroupNameText + ' - No Data Available');

      $('.detailedDataChart').each(function(){
        var canvas = this;
        var context = canvas.getContext('2d');

        // clear charts
        context.canvas.width = context.canvas.width;

      });
    }

    if(scroll == true){
      $('html, body').animate({
        scrollTop: $("#DetailedDataLabel").offset().top-200
    }, 500);
    }
    
  }
  
  function outputChart(chartData) {
    var canvas = document.getElementById(chartData.elementId);
    //var ctx = canvas.getContext("2d");

    var parent = canvas.parentElement;

    //ctx.clearRect(0, 0, canvas.width, canvas.height);

    var oldcanv = document.getElementById(chartData.elementId);
    parent.removeChild(oldcanv)

    canvas = document.createElement('canvas');
    canvas.id = chartData.elementId;
    parent.appendChild(canvas);
    var ctx = canvas.getContext("2d");


    var NewChart = new Chart(ctx, {
      type: chartData.chartType,
      data: chartData,
      options: {
           legend: {
              display: chartData.legend
           }
      }
    });

    //IF BREAKDOWN PIECHART NEED TO BE ABLE TO CLICK PIE CHART TO CHANGE DATA
    if(chartData.breakdown == true && companyPage == true){
      canvas.onclick = function(evt) {
        var activePoints = NewChart.getElementsAtEvent(evt);
        if (activePoints[0]) {
          var chartData = activePoints[0]['_chart'].config.data;
          var idx = activePoints[0]['_index'];
  
          var label = chartData.labels[idx];

          var dataGroupName = label;
          outputDetailedDataGroup(dataGroupName)
        }
      };
    }
  }


  function pushToDetailedCollectionDataset(dataGroupName,dataCharts){
    const dataGroup ={
      "name" : dataGroupName,
      "charts": dataCharts
    };
   
    detailedDataCollection.dataGroups.push(dataGroup);
  }


  $(".changable-data-group").click(function(){
      var dataGroupName = $(this).attr('dataGroupName');
      outputDetailedDataGroup(dataGroupName)
  });

  

  //END OUTPUT MAIN CHARTS SECTION

  //START RESPONSIVE CHARTS?
  if (window.parent && window.parent.parent){
    window.parent.parent.postMessage(["resultsFrame", {
      height: document.body.getBoundingClientRect().height,
      slug: "L42tnpcm"
    }], "*")
  }

  window.name = "result"
  //END RESPONSIVE CHARTS?