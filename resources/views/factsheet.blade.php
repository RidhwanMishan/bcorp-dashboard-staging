@extends('retail.index')

@section('title') Factsheet @endsection

@section('content')

@php
$name = "Berjaya Enviro Holdings Sdn Bhd"; //Can change depending on what company
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
                            GROSS REVENUE
                        </div><!-- card-header -->
                        <div class="card-body bd bd-t-0">
                            <canvas id="myChart"></canvas>
                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                            <script>
                                const ctx = document.getElementById('myChart').getContext('2d');
                                const myChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: ,
                                        datasets: [{
                                            label: 'MYR',

                                            
                                            data: ,
                                            backgroundColor: [
                                                'rgba(243, 213, 221,1)',
                                                'rgba(237, 192, 204, 1)',
                                                'rgba(231, 171, 187, 1)',
                                                'rgba(225, 150, 170, 1)',
                                                'rgba(0, 80, 160, 1)'
                                            ],
                                            borderColor: [
                                                'rgba(243, 213, 221, 1)',
                                                'rgba(237, 192, 204, 1)',
                                                'rgba(231, 171, 187, 1)',
                                                'rgba(225, 150, 170, 1)',
                                                'rgba(0, 80, 160, 1)'
                                            ],
                                            borderWidth: 1,
                                            order: 1,
                                            tension:0.3
                                        }]
                                    
                                    },

                                    options: {
                                        plugins: {
                                            legend: {
                                                display: false
                                            },
                                        },
                                        scales: {
                                            x: {
                                            ticks: {
                                                display: false
                                            },
                                            grid:{
                                                display:false
                                            }
                                        },
                                        y: {
                                            ticks: {
                                                display: false
                                            },
                                            grid:{
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
                        COST OF SALE
                        </div><!-- card-header -->
                        <div class="card-body bd bd-t-0">
                            <canvas id="myChart1"></canvas>
                            <script>
                            const ctx1 = document.getElementById('myChart1').getContext('2d');
                            const myChart1 = new Chart(ctx1, {
                                type: 'bar',
                                data: {
                                    labels: ['2017', '2018', '2019', '2020', '2021'],
                                    datasets: [{
                                        label: 'MYR',
                                        
                                        data: [5.7, 6.0, 6.2, 6.5, 4.3],
                                        backgroundColor: [
                                            'rgba(225, 236, 246, 1)',
                                            'rgba(211, 227, 245, 1)',
                                            'rgba(196, 218, 241, 1)',
                                            'rgba(181, 208, 238, 1)',
                                            'rgba(0, 80, 160, 1)'
                                        ],
                                        borderColor: [
                                            'rgba(225, 236, 246, 1)',
                                            'rgba(211, 227, 245, 1)',
                                            'rgba(196, 218, 241, 1)',
                                            'rgba(181, 208, 238, 1)',
                                            'rgba(0, 80, 160, 1)'
                                        ],
                                        borderWidth: 1,
                                        order: 1,
                                        tension:0.3
                                    }]
                                    
                                },

                                options: {
                                        plugins: {
                                            legend: {
                                                display: false
                                            },
                                        },
                                        scales: {
                                            x: {
                                            ticks: {
                                                display: false
                                            },
                                            grid:{
                                                display:false
                                            }
                                        },
                                        y: {
                                            ticks: {
                                                display: false
                                            },
                                            grid:{
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
                            GROSS PROFIT / LOSS
                        </div><!-- card-header -->
                        <div class="card-body bd bd-t-0">
                            <canvas id="myChart2"></canvas>
                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                            <script>
                                const ctx2 = document.getElementById('myChart2').getContext('2d');
                                const myChart2 = new Chart(ctx2, {
                                    type: 'bar',
                                    data: {
                                        labels: ['2017', '2018', '2019', '2020', '2021'],
                                        datasets: [{
                                            label: '%',
                                            
                                            data: [197, 205, 193, 198, 200],
                                            backgroundColor: [
                                            'rgba(216, 240, 225, 1)',
                                            'rgba(197, 232, 210, 1)',
                                            'rgba(178, 225, 195, 1)',
                                            'rgba(159, 217, 180, 1)',
                                            'rgba(0, 80, 160, 1)'
                                        ],
                                        borderColor: [
                                            'rgba(216, 240, 225, 1)',
                                            'rgba(197, 232, 210, 1)',
                                            'rgba(178, 225, 195, 1)',
                                            'rgba(159, 217, 180, 1)',
                                            'rgba(0, 80, 160, 1)'
                                        ],
                                            borderWidth: 1,
                                            order: 1,
                                            tension:0.3
                                        }]
                                    
                                    },

                                    options: {
                                        plugins: {
                                            legend: {
                                                display: false
                                            },
                                        },
                                        scales: {
                                            x: {
                                            ticks: {
                                                display: false
                                            },
                                            grid:{
                                                display:false
                                            }
                                        },
                                        y: {
                                            ticks: {
                                                display: false
                                            },
                                            grid:{
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
                            OPERATING EXPENSE
                        </div><!-- card-header -->
                        <div class="card-body bd bd-t-0">
                            <canvas id="myChart3"></canvas>
                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                            <script>
                                const ctx3 = document.getElementById('myChart3').getContext('2d');
                                const myChart3 = new Chart(ctx3, {
                                    type: 'bar',
                                    data: {
                                        labels: ['2017', '2018', '2019', '2020', '2021'],
                                        datasets: [{
                                            label: 'MYR',

                                            
                                            data: [70.6, 69.6, 62.4, 69.9, 71.6],
                                            backgroundColor: [
                                                'rgba(154, 198, 204, 1)',
                                                'rgba(103, 170, 179, 1)',
                                                'rgba(53, 142, 154, 1)',
                                                'rgba(2, 113, 128, 1)',
                                                'rgba(0, 80, 160, 1)'
                                            ],
                                            borderColor: [
                                                'rgba(154, 198, 204, 1)',
                                                'rgba(103, 170, 179, 1)',
                                                'rgba(53, 142, 154, 1)',
                                                'rgba(2, 113, 128, 1)',
                                                'rgba(0, 80, 160, 1)'
                                            ],
                                            borderWidth: 1,
                                            order: 1,
                                            tension:0.3
                                        }]
                                    
                                    },

                                    options: {
                                        plugins: {
                                            legend: {
                                                display: false
                                            },
                                        },
                                        scales: {
                                            x: {
                                            ticks: {
                                                display: false
                                            },
                                            grid:{
                                                display:false
                                            }
                                        },
                                        y: {
                                            ticks: {
                                                display: false
                                            },
                                            grid:{
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
                            PROFIT AFTER TAX
                        </div><!-- card-header -->
                        <div class="card-body bd bd-t-0">
                            <canvas id="myChart4"></canvas>
                            <script>
                            const ctx4 = document.getElementById('myChart4').getContext('2d');
                            const myChart4 = new Chart(ctx4, {
                                type: 'bar',
                                data: {
                                    labels: ['2017', '2018', '2019', '2020', '2021'],
                                    datasets: [{
                                        label: 'MYR',
                                        
                                        data: [1.16, 1.26, 1.34, 0.73, 1.43],
                                        backgroundColor: [
                                            'rgba(254, 237, 206, 1)',
                                            'rgba(253, 228, 182, 1)',
                                            'rgba(253, 220, 157, 1)',
                                            'rgba(252, 211, 133, 1)',
                                            'rgba(0, 80, 160, 1)'
                                        ],
                                        borderColor: [
                                            'rgba(254, 237, 206, 1)',
                                            'rgba(253, 228, 182, 1)',
                                            'rgba(253, 220, 157, 1)',
                                            'rgba(252, 211, 133, 1)',
                                            'rgba(0, 80, 160, 1)'
                                        ],
                                        borderWidth: 1,
                                        order: 1,
                                        tension:0.3
                                    }]
                                    
                                },

                                options: {
                                        plugins: {
                                            legend: {
                                                display: false
                                            },
                                        },
                                        scales: {
                                            x: {
                                            ticks: {
                                                display: false
                                            },
                                            grid:{
                                                display:false
                                            }
                                        },
                                        y: {
                                            ticks: {
                                                display: false
                                            },
                                            grid:{
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
                            EBITDA
                        </div><!-- card-header -->
                        <div class="card-body bd bd-t-0">
                            <canvas id="myChart5"></canvas>
                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                            <script>
                                const ctx5 = document.getElementById('myChart5').getContext('2d');
                                const myChart5 = new Chart(ctx5, {
                                    type: 'bar',
                                    data: {
                                        labels: ['2017', '2018', '2019', '2020', '2021'],
                                        datasets: [{
                                            label: 'MYR',

                                            
                                            data: [58.1, 60.0, 45.7, 60.7, 47.3],
                                            backgroundColor: [
                                                'rgba(228, 216, 181, 1)',
                                                'rgba(215, 196, 143, 1)',
                                                'rgba(201, 177, 106, 1)',
                                                'rgba(188, 157, 69, 1)',
                                                'rgba(0, 80, 160, 1)'
                                            ],
                                            borderColor: [
                                                'rgba(228, 216, 181, 1)',
                                                'rgba(215, 196, 143, 1)',
                                                'rgba(201, 177, 106, 1)',
                                                'rgba(188, 157, 69, 1)',
                                                'rgba(0, 80, 160, 1)'
                                            ],
                                            borderWidth: 1,
                                            order: 1,
                                            tension:0.3
                                        }]
                                    
                                    },

                                    options: {
                                        plugins: {
                                            legend: {
                                                display: false
                                            },
                                        },
                                        scales: {
                                            x: {
                                            ticks: {
                                                display: false
                                            },
                                            grid:{
                                                display:false
                                            }
                                        },
                                        y: {
                                            ticks: {
                                                display: false
                                            },
                                            grid:{
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

            <div class="row row-xs mt-4">
                <div class="col-md-12">
                    <div class="text-center blue">
                        <h4>GEOGRAPHY</h4>
                        <p><i>in % as of 2020 total gross revenues</i></p>
                    </div>
                </div>
                <div class="col-md-12 mb-4">

                    <canvas id="myChart6"></canvas>
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                    <script>
                    const ctx6 = document.getElementById('myChart6').getContext('2d');
                    const myChart6 = new Chart(ctx6, {
                    type: 'doughnut',
                    data: {
                        labels: ['Klang Valley', 'Johor', 'Penang', 'Sarawak', 'Pahang'],
                        datasets: [{
                            label: 'Location',
                            
                            data: [35, 28, 14, 16, 7],
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
                    </script>
                </div>

                <div class="col-md-12 mt-4">
                    <div class="text-center blue">
                        <h4>BUSINESS</h4>
                        <p><i>in % as of 2020 rebased underlying earnings* excl. Holdings</i></p>
                    </div>
                </div>
                <div class="col-md-12">
                    <canvas id="myChart7"></canvas>
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    
                    <script>
                    const ctx7 = document.getElementById('myChart7').getContext('2d');
                    const myChart7 = new Chart(ctx7, {
                        type: 'doughnut',
                        data: {
                            labels: ['Drinks', 'Food', 'Merchandise', 'Others'],
                            datasets: [{
                                label: 'Business',
                                
                                data: [52, 33, 11, 4],
                                backgroundColor: [
                                    'rgba(0, 80, 160, 1)',
                                    'rgba(188, 157, 69, 1)',
                                    'rgba(225, 150, 170, 1)',
                                    'rgba(145, 65, 70, 1)'
                                ],
                                borderColor: [
                                    'rgba(0, 80, 160, 1)',
                                    'rgba(188, 157, 69, 1)',
                                    'rgba(225, 150, 170, 1)',
                                    'rgba(145, 65, 70, 1)'
                                ],
                                borderWidth: 1
                            }]
                        
                        }
                    });
                    </script>
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
                    <ul class="list-group list-group-striped">
                        <li class="list-group-item">
                            <p class="mg-b-0"><span class="text-muted">Founded:</span> <strong class="tx-inverse tx-medium">1998</strong></p>
                        </li>
                        <li class="list-group-item">
                            <p class="mg-b-0"><span class="text-muted">HQ:</span> <strong class="tx-inverse tx-medium">Lot 10-04, Level 10, Berjaya Times Square, No.1, Jalan Imbi, 55100 Kuala Lumpur</strong></p>
                        </li>
                        <li class="list-group-item">
                            <p class="mg-b-0"><span class="text-muted">Type of Business:</span> <strong class="tx-inverse tx-medium">Food & Beverage</strong></p>
                        </li>
                        <li class="list-group-item">
                            <p class="mg-b-0"><span class="text-muted">Stakeholder Structure:</span> <strong class="tx-inverse tx-medium">100% Ownership under Berjaya Food Berhad</strong></p>
                        </li>
                        <li class="list-group-item">
                            <p class="mg-b-0"><span class="text-muted">Current plans:</span></p>
                            <ul>
                                <li><strong class="tx-inverse tx-medium">Opening another 8 outlets in Q4 2021</strong></li>
                                <li><strong class="tx-inverse tx-medium">Introduction of New plant-based menu</strong></li>
                            </ul>
                        </li>
                        <li class="list-group-item">
                            <p class="mg-b-0"><span class="text-muted">Future plans:</span></p<>
                            <ul>
                                <li><strong class="tx-inverse tx-medium">Opening further 35-50 outlets in FY2022</strong></li>
                            </ul>
                        </li>
                    </ul>
                </div>

                <div class="col-md-6">
                    <ul class="list-group list-group-striped">
                        <li class="list-group-item">
                            <p class="mg-b-0"><span class="text-muted">Key Personnel:</span><br/>
                            <strong class="tx-inverse tx-medium">Person #1:</strong><br/>
                            <strong class="tx-inverse tx-medium">Person #2:</strong><br/>
                            <strong class="tx-inverse tx-medium">Person #3:</strong></p>
                        </li>
                        <li class="list-group-item">
                            <p class="mg-b-0"><span class="text-muted">Achievements:</span></p>
                            <ul>
                                <li><strong class="tx-inverse tx-medium">Asia Responsible Enterprise Awards 2018 (AREA 2018)</strong>
                                <li><strong class="tx-inverse tx-medium">‘Top Companies to Work for in Asia’ at the Asia Corporate Excellence and Sustainability awards (2017)</strong>
                                <li><strong class="tx-inverse tx-medium">World’s first Starbucks Signing Store in Bangsar Village II, a store which aims to cultivate a culture of empowerment for our deaf and hard-of-hearing partners.</strong></li>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4">

            <div class="card bd-0">
                <div class="card-header tx-medium bd-0 tx-white bg-primary tx-center">
                    SOUND BITES
                </div><!-- card-header -->
            </div>

            <div class="row row-xs mt-4">
                <div class="col-md-12">
                    <ul class="cloud" role="navigation" aria-label="Webdev tag cloud">
                        <li><a data-weight="9">18 million</a></li>
                        <li><a data-weight="3">Cups of coffee sold annually</a></li>
                        <li><a data-weight="4">Stores: Reserved&trade;, Signing, Drive-thru</a></li>
                        <li><a data-weight="9">32</a></li>
                        <li><a data-weight="6">Our employees </a></li>
                        <li><a data-weight="9">88,888 </a></li>
                        <li><a data-weight="8">80,0000</a></li>
                        <li><a data-weight="5">community hours service</a></li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    </div><!-- container -->
</div><!-- slim-mainpanel -->

@endsection