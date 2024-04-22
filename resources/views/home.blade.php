@extends('layouts.app')

{{--@section('content')--}}
{{--    <div class="flex items-center">--}}
{{--        <div class="md:w-1/2 md:mx-auto">--}}

{{--            @if (session('status'))--}}
{{--                <div class="text-sm border border-t-8 rounded text-green-700 border-green-600 bg-green-100 px-3 py-4 mb-4" role="alert">--}}
{{--                    {{ session('status') }}--}}
{{--                </div>--}}
{{--            @endif--}}

{{--            <div class="flex flex-col break-words bg-white border border-2 rounded shadow-md">--}}

{{--                <div class="font-semibold bg-gray-200 text-gray-700 py-3 px-6 mb-0">--}}
{{--                    Dashboard--}}
{{--                </div>--}}

{{--                <div class="w-full p-6">--}}
{{--                    <p class="text-gray-700">--}}
{{--                        You are logged in!--}}
{{--                    </p>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}

@section('content')
    <div class="-mt-32 bg-transparent">
        <div class="h-full pt-8 xxxl:pt-20  pb-12 mx-auto bg-transparent">


            <div class="mx-auto px-2 sm:px-1 lg:px-2">
                <div class="hidden xxlll:hidden mt-8 md:flex md:justify-around antialiased text-gray-900 overflow-hidden">
                    <a href="/sales/overview">
                        <div class="xl:mt-12 xxl:mt-0  md:inline-flex items-center justify-center xl:h-56 xl:w-56 xsl:h-64 xsl:w-64 xxl:h-72 xxl:w-72 xxxl:h-96 xxxl:w-96 xxll:h-120 xxll:w-120 relative">
                            <img class="absolute" src="{{ asset('images/C1.png') }}" alt="ChromeCircle">
                            <div class="absolute">
                                <div class="p-4 justify-center text-center">
                                    <div class="text-red-700 xl:text-2xl xxl:text-3xl xxxl:text-4xl xxll:text-5xl uppercase font-semibold justify-center">
                                        Sales
                                    </div>
                                    <div class="text-gray-600 xl:text-md xxl:text-lg xxxl:text-xl xxll:text-2xl justify-center xl:pt-1 xxl:pt-2">
                                        MTD Sales
                                    </div>
                                    <div class="text-gray-700 md:text-lg xl:text-2xl xxl:text-3xl xxxl:text-4xl xxll:text-5xl xl:font-medium xxl:font-semibold justify-center xl:pt-1 xxl:pt-2">
                                        3,110
                                    </div>
                                    <div class="text-gray-600 xl:text-md xxl:text-lg xxxl:text-xl xxll:text-2xl justify-center xl:pt-1 xxl:pt-2">
                                        (MT)
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="/people/overview">
                        <div class="xl:mt-12 xxl:mt-0  md:inline-flex items-center justify-center xl:h-56 xl:w-56 xsl:h-64 xsl:w-64 xxl:h-72 xxl:w-72 xxxl:h-96 xxxl:w-96 xxll:h-120 xxll:w-120 relative">
                            <img class="absolute" src="{{ asset('images/C1.png') }}" alt="ChromeCircle">
                            <div class="absolute">
                                <div class="p-4 justify-center text-center">
                                    <div class="text-red-700 xl:text-2xl xxl:text-3xl xxxl:text-4xl xxll:text-5xl uppercase font-semibold justify-center">
                                        People
                                    </div>
                                    <div class="text-gray-600 xl:text-md xxl:text-lg xxxl:text-xl xxll:text-2xl justify-center xl:pt-1 xxl:pt-2">
                                        Daily Late Employees
                                    </div>
                                    <div class="text-gray-700 md:text-lg xl:text-2xl xxl:text-3xl xxxl:text-4xl xxll:text-5xl xl:font-medium xxl:font-semibold justify-center xl:pt-1 xxl:pt-2">
                                    </div>
                                    <div class="text-white select-none xl:text-md xxl:text-lg xxxl:text-xl xxll:text-2xl justify-center xl:pt-1 xxl:pt-2">
                                        0
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="/finance/overview">
                        <div class="xl:mt-12 xxl:mt-0  md:inline-flex items-center justify-center xl:h-56 xl:w-56 xsl:h-64 xsl:w-64 xxl:h-72 xxl:w-72 xxxl:h-96 xxxl:w-96 xxll:h-120 xxll:w-120 relative">
                            <img class="absolute" src="{{ asset('images/C1.png') }}" alt="ChromeCircle">
                            <div class="absolute">
                                <div class="p-4 justify-center text-center">
                                    <div class="text-red-700 xl:text-2xl xxl:text-3xl xxxl:text-4xl xxll:text-5xl uppercase font-semibold justify-center">
                                        Finance
                                    </div>
                                    <div class="text-gray-600 xl:text-md xxl:text-lg xxxl:text-xl xxll:text-2xl justify-center xl:pt-1 xxl:pt-2">
                                        Total Due
                                    </div>
                                    <div class="text-gray-700 md:text-lg xl:text-2xl xxl:text-3xl xxxl:text-4xl xxll:text-5xl xl:font-medium xxl:font-semibold justify-center xl:pt-1 xxl:pt-2">
                                        350
                                    </div>
                                    <div class="text-gray-600 xl:text-md xxl:text-lg xxxl:text-xl xxll:text-2xl justify-center xl:pt-1 xxl:pt-2">
                                        MYR '000
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="#">
                        <div class="cursor-not-allowed xl:mt-12 xxl:mt-0  md:inline-flex items-center justify-center xl:h-56 xl:w-56 xsl:h-64 xsl:w-64 xxl:h-72 xxl:w-72 xxxl:h-96 xxxl:w-96 xxll:h-120 xxll:w-120 relative">
                            <img class="absolute" src="{{ asset('images/Circle-Grey.png') }}" alt="ChromeCircle">
                            <div class="absolute">
                            </div>
                        </div>
                    </a>
                    <a href="#">
                        <div class="cursor-not-allowed xl:mt-12 xxl:mt-0  md:inline-flex items-center justify-center xl:h-56 xl:w-56 xsl:h-64 xsl:w-64 xxl:h-72 xxl:w-72 xxxl:h-96 xxxl:w-96 xxll:h-120 xxll:w-120 relative">
                            <img class="absolute" src="{{ asset('images/Circle-Grey.png') }}" alt="ChromeCircle">
                            <div class="absolute">
                            </div>
                        </div>
                    </a>
                </div>
                <div class="hidden xxlll:hidden md:flex md:justify-around antialiased text-gray-900 overflow-hidden xl:px-2 xsl:px-6 xxl:px-8 xl:pb-4 xxl:pb-6 xl:pt-2">
                    <div class="md:inline-flex items-center justify-center xl:mr-16 xxl:mr-20 xl:w-56 lg:h-24 xsl:w-64 xxl:w-68  xxxl:w-72 xxxl:h-32 xxll:w-96 xxll:h-40 overflow-hidden shadow-2xl relative">
                        <img class="absolute" src="{{ asset('images/white-03(1).png') }}" alt="ChromeCircle">
                        <div class="absolute">
                            <div class="justify-center text-center">
                                <div class="text-gray-700 xl:text-lg xxl:text-xl xxxl:text-2xl xxll:text-3xl xl:font-medium xxl:font-semibold">
                                    Monthly target
                                </div>
                                <div class="text-red-500 xl:text-lg xxl:text-xl xxxl:text-2xl xxll:text-3xl xl:font-medium xxl:font-semibold">
                                    24 <span class="text-gray-600 font-normal">(MT)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="md:inline-flex items-center justify-center xl:ml-2 xxl:ml-0 xl:mr-16 xxl:mr-20 xl:w-56 lg:h-24 xsl:w-64 xxl:w-68  xxxl:w-72 xxxl:h-32 xxll:w-96 xxll:h-40 overflow-hidden shadow-2xl relative">
                        <img class="absolute" src="{{ asset('images/white-03(1).png') }}" alt="ChromeCircle">
                        <div class="absolute">
                            <div class="justify-center text-center">
                                <div class="text-gray-700 xl:text-lg xxl:text-xl xxxl:text-2xl xxll:text-3xl xl:font-medium xxl:font-semibold">
                                    Daily Late Hours
                                </div>
                                <div class="text-red-500 xl:text-lg xxl:text-xl xxxl:text-2xl xxll:text-3xl xl:font-medium xxl:font-semibold">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="md:inline-flex items-center justify-center xl:w-56 lg:h-24 xsl:w-64 xxl:w-68  xxxl:w-72 xxxl:h-32 xxll:w-96 xxll:h-40 overflow-hidden shadow-2xl relative">
                        <img class="absolute" src="{{ asset('images/white-03(1).png') }}" alt="ChromeCircle">
                        <div class="absolute">
                            <div class="justify-center text-center">
                                <div class="text-gray-700 xl:text-lg xxl:text-xl xxxl:text-2xl xxll:text-3xl xl:font-medium xxl:font-semibold">
                                    Total receivables MYR '000
                                </div>
                                <div class="text-red-500 xl:text-lg xxl:text-xl xxxl:text-2xl xxll:text-3xl xl:font-medium xxl:font-semibold">
                                    550
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="cursor-not-allowed  select-none md:inline-flex items-center justify-center xl:ml-16 xxl:ml-20 xl:w-56 lg:h-24 xsl:w-64 xxl:w-68  xxxl:w-72 xxxl:h-32 xxll:w-96 xxll:h-40  overflow-hidden shadow-2xl relative">
                        <img class="absolute" src="{{ asset('images/Box-Grey.png') }}" alt="ChromeCircle">
                        <div class="absolute">
                        </div>
                    </div>
                    <div class="cursor-not-allowed  select-none md:inline-flex items-center justify-center xl:mr-2 xxl:mr-0 xl:ml-16 xxl:ml-20 xl:w-56 lg:h-24 xsl:w-64 xxl:w-68  xxxl:w-72 xxxl:h-32 xxll:w-96 xxll:h-40 overflow-hidden shadow-2xl relative">
                        <img class="absolute" src="{{ asset('images/Box-Grey.png') }}" alt="ChromeCircle">
                        <div class="absolute">
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex mt-8 mb-8">
                <div class=" w-1/2 h-108">
                    <h2 class="text-center text-xl xxxl:text-2xl xxll:text-3xl leading-9 font-semibold tracking-wide text-gray-800 sm:leading-10">
                        Late Attendance (Monthly)
                    </h2>
                    <iframe id="iframe" class=" xl:px-4 xxl:px-6 min-h-full min-w-full" src="https://us-west-2b.online.tableau.com/t/businessinsighting/views/LateattendancemonthlyWEB/Chart?iframeSizedToWindow=true&:embed=y&:refresh&:showAppBanner=false&:display_count=n&:showVizHome=n&:origin=card_share_link&:embed=n&:toolbar=no"></iframe>
                    <div class="mx-8 flex">
                        <div class="h-4 w-4 bg-red-700 mr-2 self-center rounded-sm"></div>
                        <span class="inline-block align-top items-start self-start content-start">Late Hours</span>
                        <div class="h-4 w-4 bg-blue-700 ml-4 mr-2 self-center rounded-sm"></div>
                        <span class="inline-block align-top items-start self-start content-start">Late Occurence</span>
                    </div>
                </div>
                <div class=" w-1/2 h-108">
                    <h2 class="text-center text-xl xxxl:text-2xl xxll:text-3xl leading-9 font-semibold tracking-wide text-gray-800 sm:leading-10">
                        Emergency, Sick & Unpaid Leave (Monthly)
                    </h2>
                    <iframe id="iframe" class=" xl:px-4 xxl:px-6  min-h-full min-w-full" src="https://us-west-2b.online.tableau.com/t/businessinsighting/views/EmergencySickUnpaidLeaveMonthlyWEB/Trend?iframeSizedToWindow=true&:embed=y&:refresh&:showAppBanner=false&:display_count=n&:showVizHome=n&:origin=card_share_link&:embed=n&:toolbar=no"></iframe>
                    <div class="mx-8 flex">
                        <div class="h-4 w-4 bg-purple-700 mr-2 self-center rounded-sm"></div>
                        <span class="inline-block align-top items-start self-start content-start">Total leave taken for Emergency, Sick & Unpaid Leave (Days)</span>
                    </div>
                </div>
            </div>
            <div class="flex py-8 text-white">0
            </div>
            <div class="flex mt-16">
                <div class=" w-1/2 h-108">
                    <h2 class="text-center text-xl xxxl:text-2xl xxll:text-3xl leading-9 font-semibold tracking-wide text-gray-800 sm:leading-10">
                        Monthly Sales Volume (MT)
                    </h2>
                    <iframe id="iframe" class=" xl:px-4 xxl:px-6 min-h-full min-w-full" src="https://us-west-2b.online.tableau.com/t/businessinsighting/views/CEOSalesOverview_v0_1/SalesMT?iframeSizedToWindow=true&:embed=y&:refresh&:showAppBanner=false&:display_count=n&:showVizHome=n&:origin=card_share_link&:embed=n&:toolbar=no"></iframe>

                </div>
                <div class=" w-1/2 h-108">
                    <h2 class="text-center text-xl xxxl:text-2xl xxll:text-3xl leading-9 font-semibold tracking-wide text-gray-800 sm:leading-10">
                        Monthly Sales Value (MYR '000)
                    </h2>
                    <iframe id="iframe" class=" xl:px-4 xxl:px-6  min-h-full min-w-full" src="https://us-west-2b.online.tableau.com/t/businessinsighting/views/CEOSalesOverview_v0_1/SalesMYR?iframeSizedToWindow=true&:embed=y&:refresh&:showAppBanner=false&:display_count=n&:showVizHome=n&:origin=card_share_link&:embed=n&:toolbar=no"></iframe>

                </div>
            </div>

            <div class="mt-6 mx-auto px-2 sm:px-1 lg:px-4">
                <div class="mt-2 px-2 py-3 sm:px-1 sm:pb-3">
                    <a href="/sales/overview" class="topnavmobile topnavmobileori xl:text-base xxl:text-lg xxxl:text-xl xxll:text-2xl mt-1 ml-1 text-white ">Sales Overview</a>
                    <a href="/sales/management" class="topnavmobile topnavmobileori xl:text-base xxl:text-lg xxxl:text-xl xxll:text-2xl mt-1 ml-1 text-white ">Sales & Inventory Management</a>
                    <a href="/sales/renewal" class="topnavmobile topnavmobileori xl:text-base xxl:text-lg xxxl:text-xl xxll:text-2xl mt-1 ml-1 text-white ">Contract Renewal</a>
                    <a href="/sales/aspmanagement" class="topnavmobile topnavmobileori xl:text-base xxl:text-lg xxxl:text-xl xxll:text-2xl mt-1 ml-1 text-white ">Customer ASP Management</a>
                </div>
            </div>
            <div class="max-w-screen-xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl leading-9 font-bold tracking-wide text-gray-100 sm:leading-10">
                    Sales Overview
                </h2>
            </div>
            <div class="mt-2 min-h-screen min-w-full">
                <iframe id="iframe" class=" xl:px-4 xxl:px-6 min-h-screen min-w-full" src="https://us-west-2b.online.tableau.com/t/businessinsighting/views/SaleOverview_v0_1/SalesOverview?iframeSizedToWindow=true&:embed=y&:refresh&:showAppBanner=false&:display_count=n&:showVizHome=n&:origin=viz_share_link&:toolbar=no"></iframe>
            </div>

            <div class="max-w-screen-xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl leading-9 font-bold tracking-wide text-gray-100 sm:leading-10">
                    Sales Overview 16/10
                </h2>
            </div>
            <div class="mt-2  embed-responsive aspect-ratio-16/10">
                <iframe id="iframe" class=" xl:px-4 xxl:px-6 min-h-screen min-w-full" src="https://us-west-2b.online.tableau.com/t/businessinsighting/views/SaleOverview_v0_1/SalesOverview?iframeSizedToWindow=true&:embed=y&:refresh&:showAppBanner=false&:display_count=n&:showVizHome=n&:origin=viz_share_link&:toolbar=no"></iframe>
            </div>
            <div class=" embed-responsive aspect-ratio-16/10">
                <iframe id="iframe" class=" xl:px-4 xxl:px-6 embed-responsive-item" src="https://us-west-2b.online.tableau.com/t/businessinsighting/views/ContractRenewalDashboard/ContractRenewal?iframeSizedToWindow=true&:embed=y&:refresh&:showAppBanner=false&:display_count=n&:showVizHome=n&:origin=viz_share_link&:toolbar=no"></iframe>
            </div>
            <div class="max-w-screen-xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl leading-9 font-bold tracking-wide text-gray-100 sm:leading-10">
                    Sales Overview 4/3
                </h2>
            </div>
            <div class="mt-2  embed-responsive aspect-ratio-4/3">
                <iframe id="iframe" class=" xl:px-4 xxl:px-6 min-h-screen min-w-full" src="https://us-west-2b.online.tableau.com/t/businessinsighting/views/SaleOverview_v0_1/SalesOverview?iframeSizedToWindow=true&:embed=y&:refresh&:showAppBanner=false&:display_count=n&:showVizHome=n&:origin=viz_share_link&:toolbar=no"></iframe>
            </div>

            <div class="mx-auto px-2 sm:px-1 lg:px-4">
                <div class="px-2 py-3 sm:px-1">
                    <a href="/people/overview" class="topnavmobile topnavmobileori xl:text-base xxl:text-lg xxxl:text-xl xxll:text-2xl ml-1 text-white ">People Overview</a>
                    <a href="/people/late/tv" class="topnavmobile topnavmobileori xl:text-base xxl:text-lg xxxl:text-xl xxll:text-2xl ml-1 text-white " >Late Attendance</a>
                    <a href="/people/absenteeism" class="topnavmobile topnavmobileori xl:text-base xxl:text-lg xxxl:text-xl xxll:text-2xl ml-1 text-white " >Absenteeism</a>
                    <a href="/people/leaves/tv" class="topnavmobile topnavmobileori xl:text-base xxl:text-lg xxxl:text-xl xxll:text-2xl ml-1 text-white " >Emergency, Sick and Unpaid Leave</a>
                    <a href="/people/ot" class="topnavmobile topnavmobileori xl:text-base xxl:text-lg xxxl:text-xl xxll:text-2xl ml-1 text-white " >Overtime</a>
                    <a href="/people/expenses/overall" class="topnavmobile topnavmobileori xl:text-base xxl:text-lg xxxl:text-xl xxll:text-2xl ml-1 text-white " >Medical Expenses</a>
                    <a href="/people/behavior/overall" class="topnavmobile topnavmobileori xl:text-base xxl:text-lg xxxl:text-xl xxll:text-2xl ml-1 text-white " >Staff Behaviour</a>
                </div>
            </div>

            <div class="max-w-screen-xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl leading-9 font-bold tracking-wide text-gray-100 sm:leading-10">
                    People Overview
                </h2>
            </div>
            <div class="mt-2 min-h-screen min-w-full">
                <iframe id="iframe" class=" xl:px-4 xxl:px-6 min-h-screen min-w-full" src="https://us-west-2b.online.tableau.com/t/businessinsighting/views/PeopleOverview/Daily?iframeSizedToWindow=true&:embed=y&:refresh&:showAppBanner=false&:display_count=n&:showVizHome=n&:origin=viz_share_link&:toolbar=no"></iframe>
            </div>
            <div class=" mx-auto px-2 sm:px-1 lg:px-4">
                <div class="flex px-2 py-2 sm:px-1">
                    <a href="/people/late/tv" class="text-center w-1/12 xl:px-2 xxl:px-4 xxxl:px-8 xxll:px-12 mt-1 ml-2 xxl:ml-4 text-gray-700 bg-white py-3 xxl:py-4  rounded-md xxxl:rounded-lg text-md xxxl:text-lg xxll:text-2xl font-semibold hover:bg-yellow-200 xl:border-2 xxxl:border-4 border-gray-300  " >Daily (TV)</a>
                    <a href="/people/late/daily" class="text-center w-1/12 xl:px-2 xxl:px-4 xxxl:px-8 xxll:px-12 mt-1 ml-2 xxl:ml-4 text-gray-700 bg-white py-3 xxl:py-4  rounded-md xxxl:rounded-lg text-md xxxl:text-lg xxll:text-2xl font-semibold hover:bg-yellow-200 xl:border-2 xxxl:border-4 border-gray-300  " >Daily</a>
                    <a href="/people/late/monthly" class="text-center w-1/12 xl:px-2 xxl:px-4 xxxl:px-8 xxll:px-12 mt-1 ml-2 xxl:ml-4 text-gray-700 bg-white py-3 xxl:py-4  rounded-md xxxl:rounded-lg text-md xxxl:text-lg xxll:text-2xl font-semibold hover:bg-yellow-200 xl:border-2 xxxl:border-4 border-gray-300  " >Monthly</a>
                </div>
            </div>
            <div class="max-w-screen-xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl leading-9 font-bold tracking-wide text-gray-100 sm:leading-10">
                    Late Attendance (TV)
                </h2>
            </div>
            <div class="mt-2 embed-responsive aspect-ratio-21/9">
                <iframe id="iframe" class=" xl:px-4 xxl:px-6 embed-responsive-item" src="https://us-west-2b.online.tableau.com/t/businessinsighting/views/LateAttendanceDailyTV/TV?iframeSizedToWindow=true&:embed=y&:refresh&:showAppBanner=false&:display_count=n&:showVizHome=n&:origin=viz_share_link&:toolbar=no"></iframe>
            </div>
            <div class="max-w-screen-xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl leading-9 font-bold tracking-wide text-gray-100 sm:leading-10">
                    Late Attendance (Daily)
                </h2>
            </div>
            <div class="mt-2 min-h-screen min-w-full">
                <iframe id="iframe" class=" xl:px-4 xxl:px-6 min-h-screen min-w-full" src="https://us-west-2b.online.tableau.com/t/businessinsighting/views/LateAttendanceDaily/Daily?iframeSizedToWindow=true&:embed=y&:refresh&:showAppBanner=false&:display_count=n&:showVizHome=n&:origin=viz_share_link&:toolbar=no"></iframe>
            </div>
            <div class="max-w-screen-xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl leading-9 font-bold tracking-wide text-gray-100 sm:leading-10">
                    Late Attendance (Monthly)
                </h2>
            </div>
            <div class="mt-2 min-h-screen min-w-full">
                <iframe id="iframe" class=" xl:px-4 xxl:px-6 min-h-screen min-w-full" src="https://us-west-2b.online.tableau.com/t/businessinsighting/views/Lateattendancemonthly/Dashboard1?iframeSizedToWindow=true&:embed=y&:refresh&:showAppBanner=false&:display_count=n&:showVizHome=n&:origin=viz_share_link&:toolbar=no"></iframe>
            </div>
            <div class="max-w-screen-xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl leading-9 font-bold tracking-wide text-gray-100 sm:leading-10">
                    Absenteeism Dashboard
                </h2>
            </div>
            <div class="mt-2 min-h-screen min-w-full">
                <iframe id="iframe" class=" xl:px-4 xxl:px-6 min-h-screen min-w-full" src="https://us-west-2b.online.tableau.com/t/businessinsighting/views/AbsenteeismDaily/Daily?iframeSizedToWindow=true&:embed=y&:refresh&:showAppBanner=false&:display_count=n&:showVizHome=n&:origin=viz_share_link&:toolbar=no"></iframe>
            </div>
            <div class=" mx-auto px-2 sm:px-1 lg:px-4">
                <div class="flex px-2 py-2 sm:px-1">
                    <a href="/people/leaves/tv" class="text-center w-1/12 mt-1 ml-2 xxl:ml-4 text-gray-700 bg-white xl:px-2 xxl:px-4 xxxl:px-8 xxll:px-12 py-3 xxl:py-4 rounded-md xxxl:rounded-lg text-md xxxl:text-lg xxll:text-2xl font-semibold hover:bg-yellow-200 xl:border-2 xxxl:border-4 border-gray-300 " >Daily (TV)</a>
                    <a href="/people/leaves/daily" class="text-center w-1/12 mt-1 ml-2 xxl:ml-4 text-gray-700 bg-white xl:px-2 xxl:px-4 xxxl:px-8 xxll:px-12 py-3 xxl:py-4 rounded-md xxxl:rounded-lg text-md xxxl:text-lg xxll:text-2xl font-semibold hover:bg-yellow-200 xl:border-2 xxxl:border-4 border-gray-300 " >Daily</a>
                    <a href="/people/leaves/monthly" class="text-center w-1/12 mt-1 ml-2 xxl:ml-4 text-gray-700 bg-white xl:px-2 xxl:px-4 xxxl:px-8 xxll:px-12 py-3 xxl:py-4 rounded-md xxxl:rounded-lg text-md xxxl:text-lg xxll:text-2xl font-semibold hover:bg-yellow-200 xl:border-2 xxxl:border-4 border-gray-300 " >Monthly</a>
                </div>
            </div>
            <div class="max-w-screen-xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl leading-9 font-bold tracking-wide text-gray-100 sm:leading-10">
                    Emergency, Sick & Unpaid Leave (TV)
                </h2>
            </div>
            <div class="mt-2 embed-responsive aspect-ratio-21/9">
                <iframe id="iframe" class=" xl:px-4 xxl:px-6 embed-responsive-item" src="https://us-west-2b.online.tableau.com/t/businessinsighting/views/EmergencySickUnpaidLeaveDailyTV/TV?iframeSizedToWindow=true&:embed=y&:refresh&:showAppBanner=false&:display_count=n&:showVizHome=n&:origin=viz_share_link&:toolbar=no"></iframe>
            </div>
            <div class="max-w-screen-xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl leading-9 font-bold tracking-wide text-gray-100 sm:leading-10">
                    Emergency, Sick & Unpaid Leave (Daily)
                </h2>
            </div>
            <div class="mt-2 embed-responsive aspect-ratio-16/10">
                <iframe id="iframe" class=" xl:px-4 xxl:px-6 embed-responsive-item" src="https://us-west-2b.online.tableau.com/t/businessinsighting/views/EmergencySickUnpaidLeaveDaily/Daily?iframeSizedToWindow=true&:embed=y&:refresh&:showAppBanner=false&:display_count=n&:showVizHome=n&:origin=viz_share_link&:toolbar=no"></iframe>
            </div>
            <div class="max-w-screen-xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl leading-9 font-bold tracking-wide text-gray-100 sm:leading-10">
                    Emergency, Sick & Unpaid Leave (Monthly)
                </h2>
            </div>
            <div class="mt-2 embed-responsive aspect-ratio-16/10">
                <iframe id="iframe" class=" xl:px-4 xxl:px-6 embed-responsive-item" src="https://us-west-2b.online.tableau.com/t/businessinsighting/views/EmergencySickUnpaidLeaveMonthly/MonthlyDashboard?iframeSizedToWindow=true&:embed=y&:refresh&:showAppBanner=false&:display_count=n&:showVizHome=n&:origin=viz_share_link&:toolbar=no"></iframe>
            </div>
            <div class=" min-h-full min-w-full">
                <img class=" xl:px-4 xxl:px-6 min-h-full min-w-full" src="/static/dashboardImages/Overtime.png" alt="peopleOverview">
            </div>
            <div class=" min-h-full min-w-full">
                <img class=" xl:px-4 xxl:px-6 min-h-full min-w-full" src="/static/dashboardImages/Medical%20Expenses%20–%201.png" alt="peopleOverview">
            </div>
            <div class="mx-auto px-2 sm:px-1 lg:px-4">
                <div class="flex px-2 py-2 sm:px-1">
                    <a href="/people/behavior/overall" class="text-center w-1/12 mt-1 ml-2 xxl:ml-4 text-gray-700 bg-white xl:px-2 xxl:px-4 xxxl:px-8 xxll:px-12 py-3 xxl:py-4 rounded-md xxxl:rounded-lg text-md xxxl:text-lg xxll:text-2xl font-semibold hover:bg-yellow-200 xl:border-2 xxxl:border-4 border-gray-300 " >Overall</a>
                    <a href="/people/behavior/individual" class="text-center w-1/12 mt-1 ml-2 xxl:ml-4 text-gray-700 bg-white xl:px-2 xxl:px-4 xxxl:px-8 xxll:px-12 py-3 xxl:py-4 rounded-md xxxl:rounded-lg text-md xxxl:text-lg xxll:text-2xl font-semibold hover:bg-yellow-200 xl:border-2 xxxl:border-4 border-gray-300 " >Individual</a>
                </div>
            </div>
            <div class="max-w-screen-xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl leading-9 font-bold tracking-wide text-gray-100 sm:leading-10">
                    Overall
                </h2>
            </div>
            <div class="mt-2 embed-responsive aspect-ratio-4/3">
                <iframe id="iframe" class=" xl:px-4 xxl:px-6 embed-responsive-item" src="https://www.youtube.com/embed/2Bb0k9HgQxc"></iframe>
            </div>
            <div class="max-w-screen-xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl leading-9 font-bold tracking-wide text-gray-100 sm:leading-10">
                    Individual
                </h2>
            </div>
            <div class="mt-2 embed-responsive aspect-ratio-4/3">
                <iframe id="iframe" class=" xl:px-4 xxl:px-6 embed-responsive-item" src="https://www.youtube.com/embed/2Bb0k9HgQxc"></iframe>
            </div>

            <div class="mx-auto px-2 sm:px-1 lg:px-4">
                <div class="mt-1 px-2 py-3 sm:px-1">
                    <a href="/finance/overview" class="topnavmobile topnavmobileori xl:text-base xxl:text-lg xxxl:text-xl xxll:text-2xl mt-1 ml-1 text-white ">Finance Overview</a>
                    <a href="/finance/operations" class="topnavmobile topnavmobileori xl:text-base xxl:text-lg xxxl:text-xl xxll:text-2xl mt-1 ml-1 text-white " >Finance Operations</a>
                    <a href="/finance/araging" class="topnavmobile topnavmobileori xl:text-base xxl:text-lg xxxl:text-xl xxll:text-2xl mt-1 ml-1 text-white " >AR Aging</a>
                </div>
            </div>

            <div class=" min-h-full min-w-full">
                <img class=" xl:px-4 xxl:px-6 min-h-full min-w-full" src="/static/dashboardImages/Finance%20Overview.png" alt="financeOverview">
            </div>
            <div class=" min-h-full min-w-full">
                <img class=" xl:px-4 xxl:px-6 min-h-full min-w-full" src="/static/dashboardImages/Finance%20Operations.png" alt="financeOp">
            </div>
            <div class=" min-h-full min-w-full">
                <img class=" xl:px-4 xxl:px-6 min-h-full min-w-full" src="/static/dashboardImages/Finance%20AR.png" alt="financeAR">
            </div>
        </div>
    </div>
@endsection
