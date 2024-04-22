@extends('retail.index')

@section('title') Faq @endsection

@section('content')

<div class="slim-mainpanel faq">
    <div class="container">

        <div class="slim-pageheader">
            <div>
            </div>
            <h6 class="slim-pagetitle">Berjaya Dashboard FAQ &amp; User Guide</h6>
        </div>

        <!-- TAB -->
        <div class="row">
            <div class="col-sm-12">
                <ul class="nav nav-tabs">
                    <li ><a data-toggle="tab" href="#tab1" aria-selected="true" class="active">Berjaya Dashboard Faq</a></li>
                    <li><a data-toggle="tab" href="#tab2" aria-selected="false">Berjaya Intelligence Dashboard User Guide</a></li>
                </ul>
            </div>
        </div>
        <!-- TAB END -->

        <div class="row">
            <div class="col-sm-12">
                
                <div class="tab-content">

                    <div id="tab1" class="tab-pane fade show active">
                        <div id="accordion" class="accordion-one" role="tablist" aria-multiselectable="true">
                            <div class="card">
                                <div class="card-header" role="tab" id="headingOne">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="tx-gray-800 transition">
                                        What is the purpose of this Dashboard?
                                    </a>
                                </div><!-- card-header -->

                                <div id="collapseOne" class="collapse" role="tabpanel" aria-labelledby="headingOne" style="">
                                    <div class="card-body">
                                        The purpose of the Berjaya Intelligence Dashboard is to provide a personalized view of key business metrics to Berjaya Corporation Management and respective Heads of department and companies. The dashboard would provide a consolidated view from across Berjaya Corporation, the 4 sector groups and subsidiary companies on custom dashboards that delivers valuable insights for the Management team to make smarter, data-driven decisions.
                                    </div>
                                </div>
                            </div><!-- close card-->

                            <div class="card">
                                <div class="card-header" role="tab" id="headingTwo">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" class="tx-gray-800 transition">
                                        Who has access to the Dashboards?
                                    </a>
                                </div><!-- card-header -->

                                <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo" style="">
                                    <div class="card-body">
                                        The dashboard currently is only accessible to the C-suite of Berjaya Corporation and the top Management of the Berjaya’s subsidiaries.
                                    </div>
                                </div>
                            </div><!-- close card-->

                            <div class="card">
                                <div class="card-header" role="tab" id="headingThree">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree" class="tx-gray-800 transition">
                                        Will my company’s information be given out to the other subsidiaries?
                                    </a>
                                </div><!-- card-header -->

                                <div id="collapseThree" class="collapse" role="tabpanel" aria-labelledby="headingThree" style="">
                                    <div class="card-body">
                                    The user access for subsidiaries under Berjaya will be limited to their own respective companies and c-level under Berjaya Corporation. Therefore, no other personnel, companies or subsidiaries are allowed to view any information outside of their jurisdiction. Information Security is the utmost importance
                                    </div>
                                </div>
                            </div><!-- close card-->

                            <div class="card">
                                <div class="card-header" role="tab" id="headingFour">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour" class="tx-gray-800 transition">
                                        How is the Dashboard structured?
                                    </a>
                                </div><!-- card-header -->

                                <div id="collapseFour" class="collapse" role="tabpanel" aria-labelledby="headingFour" style="">
                                    <div class="card-body">
                                        <img src="images/Hierarchy.PNG" alt="logo" class="img-fluid d-block mx-auto mb-4"/>
                                        The hierarchy of the Dashboard is as the figure above, where the information is broken down from the Berjaya Corporation level and spans downwards to the 4 main verticals (Retail, Property, Hospitality and Services). The vertical branches are also further broken into their respective groups based on their industry. Lastly the hierarchy would show the each of the company in their respective groups.
                                    </div>
                                </div>
                            </div><!-- close card-->

                            <div class="card">
                                <div class="card-header" role="tab" id="headingFive">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive" class="tx-gray-800 transition">
                                        Who do I contact if I have any issues or need assistance?
                                    </a>
                                </div><!-- card-header -->

                                <div id="collapseFive" class="collapse" role="tabpanel" aria-labelledby="headingFive" style="">
                                    <div class="card-body">
                                        If you experience any issues or need assistance, you may reach out to our main contact person from Berjaya Digital Division, Fahmi Fadzil at <a href="mailto:ahmad.fahmi@berjaya.com.my" target="_blank">ahmad.fahmi@berjaya.com.my</a>.
                                    </div>
                                </div>
                            </div><!-- close card-->

                        </div><!-- accordion-->
                    </div><!-- tab 1-->

                    <div id="tab2" class="tab-pane fade">
                        <div id="accordion2" class="accordion-one" role="tablist" aria-multiselectable="true">
                            <div class="card">
                                <div class="card-header" role="tab" id="headingSix">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapseSix" aria-expanded="true" aria-controls="collapseSix" class="tx-gray-800 transition">
                                        <p class="mb-0">Berjaya Intelligence Dashboard Interface</p>
                                    </a>
                                </div><!-- card-header -->
                                <div id="collapseSix" class="collapse show" role="tabpanel" aria-labelledby="headingSix" style="">
                                    <div class="card-body">
                                        <img src="images/Screenshot Dashboard.PNG" class="img-fluid d-block mx-auto mb-4"/>
                                        The Berjaya Intelligence Dashboard Interface can be broken into 3 sections. They are Cards, Pie Charts and Detailed Graphs.  
                                    </div>
                                </div>
                            </div><!-- card close -->

                            <div class="card">
                                <div class="card-header" role="tab" id="headingSeven">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven" class="tx-gray-800 transition">
                                        <p class="mb-0">Cards</p>
                                    </a>
                                </div><!-- card-header -->
                                <div id="collapseSeven" class="collapse" role="tabpanel" aria-labelledby="headingSeven" style="">
                                    <div class="card-body">
                                        <img src="images/cards.PNG" class="img-fluid d-block mx-auto mb-4"/>
                                        Description: The cards section of the dashboard is the main financial information required to analyse financial performances. This section consists of 6 cars which are:
                                        <ul>
                                            <li>Revenue</li>
                                            <li>Cost of Sale</li>
                                            <li>Gross / Profit Lose</li>
                                            <li>Operating Expenses</li>
                                            <li>EBITA</li>
                                            <li>Profit after tax</li>
                                        </ul> 

                                        Business rule & logic:

                                        <ul>
                                            <li>All currencies are based on Malaysian Ringgit (MYR)</li>
                                            <li>Each colour of the cards will indicate the performance on the data (Green = Healthy, Amber = At Risk and Red = In Danger). The parameters for a data to be classified in Amber is for it to be at 80% - 100% target threshold of the financial performance for the respective month.</li>
                                            <li>The lines below the figures are simple graphs (trend indicators) to provide the user a quick glance of the trend.</li>
                                        </ul> 
                                    </div>
                                </div>
                            </div><!-- card close -->

                            <div class="card">
                                <div class="card-header" role="tab" id="headingEight">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapseEight" aria-expanded="false" aria-controls="collapseEight" class="tx-gray-800 transition">
                                        <p class="mb-0">Pie Charts</p>
                                    </a>
                                </div><!-- card-header -->
                                <div id="collapseEight" class="collapse" role="tabpanel" aria-labelledby="headingEight" style="">
                                    <div class="card-body">
                                        <img src="images/pie.PNG" class="img-fluid d-block mx-auto mb-4"/>
                                        Description: The pie chart section is the section where the breakdown of the cards is portrayed.Business rule & logic:
                                        <ul>
                                            <li>This section will breakdown the cards based on each level hierarchy, where the breakdown will roll down further until it reaches the company level. Refer to section 1.2 Navigation Tree as a guide. </li>
                                        </ul>
                                    </div>
                                </div>
                            </div><!-- card close -->

                            <div class="card">
                                <div class="card-header" role="tab" id="headingNine">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapseNine" aria-expanded="false" aria-controls="collapseNine" class="tx-gray-800 transition">
                                        <p class="mb-0">Detailed Data</p>
                                    </a>
                                </div><!-- card-header -->
                                <div id="collapseNine" class="collapse" role="tabpanel" aria-labelledby="headingNine" style="">
                                    <div class="card-body">
                                        <img src="images/detailed.PNG" class="img-fluid d-block mx-auto mb-4"/>
                                        Description: The detailed data section contains several graphs to further detail out the financial performance trends based on Monthly, Quarterly and Annual Data. Business rule & logic:

                                        <ul>
                                            <li>All currencies are in Malaysian Ringgit (MYR)</li>
                                        </ul>
                                    </div>
                                </div>
                            </div><!-- card close -->
                            
                        </div><!-- accordion-->
                    </div><!-- tab2 -->

                </div><!-- tab content -->

            </div><!-- col-sm-12 -->
            
        </div>

    </div>
</div>


    @endsection