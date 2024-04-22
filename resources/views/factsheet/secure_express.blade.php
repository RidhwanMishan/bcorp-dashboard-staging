@extends('retail.index')

@section('title') Factsheet @endsection

@section('content')

@php
$name = 'Secureexpress Services Sdn Bhd';
@endphp


<div class="slim-mainpanel factsheet">
    <div class="container">

    <div class="slim-pageheader">
        <div>
        </div>
        <h6 class="slim-pagetitle">{{ $name }}</h6>
    </div>

    <div class="row">

        <div class="col-md-8">

            <div class="card bd-0">
                <div class="card-header tx-medium bd-0 tx-white bg-primary tx-center">
                    KEY FIGURES
                </div><!-- card-header -->
            </div>

            <div class="row row-xs mt-2">
            <div class="col-md-12">
                <div class="row row-xs">

                <div class="col-md-6">
                    <div class="card bd-0 mb-2">
                        <div class="card-header tx-medium bd-0 tx-white bg-success">
                            GROSS REVENUE (MYR)
                        </div><!-- card-header -->
                        <div class="card-body bd bd-t-0">

                            <canvas id="myChart"></canvas>
                            
                            <script>
                                const ctx = document.getElementById('myChart').getContext('2d');
                                const myChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: <?php echo json_encode($revenue_year);?>,
                                        datasets: [{
                                            label: 'MYR',


                                            data: <?php echo json_encode($revenue_amount);?>,
                                            backgroundColor: [
                                            'rgba(243, 213, 221,1)',
                                            'rgba(243, 213, 221,1)',
                                            'rgba(243, 213, 221,1)',
                                            'rgba(237, 192, 204, 1)',
                                            'rgba(231, 171, 187, 1)',
                                            'rgba(225, 150, 170, 1)',
                                            'rgba(0, 80, 160, 1)'
                                            ],
                                            borderColor: [
                                            'rgba(243, 213, 221, 1)',
                                            'rgba(243, 213, 221,1)',
                                            'rgba(243, 213, 221,1)',
                                            'rgba(237, 192, 204, 1)',
                                            'rgba(231, 171, 187, 1)',
                                            'rgba(225, 150, 170, 1)',
                                            'rgba(0, 80, 160, 1)'
                                            ],
                                            borderWidth: 1,
                                            order: 1,
                                            tension:0.3,
                                            datalabels : {
                                                formatter: function intToString (value) {
                                                    var suffixes = ["", "K", "M", "B","T"];
                                                    var suffixNum = Math.floor((""+value).length/4);
                                                    var shortValue = parseFloat((suffixNum != 0 ? (value / Math.pow(1000,suffixNum)) : value).toPrecision(3));
                                                    if (shortValue % 1 != 0) {
                                                        shortValue = shortValue.toFixed(1);
                                                    }
                                                    return shortValue+suffixes[suffixNum];
                                                },
                                                anchor: 'end',
                                                align: 'top',
                                                offset: 5
                                            }
                                        }]

                                    },

                                    plugins: [ChartDataLabels],
                                    options: {
                                        plugins: {
                                            legend: {
                                                display: false
                                            },
                                        },
                                        scales: {
                                            x: {
                                                ticks: {
                                                    display: true
                                                },
                                                gridLines:{
                                                    display:false
                                                }
                                            },
                                            y: {
                                                ticks: {
                                                    count: 5,
                                                    callback: function(value) {
                                                        var ranges = [
                                                        { divider: 1e6, suffix: 'M' },
                                                        { divider: 1e3, suffix: 'K' }
                                                        ];
                                                        function formatNumber(n) {
                                                            for (var i = 0; i < ranges.length; i++) {
                                                                if (n >= ranges[i].divider) {
                                                                    return (n / ranges[i].divider).toString() + ranges[i].suffix;
                                                                }
                                                            }
                                                            return n;
                                                        }
                                                        return formatNumber(value);
                                                    }
                                                },
                                                gridLines:{
                                                    display:false
                                                }
                                            }
                                        },

                                    }

                                });
                            </script>
                        </div><!-- card-body -->
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card bd-0 mb-2">
                        <div class="card-header tx-medium bd-0 tx-white bg-success">
                        COST OF SALE (MYR)
                        </div><!-- card-header -->
                        <div class="card-body bd bd-t-0">
                            <canvas id="myChart1"></canvas>

                            <script>
                                const ctx1 = document.getElementById('myChart1').getContext('2d');
                                const myChart1 = new Chart(ctx1, {
                                    type: 'bar',
                                    data: {
                                        labels: <?php echo json_encode($cos_year);?>,
                                        datasets: [{
                                            label: 'MYR',

                                            data: <?php echo json_encode($cos_amount);?>,
                                            backgroundColor: [
                                            'rgba(225, 236, 246, 1)',
                                            'rgba(225, 236, 246, 1)',
                                            'rgba(225, 236, 246, 1)',
                                            'rgba(211, 227, 245, 1)',
                                            'rgba(196, 218, 241, 1)',
                                            'rgba(181, 208, 238, 1)',
                                            'rgba(0, 80, 160, 1)'
                                            ],
                                            borderColor: [
                                            'rgba(225, 236, 246, 1)',
                                            'rgba(225, 236, 246, 1)',
                                            'rgba(225, 236, 246, 1)',
                                            'rgba(211, 227, 245, 1)',
                                            'rgba(196, 218, 241, 1)',
                                            'rgba(181, 208, 238, 1)',
                                            'rgba(0, 80, 160, 1)'
                                            ],
                                            borderWidth: 1,
                                            order: 1,
                                            tension:0.3,
                                            datalabels : {
                                                formatter: function intToString (value) {
                                                    var suffixes = ["", "K", "M", "B","T"];
                                                    var suffixNum = Math.floor((""+value).length/4);
                                                    var shortValue = parseFloat((suffixNum != 0 ? (value / Math.pow(1000,suffixNum)) : value).toPrecision(3));
                                                    if (shortValue % 1 != 0) {
                                                        shortValue = shortValue.toFixed(1);
                                                    }
                                                    return shortValue+suffixes[suffixNum];
                                                },
                                                anchor: 'end',
                                                align: 'top',
                                                offset: 5
                                            }
                                        }]

                                    },

                                    plugins: [ChartDataLabels],
                                    options: {
                                        plugins: {
                                            legend: {
                                                display: false
                                            },
                                        },
                                        scales: {
                                            x: {
                                                ticks: {
                                                    display: true
                                                },
                                                gridLines:{
                                                    display: false
                                                }
                                            },
                                            y: {
                                                ticks: {
                                                    count: 5, 
                                                    callback: function(value) {
                                                        var ranges = [
                                                        { divider: 1e6, suffix: 'M' },
                                                        { divider: 1e3, suffix: 'K' }
                                                        ];
                                                        function formatNumber(n) {
                                                            for (var i = 0; i < ranges.length; i++) {
                                                                if (n >= ranges[i].divider) {
                                                                    return (n / ranges[i].divider).toString() + ranges[i].suffix;
                                                                }
                                                            }
                                                            return n;
                                                        }
                                                        return formatNumber(value);
                                                    }
                                                },
                                                gridLines:{
                                                    display:false
                                                }
                                            }
                                        },
                                        
                                    }

                                });


                            </script>
                        </div><!-- card-body -->
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card bd-0 mb-2">
                        <div class="card-header tx-medium bd-0 tx-white bg-success">
                            GROSS PROFIT / LOSS (MYR)
                        </div><!-- card-header -->
                        <div class="card-body bd bd-t-0">
                            <canvas id="myChart2"></canvas>
                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                            <script>
                                const ctx2 = document.getElementById('myChart2').getContext('2d');
                                const myChart2 = new Chart(ctx2, {
                                    type: 'bar',
                                    data: {
                                        labels: <?php echo json_encode($gpl_year);?>,
                                        datasets: [{
                                            label: 'MYR',
                                            
                                            data: <?php echo json_encode($gpl_amount);?>,
                                            backgroundColor: [
                                            'rgba(216, 240, 225, 1)',
                                            'rgba(216, 240, 225, 1)',
                                            'rgba(216, 240, 225, 1)',
                                            'rgba(197, 232, 210, 1)',
                                            'rgba(178, 225, 195, 1)',
                                            'rgba(159, 217, 180, 1)',
                                            'rgba(0, 80, 160, 1)'
                                            ],
                                            borderColor: [
                                            'rgba(216, 240, 225, 1)',
                                            'rgba(216, 240, 225, 1)',
                                            'rgba(216, 240, 225, 1)',
                                            'rgba(197, 232, 210, 1)',
                                            'rgba(178, 225, 195, 1)',
                                            'rgba(159, 217, 180, 1)',
                                            'rgba(0, 80, 160, 1)'
                                            ],
                                            borderWidth: 1,
                                            order: 1,
                                            tension:0.3,
                                            datalabels : {
                                                formatter: function intToString (value) {
                                                    var suffixes = ["", "K", "M", "B","T"];
                                                    var suffixNum = Math.floor((""+value).length/4);
                                                    var shortValue = parseFloat((suffixNum != 0 ? (value / Math.pow(1000,suffixNum)) : value).toPrecision(3));
                                                    if (shortValue % 1 != 0) {
                                                        shortValue = shortValue.toFixed(1);
                                                    }
                                                    return shortValue+suffixes[suffixNum];
                                                },
                                                anchor: 'end',
                                                align: 'top',
                                                offset: 5
                                            }
                                        }]

                                    },

                                    plugins: [ChartDataLabels],
                                    options: {
                                        plugins: {
                                            legend: {
                                                display: false
                                            },
                                        },
                                        scales: {
                                            x: {
                                                ticks: {
                                                    display: true
                                                },
                                                gridLines:{
                                                    display: false
                                                }
                                            },
                                            y: {
                                                ticks: {
                                                    count: 5,
                                                    callback: function(value) {
                                                        var ranges = [
                                                        { divider: 1e6, suffix: 'M' },
                                                        { divider: 1e3, suffix: 'K' }
                                                        ];
                                                        function formatNumber(n) {
                                                            for (var i = 0; i < ranges.length; i++) {
                                                                if (n >= ranges[i].divider) {
                                                                    return (n / ranges[i].divider).toString() + ranges[i].suffix;
                                                                }
                                                            }
                                                            return n;
                                                        }
                                                        return formatNumber(value);
                                                    }
                                                },
                                                gridLines:{
                                                    display:false
                                                }
                                            }
                                        },
                                        
                                    }
                                    
                                });
                            </script>
                        </div><!-- card-body -->
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card bd-0 mb-2">
                        <div class="card-header tx-medium bd-0 tx-white bg-success">
                            OPERATING EXPENSE (MYR)
                        </div><!-- card-header -->
                        <div class="card-body bd bd-t-0">
                            <canvas id="myChart3"></canvas>
                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                            <script>
                                const ctx3 = document.getElementById('myChart3').getContext('2d');
                                const myChart3 = new Chart(ctx3, {
                                    type: 'bar',
                                    data: {
                                        labels: <?php echo json_encode($op_year);?>,
                                        datasets: [{
                                            label: 'MYR',

                                            
                                            data: <?php echo json_encode($op_amount);?>,
                                            backgroundColor: [
                                            'rgba(154, 198, 204, 1)',
                                            'rgba(154, 198, 204, 1)',
                                            'rgba(154, 198, 204, 1)',
                                            'rgba(103, 170, 179, 1)',
                                            'rgba(53, 142, 154, 1)',
                                            'rgba(2, 113, 128, 1)',
                                            'rgba(0, 80, 160, 1)'
                                            ],
                                            borderColor: [
                                            'rgba(154, 198, 204, 1)',
                                            'rgba(154, 198, 204, 1)',
                                            'rgba(154, 198, 204, 1)',
                                            'rgba(103, 170, 179, 1)',
                                            'rgba(53, 142, 154, 1)',
                                            'rgba(2, 113, 128, 1)',
                                            'rgba(0, 80, 160, 1)'
                                            ],
                                            borderWidth: 1,
                                            order: 1,
                                            tension:0.3,
                                            datalabels : {
                                                formatter: function intToString (value) {
                                                    var suffixes = ["", "K", "M", "B","T"];
                                                    var suffixNum = Math.floor((""+value).length/4);
                                                    var shortValue = parseFloat((suffixNum != 0 ? (value / Math.pow(1000,suffixNum)) : value).toPrecision(3));
                                                    if (shortValue % 1 != 0) {
                                                        shortValue = shortValue.toFixed(1);
                                                    }
                                                    return shortValue+suffixes[suffixNum];
                                                },
                                                anchor: 'end',
                                                align: 'top',
                                                offset: 5
                                            }
                                        }]

                                    },

                                    plugins: [ChartDataLabels],
                                    options: {
                                        plugins: {
                                            legend: {
                                                display: false
                                            },
                                        },
                                        scales: {
                                            x: {
                                                ticks: {
                                                    display: true
                                                },
                                                gridLines:{
                                                    display: false
                                                }
                                            },
                                            y: {
                                                ticks: {
                                                    count: 5, 
                                                    callback: function(value) {
                                                        var ranges = [
                                                        { divider: 1e6, suffix: 'M' },
                                                        { divider: 1e3, suffix: 'K' }
                                                        ];
                                                        function formatNumber(n) {
                                                            for (var i = 0; i < ranges.length; i++) {
                                                                if (n >= ranges[i].divider) {
                                                                    return (n / ranges[i].divider).toString() + ranges[i].suffix;
                                                                }
                                                            }
                                                            return n;
                                                        }
                                                        return formatNumber(value);
                                                    }
                                                },
                                                gridLines:{
                                                    display:false
                                                }
                                            }
                                        },
                                        
                                    }
                                    
                                });
                            </script>        
                        </div><!-- card-body -->
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card bd-0 mb-2">
                        <div class="card-header tx-medium bd-0 tx-white bg-success">
                            PROFIT AFTER TAX (MYR)
                        </div><!-- card-header -->
                        <div class="card-body bd bd-t-0">
                            <canvas id="myChart4"></canvas>
                            <script>
                                const ctx4 = document.getElementById('myChart4').getContext('2d');
                                const myChart4 = new Chart(ctx4, {
                                    type: 'bar',
                                    data: {
                                        labels: <?php echo json_encode($pat_year);?>,
                                        datasets: [{
                                            label: 'MYR',

                                            data: <?php echo json_encode($pat_amount);?>,
                                            backgroundColor: [
                                            'rgba(254, 237, 206, 1)',
                                            'rgba(254, 237, 206, 1)',
                                            'rgba(254, 237, 206, 1)',
                                            'rgba(253, 228, 182, 1)',
                                            'rgba(253, 220, 157, 1)',
                                            'rgba(252, 211, 133, 1)',
                                            'rgba(0, 80, 160, 1)'
                                            ],
                                            borderColor: [
                                            'rgba(254, 237, 206, 1)',
                                            'rgba(254, 237, 206, 1)',
                                            'rgba(254, 237, 206, 1)',
                                            'rgba(253, 228, 182, 1)',
                                            'rgba(253, 220, 157, 1)',
                                            'rgba(252, 211, 133, 1)',
                                            'rgba(0, 80, 160, 1)'
                                            ],
                                            borderWidth: 1,
                                            order: 1,
                                            tension:0.3,
                                            datalabels : {
                                                formatter: function intToString (value) {
                                                    var suffixes = ["", "K", "M", "B","T"];
                                                    var suffixNum = Math.floor((""+value).length/4);
                                                    var shortValue = parseFloat((suffixNum != 0 ? (value / Math.pow(1000,suffixNum)) : value).toPrecision(3));
                                                    if (shortValue % 1 != 0) {
                                                        shortValue = shortValue.toFixed(1);
                                                    }
                                                    return shortValue+suffixes[suffixNum];
                                                },
                                                anchor: 'end',
                                                align: 'top',
                                                offset: 5
                                            }
                                        }]

                                    },

                                    plugins: [ChartDataLabels],
                                    options: {
                                        plugins: {
                                            legend: {
                                                display: false
                                            },
                                        },
                                        scales: {
                                            x: {
                                                ticks: {
                                                    display: true
                                                },
                                                gridLines:{
                                                    display: false
                                                }
                                            },
                                            y: {
                                                ticks: {
                                                    count: 5,
                                                    callback: function(value) {
                                                        var ranges = [
                                                        { divider: 1e6, suffix: 'M' },
                                                        { divider: 1e3, suffix: 'K' }
                                                        ];
                                                        function formatNumber(n) {
                                                            for (var i = 0; i < ranges.length; i++) {
                                                                if (n >= ranges[i].divider) {
                                                                    return (n / ranges[i].divider).toString() + ranges[i].suffix;
                                                                }
                                                                if (n <= ranges[i].divider) {
                                                                    return (n / ranges[i].divider).toString() + ranges[i].suffix;
                                                                }
                                                            }
                                                            return n;
                                                        }
                                                        return formatNumber(value);
                                                    }
                                                },
                                                gridLines:{
                                                    display:false
                                                }
                                            }
                                        },
                                        
                                    }

                                });


                            </script>
                        </div><!-- card-body -->
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card bd-0 mb-2">
                        <div class="card-header tx-medium bd-0 tx-white bg-success">
                            EBITDA (MYR)
                        </div><!-- card-header -->
                        <div class="card-body bd bd-t-0">
                            <canvas id="myChart5"></canvas>
                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                            <script>
                                const ctx5 = document.getElementById('myChart5').getContext('2d');
                                const myChart5 = new Chart(ctx5, {
                                    type: 'bar',
                                    data: {
                                        labels: <?php echo json_encode($ebitda_year);?>,
                                        datasets: [{
                                            label: 'MYR',

                                            
                                            data: <?php echo json_encode($ebitda_amount);?>,
                                            backgroundColor: [
                                            'rgba(228, 216, 181, 1)',
                                            'rgba(228, 216, 181, 1)',
                                            'rgba(228, 216, 181, 1)',
                                            'rgba(215, 196, 143, 1)',
                                            'rgba(201, 177, 106, 1)',
                                            'rgba(188, 157, 69, 1)',
                                            'rgba(0, 80, 160, 1)'
                                            ],
                                            borderColor: [
                                            'rgba(228, 216, 181, 1)',
                                            'rgba(228, 216, 181, 1)',
                                            'rgba(228, 216, 181, 1)',
                                            'rgba(215, 196, 143, 1)',
                                            'rgba(201, 177, 106, 1)',
                                            'rgba(188, 157, 69, 1)',
                                            'rgba(0, 80, 160, 1)'
                                            ],
                                            borderWidth: 1,
                                            order: 1,
                                            tension:0.3,
                                            datalabels : {
                                                formatter: function intToString (value) {
                                                    var suffixes = ["", "K", "M", "B","T"];
                                                    var suffixNum = Math.floor((""+value).length/4);
                                                    var shortValue = parseFloat((suffixNum != 0 ? (value / Math.pow(1000,suffixNum)) : value).toPrecision(3));
                                                    if (shortValue % 1 != 0) {
                                                        shortValue = shortValue.toFixed(1);
                                                    }
                                                    return shortValue+suffixes[suffixNum];
                                                },
                                                anchor: 'end',
                                                align: 'top',
                                                offset: 5
                                            }
                                        }]

                                    },

                                    plugins: [ChartDataLabels],
                                    options: {
                                        plugins: {
                                            legend: {
                                                display: false
                                            },
                                        },
                                        scales: {
                                            x: {
                                                ticks: {
                                                    display: true
                                                },
                                                gridLines:{
                                                    display: false
                                                }
                                            },
                                            y: {
                                                ticks: {
                                                    count: 5, 
                                                    callback: function(value) {
                                                        var ranges = [
                                                        { divider: 1e6, suffix: 'M' },
                                                        { divider: 1e3, suffix: 'K' }
                                                        ];
                                                        function formatNumber(n) {
                                                            for (var i = 0; i < ranges.length; i++) {
                                                                if (n >= ranges[i].divider) {
                                                                    return (n / ranges[i].divider).toString() + ranges[i].suffix;
                                                                }
                                                                if (n <= ranges[i].divider) {
                                                                    return (n / ranges[i].divider).toString() + ranges[i].suffix;
                                                                }
                                                            }
                                                            return n;
                                                        }
                                                        return formatNumber(value);
                                                    }
                                                },
                                                gridLines:{
                                                    display:false
                                                }
                                            }
                                        },
                                        
                                    }
                                    
                                });
                            </script>
                        </div><!-- card-body -->
                    </div>
                </div>

                </div>
            </div>
            </div>

        </div>

        <div class="col-md-4">
            
            <div class="card bd-0">
                <div class="card-header tx-medium bd-0 tx-white bg-primary tx-center">
                    OUR ACTIVITIES
                </div><!-- card-header -->
            </div>

            <div class="row row-xs mt-2">
                <div class="col-md-12">

                    <div class="card bd-0 mb-5">
                        <div class="card-header tx-medium bd-0 tx-white bg-success" style="text-transform: uppercase;">
                            {{ $actvt_fact[0] }}
                        </div><!-- card-header -->
                        <div class="card-body bd bd-t-0">
                            <p class="blue">in % as of 2020 {{ $actvt_fact[0] }}</p>
                            <table class="table table-striped mg-b-0 text-center">
                              <tbody>
                                @foreach ($actvt_fact_value as $index => $actvt_item)
                                <tr>
                                  <td width="65%">{{ $actvt_item }}</td>
                                  <td><strong>{{ $actvt_fact_value_2[$index] }}</strong></td>
                                </tr>
                                @endforeach
                              </tbody>
                            </table>

                            <!--canvas id="myChart6"></canvas>

                            <script>
                            const ctx6 = document.getElementById('myChart6').getContext('2d');
                            const myChart6 = new Chart(ctx6, {
                            type: 'doughnut',
                            data: {
                                labels: <?php echo json_encode($actvt_fact_value);?>,
                                datasets: [{
                                    label: 'Location',
                                    
                                    data: <?php echo json_encode(str_replace('%', '', $actvt_fact_value_2));?>,
                                    options: {
                                        responsive: true,
                                        legend: {
                                            position: 'bottom',
                                            labels: {
                                                fontColor: "white",
                                                boxWidth: 20,
                                                padding: 20
                                            }
                                        }
                                    },
                                    backgroundColor: [
                                        'rgba(0, 80, 160, 1)',
                                        'rgba(188, 157, 69, 1)',
                                        'rgba(225, 150, 170, 1)',
                                        'rgba(145, 65, 70, 1)',
                                        'rgba(2, 113, 128, 1)'
                                    ],
                                    borderColor: [
                                        'rgba(0, 80, 160, 1)',
                                        'rgba(188, 157, 69, 1)',
                                        'rgba(225, 150, 170, 1)',
                                        'rgba(145, 65, 70, 1)',
                                        'rgba(2, 113, 128, 1)'
                                    ],
                                    borderWidth: 1
                                }]
                                
                            }
                            });
                            </script-->

                        </div>
                    </div>

                </div>

                <div class="col-md-12 mt-5">

                    <div class="card bd-0 mb-2">
                        <div class="card-header tx-medium bd-0 tx-white bg-success" style="text-transform: uppercase;">
                            {{ $actvt2_fact[0] }}
                        </div><!-- card-header -->
                        <div class="card-body bd bd-t-0">
                            <p class="blue">in % as of 2020 {{ $actvt2_fact[0] }}</p>
                            <table class="table table-striped mg-b-0 text-center">
                              <tbody>
                                @foreach ($actvt2_fact_value as $index => $actvt_item)
                                <tr>
                                  <td width="65%">{{ $actvt_item }}</td>
                                  <td><strong>{{ $actvt2_fact_value_2[$index] }}</strong></td>
                                </tr>
                                @endforeach
                              </tbody>
                            </table>

                            <!--canvas id="myChart7"></canvas>
                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                            <script>
                            const ctx7 = document.getElementById('myChart7').getContext('2d');
                            const myChart7 = new Chart(ctx7, {
                            type: 'doughnut',
                            data: {
                                labels: <?php echo json_encode($actvt2_fact_value);?>,
                                datasets: [{
                                    label: 'Location',
                                    
                                    data: <?php echo json_encode(str_replace('%', '', $actvt2_fact_value_2));?>,
                                    options: {
                                        responsive: true,
                                        legend: {
                                            position: 'bottom',
                                            labels: {
                                                fontColor: "white",
                                                boxWidth: 20,
                                                padding: 20
                                            }
                                        }
                                    },
                                    backgroundColor: [
                                        'rgba(0, 80, 160, 1)',
                                        'rgba(188, 157, 69, 1)',
                                        'rgba(225, 150, 170, 1)',
                                        'rgba(145, 65, 70, 1)',
                                        'rgba(2, 113, 128, 1)'
                                    ],
                                    borderColor: [
                                        'rgba(0, 80, 160, 1)',
                                        'rgba(188, 157, 69, 1)',
                                        'rgba(225, 150, 170, 1)',
                                        'rgba(145, 65, 70, 1)',
                                        'rgba(2, 113, 128, 1)'
                                    ],
                                    borderWidth: 1
                                }]
                                
                            }
                            });
                            </script-->
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="row mt-4">

        <div class="col-md-8 mb-4">

            <div class="card bd-0">
                <div class="card-header tx-medium bd-0 tx-white bg-primary tx-center">
                    KEY FACTS
                </div><!-- card-header -->
            </div>

            <div class="row row-xs mt-2">
                <div class="col-md-6">
                    <table class="table table-striped mg-b-0">
                      <tbody>
                        @foreach($keyfact_fact as $index => $item_fact)
                        <tr>
                          <td><strong>{{ $item_fact }}</strong></td>
                          <td width="75%">{{ str_replace('||', ', ', $keyfact_fact_value[$index]) }}<br/></td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                </div>

                <div class="col-md-6">
                    <table class="table table-striped mg-b-0">
                      <thead>
                        <tr>
                          <th colspan="2">Management</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($management_fact as $index => $mgmt_fact)
                        <tr>
                          <td><strong>{{ $mgmt_fact }}</strong></td>
                          <td width="75%">{{ $management_fact_value[$index] }}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>

                    <table class="table table-striped mg-b-0">
                      <thead>
                        <tr>
                          <th colspan="2">Achievements</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($achievements_fact as $index => $achievements_fact)
                        <tr>
                          <td><strong>{{ $achievements_fact }}</strong></td>
                          <td width="75%">
                            {{ $achievements_fact_value[$index] }}
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">

            <div class="card bd-0">
                <div class="card-header tx-medium bd-0 tx-white bg-primary tx-center">
                    SOUND BITES
                </div><!-- card-header -->
            </div>

            <div class="row row-xs mt-2">
                <!--@foreach($sound_fact as $index => $item_fact)
                <div class="col-md-4 mb-2 text-center">
                    <div class="card bd-0">
                        <div class="card-header tx-medium bd-0 tx-white bg-success">
                            {{ $item_fact }}
                        </div>card-header
                        <div class="card-body bd bd-t-0">
                            <h2>{{ floor($sound_fact_value[$index]) }}</h2>
                        </div>
                    </div>
                </div>
                @endforeach
                -->
                <div class="col-md-12 mb-4">
                    <table class="table table-striped mg-b-0">
                      <tbody class="text-center">
                        @foreach($sound_fact as $index => $item_fact)
                        <tr>
                          <td width="75%">{{ $item_fact }}</td>
                          <td>
                            <strong>{{ floor($sound_fact_value[$index]) }}</strong>

                            <script type="text/javascript">
                                function numberWithCommas(x) {
                                    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                }
                            </script>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    </div><!-- container -->
</div><!-- slim-mainpanel -->

@endsection