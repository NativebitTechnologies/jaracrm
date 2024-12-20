
<?php $this->load->view('includes/header'); ?>
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
<link href="<?=base_url();?>assets/src/plugins/src/apex/apexcharts.css" rel="stylesheet" type="text/css">
<link href="<?=base_url();?>assets/src/assets/css/light/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
<!-- <link href="<?=base_url();?>assets/src/assets/css/dark/dashboard/dash_1.css" rel="stylesheet" type="text/css" /> -->
<!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

    <!--  BEGIN CONTENT AREA  -->
    <div id="content" class="main-content">
        <div class="layout-px-spacing">
            <div class="middle-content p-0">
                <div class="row layout-top-spacing">

                    <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                        <div class="widget widget-six">
                            <div class="widget-heading">
                                <h6 class="">Statistics</h6>
                                <div class="task-action">
                                    <div class="dropdown">
                                        <a class="dropdown-toggle" href="#" role="button" id="statistics" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                        </a>

                                        <div class="dropdown-menu left" aria-labelledby="statistics" style="will-change: transform;">
                                            <a class="dropdown-item" href="javascript:void(0);">View</a>
                                            <a class="dropdown-item" href="javascript:void(0);">Download</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-chart">
                                <div class="w-chart-section">
                                    <div class="w-detail">
                                        <p class="w-title">Total Visits</p>
                                        <p class="w-stats">423,964</p>
                                    </div>
                                    <div class="w-chart-render-one">
                                        <div id="total-users"></div>
                                    </div>
                                </div>

                                <div class="w-chart-section">
                                    <div class="w-detail">
                                        <p class="w-title">Paid Visits</p>
                                        <p class="w-stats">7,929</p>
                                    </div>
                                    <div class="w-chart-render-one">
                                        <div id="paid-visits"></div>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </div>
                    
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                        <div class="widget widget-card-four">
                            <div class="widget-content">
                                <div class="w-header">
                                    <div class="w-info">
                                        <h6 class="value">Sales Vs Target</h6>
                                    </div>
                                </div>

                                <div class="w-content">

                                    <div class="w-info">
                                        <p class="value fs-18" id="sales_vs_target_value">₹ 45,141 / ₹ 45,141 <span>this week</span></p>
                                    </div>
                                    
                                </div>

                                <div class="w-progress-stats">                                            
                                    <div class="progress">
                                        <div class="progress-bar bg-gradient-secondary" role="progressbar" id="sales_vs_target_per_bar" style="width: 57%" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>

                                    <div class="">
                                        <div class="w-icon">
                                            <p id="sales_vs_target_per">57%</p>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>  
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                        <div class="widget widget-card-three">
                            <div class="widget-content">
                                <div class="account-box">
                                    <div class="info">
                                        <div class="inv-title">
                                            <h5 class="">Total Balance</h5>
                                        </div>
                                        <div class="inv-balance-info">
                                            <p class="inv-balance">$ 41,741.42</p>
                                            <span class="inv-stats balance-credited">+ 2453</span>
                                        </div>
                                    </div>
                                    <div class="acc-action">
                                        <div class="">
                                            <a href="javascript:void(0);" class="btn-wallet"><svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg></a>
                                        </div>
                                        <a href="javascript:void(0);" class="btn-add-balance">Add Balance</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-9 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                        <div class="widget widget-chart-three">
                            <div class="widget-heading">
                                <div class="">
                                    <h5 class="">Lead Analysis</h5>
                                </div>

                                <div class="dropdown ">
                                    <a class="dropdown-toggle" href="#" role="button" id="uniqueVisitors" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                    </a>

                                    <div class="dropdown-menu left" aria-labelledby="uniqueVisitors">
                                        <a class="dropdown-item" href="javascript:void(0);">View</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Update</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Download</a>
                                    </div>
                                </div>
                            </div>

                            <div class="widget-content">
                                <div id="uniqueVisits"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                        <div class="widget widget-activity-five">

                            <div class="widget-heading">
                                <h5 class="">Sales Tips !</h5>

                                <div class="task-action">
                                    <div class="dropdown">
                                        <a class="dropdown-toggle" href="#" role="button" id="activitylog" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                        </a>

                                        <div class="dropdown-menu left" aria-labelledby="activitylog" style="will-change: transform;">
                                            <a class="dropdown-item" href="javascript:void(0);">View All</a>
                                            <a class="dropdown-item" href="javascript:void(0);">Mark as Read</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="widget-content">

                                <div class="w-shadow-top"></div>

                                <div class="mt-container mx-auto">
                                    <div class="timeline-line">
                                        
                                        <div class="item-timeline timeline-new">
                                            <div class="t-dot">
                                                <div class="t-success"><?=getIcon('check_circle')?></div>
                                            </div>
                                            <div class="t-content">
                                                <div class="t-uppercontent">
                                                    <h5>You have some client's with more business in their bags<br><br><a href="javscript:void(0);"><span>Advise : </span></a>You should make some extra efforts for them</h5>
                                                </div><br>
                                                <p>Kadson Engineers (₹ 75,000)<br>Sunflow Bath Fittings (₹ 38,000)<br>Akshar Engineers (₹ 22,000)</p>
                                            </div>
                                        </div>

                                        <div class="item-timeline timeline-new">
                                            <div class="t-dot">
                                                <div class="t-warning"><?=getIcon('check_circle')?></div>
                                            </div>
                                            <div class="t-content">
                                                <div class="t-uppercontent">
                                                    <h5>PRODUCT RANGE POTENTIAL<br><br><a href="javscript:void(0);"><span>Advise : </span></a>You should have to approach more product range to existing customer</h5>
                                                </div><br>
                                                <p>Aarogyam Dental Clinic (Implants)<br>Dr. Nirma's Dental Clinic (Abutment)<br>Lifedent Dental Hospital (Instruments)</p>
                                            </div>
                                        </div>                                  
                                    </div>                                    
                                </div>

                                <div class="w-shadow-bottom"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                        <div class="widget-four">
                            <div class="widget-heading">
                                <h5 class="">Churn Prediction Rate</h5>
                            </div>
                            <div class="widget-content">
                                <div class="vistorsBrowser">
                                    
                                    <div class="browser-list">
                                        <div class="w-icon">
                                            <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chrome"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="4"></circle><line x1="21.17" y1="8" x2="12" y2="8"></line><line x1="3.95" y1="6.06" x2="8.54" y2="14"></line><line x1="10.88" y1="21.94" x2="15.46" y2="14"></line></svg>
                                        </div>
                                        <div class="w-browser-details">
                                            <div class="w-browser-info">
                                                <h6>S3M Consultant</h6>
                                                <p class="browser-count">40%</p>
                                            </div>
                                            <div class="w-browser-stats">
                                                <div class="progress">
                                                    <div class="progress-bar bg-gradient-primary" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="browser-list">
                                        <div class="w-icon">
                                            <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-compass"><circle cx="12" cy="12" r="10"></circle><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon></svg>
                                        </div>
                                        <div class="w-browser-details">
                                            
                                            <div class="w-browser-info">
                                                <h6>Dr. K. D. Amipara</h6>
                                                <p class="browser-count">28%</p>
                                            </div>

                                            <div class="w-browser-stats">
                                                <div class="progress">
                                                    <div class="progress-bar bg-gradient-danger" role="progressbar" style="width: 28%" aria-valuenow="28" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="browser-list">
                                        <div class="w-icon">
                                            <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                                        </div>
                                        <div class="w-browser-details">
                                            <div class="w-browser-info">
                                                <h6>Shree Swagat Hardware</h6>
                                                <p class="browser-count">15%</p>
                                            </div>

                                            <div class="w-browser-stats">
                                                <div class="progress">
                                                    <div class="progress-bar bg-gradient-warning" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="row widget-statistic">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 layout-spacing">
                                <div class="widget widget-one_hybrid widget-followers">
                                    <div class="widget-heading">
                                        <div class="w-title">
                                            <div class="w-icon">
                                                <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                            </div>
                                            <div class="">
                                                <p class="w-value">31.6K</p>
                                                <h5 class="">New Business</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="widget-content">    
                                        <div class="w-chart">
                                            <div id="hybrid_followers"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 layout-spacing">
                                <div class="widget widget-one_hybrid widget-referral">
                                    <div class="widget-heading">
                                        <div class="w-title">
                                            <div class="w-icon">
                                                <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-link"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                                            </div>
                                            <div class="">
                                                <p class="w-value">35.5K</p>
                                                <h5 class="">Referral Sales</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="widget-content">    
                                        <div class="w-chart">
                                            <div id="hybrid_followers1"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 layout-spacing">
                                <div class="widget widget-one_hybrid widget-engagement">
                                    <div class="widget-heading">
                                        <div class="w-title">
                                            <div class="w-icon">
                                                <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                                            </div>
                                            <div class="">
                                                <p class="w-value">18.2%</p>
                                                <h5 class="">Engagement Sales</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="widget-content">    
                                        <div class="w-chart">
                                            <div id="hybrid_followers3"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
        <!--  BEGIN FOOTER  -->
        <div class="footer-wrapper">
            <div class="footer-section f-section-1">
                <p class="">Copyright © <span class="dynamic-year">2022</span> <a target="_blank" href="https://designreset.com/cork-admin/">DesignReset</a>, All rights reserved.</p>
            </div>
            <div class="footer-section f-section-2">
                <p class="">Coded with <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></p>
            </div>
        </div>
        <!--  END FOOTER  -->
    </div>
    <!--  END CONTENT AREA  -->

<?php $this->load->view('includes/footer'); ?>

<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
<script src="<?=base_url();?>assets/src/plugins/src/apex/apexcharts.min.js"></script>
<script src="<?=base_url();?>assets/src/assets/js/dashboard/dash_1.js"></script>
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

<script>
$(document).ready(function(){
    $("#sales_vs_target_value").html("₹ 0 / ₹ 0 <span>this month</span>");
    $("#sales_vs_target_per").html("0%");
    $("#sales_vs_target_per_bar").attr('style','width:0%;').attr('aria-valuenow','0');

    $.ajax({
        url : base_url + controller + '/getSalesVsTarget',
        type : 'post',
        data : {},
        dataType : 'json'
    }).done(function(response){
        $("#sales_vs_target_value").html("₹ "+response.sales+" / ₹ "+response.target+" <span>this month</span>");
        $("#sales_vs_target_per").html(response.per+"%");
        $("#sales_vs_target_per_bar").attr('style','width:'+response.per+'%;').attr('aria-valuenow',response.per);
    });
});
</script>