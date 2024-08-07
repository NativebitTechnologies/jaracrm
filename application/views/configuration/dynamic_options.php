
<?php $this->load->view('includes/header'); ?>
<link href="<?=base_url()?>assets/src/assets/css/light/widgets/modules-widgets.css" rel="stylesheet" type="text/css">  
	
	<!--  BEGIN CONTENT AREA  -->
	<div id="content" class="main-content">
		<div class="layout-px-spacing">
			<div class="middle-content container-xxl p-0">
				<div class="row layout-top-spacing">
					<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
						<div class="widget widget-table-one">
							<div class="widget-heading">
								<h5 class="">Transactions</h5>
								<div class="task-action">
									<div class="dropdown">
										<a class="dropdown-toggle" href="#" role="button" id="pendingTask" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
										</a>

										<div class="dropdown-menu left" aria-labelledby="pendingTask" style="will-change: transform;">
											<a class="dropdown-item" href="javascript:void(0);">View Report</a>
											<a class="dropdown-item" href="javascript:void(0);">Edit Report</a>
											<a class="dropdown-item" href="javascript:void(0);">Mark as Done</a>
										</div>
									</div>
								</div>
							</div>

							<div class="widget-content">
								<div class="transactions-list">
									<div class="t-item">
										<div class="t-company-name">
											<div class="t-icon">
												<div class="icon">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
												</div>
											</div>
											<div class="t-name">
												<h4>Electricity Bill</h4>
												<p class="meta-date">04 Jan 1:00PM</p>
											</div>

										</div>
										<div class="t-rate rate-dec">
											<p><span>-$16.44</span></p>
										</div>
									</div>
								</div>

								<div class="transactions-list t-info">
									<div class="t-item">
										<div class="t-company-name">
											<div class="t-icon">
												<div class="avatar">
													<span class="avatar-title">SP</span>
												</div>
											</div>
											<div class="t-name">
												<h4>Shaun Park</h4>
												<p class="meta-date">10 Jan 1:00PM</p>
											</div>
										</div>
										<div class="t-rate rate-inc">
											<p><span>+$36.11</span></p>
										</div>
									</div>
								</div>

								<div class="transactions-list">
									<div class="t-item">
										<div class="t-company-name">
											<div class="t-icon">
												<div class="avatar">
													<span class="avatar-title">AD</span>
												</div>
											</div>
											<div class="t-name">
												<h4>Amy Diaz</h4>
												<p class="meta-date">31 Jan 1:00PM</p>
											</div>

										</div>
										<div class="t-rate rate-inc">
											<p><span>+$66.44</span></p>
										</div>
									</div>
								</div>

								<div class="transactions-list t-secondary">
									<div class="t-item">
										<div class="t-company-name">
											<div class="t-icon">
												<div class="icon">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
												</div>
											</div>
											<div class="t-name">
												<h4>Netflix</h4>
												<p class="meta-date">02 Feb 1:00PM</p>
											</div>

										</div>
										<div class="t-rate rate-dec">
											<p><span>-$32.00</span></p>
										</div>
									</div>
								</div>


								<div class="transactions-list t-info">
									<div class="t-item">
										<div class="t-company-name">
											<div class="t-icon">
												<div class="avatar">
													<span class="avatar-title">DA</span>
												</div>
											</div>
											<div class="t-name">
												<h4>Daisy Anderson</h4>
												<p class="meta-date">15 Feb 1:00PM</p>
											</div>
										</div>
										<div class="t-rate rate-inc">
											<p><span>+$10.08</span></p>
										</div>
									</div>
								</div>

								<div class="transactions-list">
									<div class="t-item">
										<div class="t-company-name">
											<div class="t-icon">
												<div class="avatar">
													<span class="avatar-title">OG</span>
												</div>
											</div>
											<div class="t-name">
												<h4>Oscar Garner</h4>
												<p class="meta-date">20 Feb 1:00PM</p>
											</div>

										</div>
										<div class="t-rate rate-dec">
											<p><span>-$22.00</span></p>
										</div>
									</div>
								</div>
								
								
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</div>

<?php $this->load->view('includes/footer'); ?>