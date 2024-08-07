
<?php $this->load->view('includes/header'); ?>
<link href="<?=base_url()?>assets/src/assets/css/light/widgets/modules-widgets.css" rel="stylesheet" type="text/css">  
	
	<!--  BEGIN CONTENT AREA  -->
	<div id="content" class="main-content">
		<div class="layout-px-spacing">
			<div class="middle-content container-xxl p-0">
				<div class="row layout-top-spacing">
					<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
						<div class="widget widget-table-one dynamic_opt">
							<div class="widget-heading ">
								<h5 class="">Transactions</h5>
								<div class="task-action">
									<button type="button" class="btn btn-sm btn-primary" onclick="modalAction({'modal_id' : 'modal-lg', 'call_function':'addProduct', 'form_id' : 'addProduct', 'title' : 'Add Product','fnsave':'saveProduct'});">
									<?=getIcon('plus')?>
									</button>
								</div>
							</div>
							<div class="widget-content do_wrapper">
								<div class="transactions-list t-info">
									<div class="t-item">
										<div class="t-company-name">
											<div class="t-icon">
												<div class="avatar">
													<span class="avatar-title">S</span>
												</div>
											</div>
											<div class="t-name">
												<h4>Shaun Park</h4>
												<p class="meta-date">10 Jan 1:00PM</p>
											</div>
										</div>
										<div class="t-rate rate-inc">
											<?=getIcon('edit')?>
											<?=getIcon('delete')?>
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
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</div>

<?php $this->load->view('includes/footer'); ?>

<script>
	$('.do_wrapper').each((index, element) => {
		new PerfectScrollbar(element);
	});
</script>