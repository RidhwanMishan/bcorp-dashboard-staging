<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Berjaya Corp Admin Dashboard - @yield('title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
    <meta name="description" content=".">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('images/berjaya-icon.png') }}">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">


    <!--Chartist.js-->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
    <script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js">

    @if(isset($userName))
    <style>

    </style>
    @endif

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.2.1/dist/alpine.js" defer></script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0-rc.1/chartjs-plugin-datalabels.min.js"></script>

    <!-- vendor css -->
    <link href="{{ asset('lib/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/Ionicons/css/ionicons.css') }}" rel="stylesheet">

    <!-- Slim CSS -->
    <link href="{{ asset('css/slim.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>

@if(isset($userName))
<body class="relative"  onload="startTime()">

    @if(isset($userName))
    <style>

    </style>
    @endif

    <body>
        <div id="slim-header" class="slim-header">
        <div class="container">
            <div class="slim-header-left">
            <h2 class="slim-logo"><a href="index.html"><img src="{{ asset('images/logo.svg') }}" class="" style="height:50px;" alt=""></a></h2>
            </div><!-- slim-header-left -->
   
            <div class="slim-navbar">
            <div class="container">
                <ul class="nav">
                @if($berjaya_access)
                <li class="nav-item @isset($berjaya) active @endisset">
                    <a class="nav-link" href="/berjaya">
                    <span>Berjaya Group</span>
                    </a>
                </li>
                @endif
                @if($hospitality_access)
                <li class="nav-item @isset($hospitality) active @endisset">
                    <a class="nav-link" href="/hospitality">
                    <span>Hospitality</span>
                    </a>
                </li>
                @endif
                @if($property_access)
                <li class="nav-item @isset($property) active @endisset">
                    <a class="nav-link" href="/property">
                    <span>Property</span>
                    </a>
                </li>
                @endif
                @if($retail_access)
                <li class="nav-item @isset($retail) active @endisset">
                    <a class="nav-link" href="/retail">
                        <span>Retail</span>
                    </a>
                </li>
                @endif
                @if($services_access)
                <li class="nav-item @isset($services) active @endisset">
                    <a class="nav-link" href="/services">
                    <span>Services</span>
                    </a>
                </li>
                @endif
                <li class="nav-item with-sub mega-dropdown @isset($factsheet) active @endisset">
                    <a class="nav-link" href="#">
                        <span>Factsheet</span>
                    </a>
                    <div class="sub-item">
                    <ul>
                        <li><a href="#">Berjaya Corporation</a></li>
                        <label class="section-label">Services</label>
                            <li><a href="/factsheet/Berjaya Enviro Holdings Sdn Bhd">Berjaya Enviro Holdings Sdn Bhd</a></li>
                            <li><a href="/factsheet/Berjaya EnviroParks Sdn Bhd">Berjaya EnviroParks Sdn Bhd</a></li>
                            <li><a href="/factsheet/Natural Intelligence Solutions Pte Ltd">Natural Intelligence Solutions Pte Ltd</a></li>
                        <label class="section-label">Retail</label>
                            <li><a href="/factsheet/Cosway HK Limited">Coway HK Limited</a></li>
                            <li><a href="/factsheet/Cosway (M) Sdn Bhd">Coway (M) Sdn Bhd</a></li>
                            <li><a href="/factsheet/Cosway Taiwan Branch">Coway Taiwan Branch</a></li>
                    </ul>
                    </div>
                </li>
                </ul>
            </div><!-- container -->
            </div><!-- slim-navbar -->
                
                <!--img src="{{ asset('images/!logged-user.jpg') }}" alt=""-->
                <div class="dropdown">
                <button class="dropbtn">{{session('name')}}</button>
                <div class="dropdown-content">
                <a href="/faq">FAQ</a>
                <a href="/signout">Log Out</a>
                </div>
                </div>
            </div>
            </div> 
                
                </a>

              <!-- <div class="dropdown-menu dropdown-menu-right"> -->
                
                
            </div><!-- header-right -->
        </div><!-- container -->
        </div><!-- slim-header -->
        
        <div class="container">
            <div class="current-date">
                <span class="">Data updated as of:</span> <span id="txt">{{$update_date}}</span>
            </div>
        </div>

    <!-- @include('partials.alert') -->
    @yield('content')

    <div class="slim-footer">
      <div class="container">
        <p>Copyright 2021 &copy; All Rights Reserved.</p>
        <p><a href="">Natural Intelligence Solutions Technology Sdn Bhd</a></p>
      </div><!-- container -->
    </div><!-- slim-footer -->

    <script src="{{ asset('lib/jquery/js/jquery.js') }}" ></script>
    <script src="{{ asset('lib/bootstrap/js/bootstrap.js') }}" ></script>
    <script src="{{ asset('lib/jquery.cookie/js/jquery.cookie.js') }}" ></script>
    <script src="{{ asset('lib/d3/js/d3.js') }}" ></script>
    <script src="{{ asset('lib/rickshaw/js/rickshaw.min.js') }}" ></script>
    <script src="{{ asset('js/Chart.bundle.min.js') }}" ></script>

    <script src="{{ asset('js/slim.js') }}" ></script>
    <script src="{{ asset('js/ResizeSensor.js') }}" ></script>

    <!-- START DATA AND FUNCTIONS -->
    <script src="{{ asset('js/BDashboard.js') }}" ></script>
    <!-- END DATA AND FUNCTIONS -->

    <script>
        // When the user scrolls the page, execute myFunction
        window.onscroll = function() {myFunction()};

        // Get the header
        var header = document.getElementById("slim-header");

        // Get the offset position of the navbar
        var sticky = header.offsetTop;

        // Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
        function myFunction() {
        if (window.pageYOffset > sticky) {
            header.classList.add("sticky");
        } else {
            header.classList.remove("sticky");
        }
        }
    </script>

@else

<body>
    @include('partials.alert')
    @yield('content')

    @endif
</body>
</html>