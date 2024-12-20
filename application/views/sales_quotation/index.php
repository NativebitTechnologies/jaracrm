
<?php $this->load->view('includes/header'); ?>
	
	<!--  BEGIN CONTENT AREA -->
	<div id="content" class="main-content">
		<div class="layout-px-spacing">
			<div class="middle-content container-xxl p-0">
				<div class="row layout-spacing layout-top-spacing" id="cancel-row">
					<div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
						<div class="widget-content widget-content-area br-8">
							<div class="listing-header">
								<div class="row">
									<div class="col-xl-4 col-lg-5 col-md-5 col-sm-7 filtered-list-search align-self-center">
										<form class="form-inline my-2 my-lg-0 justify-content-center">
											<div class="w-100">
												<input type="text" class="w-100 form-control product-search br-30" id="commanSerach" placeholder="Search...">
											</div>
										</form>
									</div>

									<div class="col-xl-8 col-lg-7 col-md-7 col-sm-5 text-sm-right text-center align-self-center">
										<div class="d-flex justify-content-sm-end justify-content-center">
											<div class="btn-group" role="group">
                                                <button type="button" id="pendingQuotationList" class="btn btn-outline-danger status-tab" onclick="tabLoading('pendingQuotationList');" data-url="<?=base_url('salesQuotation/getSalesQuotationListing');?>" data-length="20" data-post_data='{"status":0}'>Pending</button>

                                                <button type="button" id="approvedQuotationList" class="btn btn-outline-warning status-tab" onclick="tabLoading('approvedQuotationList');" data-url="<?=base_url('salesQuotation/getSalesQuotationListing');?>" data-length="20" data-post_data='{"status":1}'>Approved</button>

                                                <button type="button" id="completeQuotationList" class="btn btn-outline-success status-tab" onclick="tabLoading('completeQuotationList');" data-url="<?=base_url('salesQuotation/getSalesQuotationListing');?>" data-length="20" data-post_data='{"status":2}'>Completed</button>

												<button type="button" class="btn btn-outline-primary" onclick="reloadTransaction();"><?=getIcon('refresh')?></button>

												<?php $addParam = "{'postData':{},'modal_id' : 'modal-xxl', 'call_function':'addSalesQuotation', 'form_id' : 'quotationForm', 'title' : 'Add Sales Quotation'}"; ?>
												<button type="button" class="btn btn-outline-primary" onclick="modalAction(<?=$addParam?>);"><?=getIcon('plus')?></button>
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<div class="table-responsive table-scroll lazy-wrapper">
								<table id="sales-quotation-list" class="table dataTable dt-table-hover table-striped table-fixed" style="width:100%">
									<thead class="gradient-theme">
										<tr>
											<th class="checkbox-column"> # </th>
											<th>Order No.</th>
											<th>Order Date</th>
											<th>Party Name</th>
											<th>Sales Executive</th>
											<th>Taxable Amount</th>
											<th>GST Amount</th>
											<th>Net Amount</th>
											<th class="text-center">Actions</th>
										</tr>
									</thead>
									<tbody id="salesQuotationList" class="lazy-load-trans" data-url="<?=base_url('salesQuotation/getSalesQuotationListing');?>" data-length="20" data-post_data='{"status":0}'>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</div>

<?php $this->load->view('includes/footer'); ?>