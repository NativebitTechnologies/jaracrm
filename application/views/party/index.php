
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
												<button type="button" class="btn btn-outline-primary bs-tooltip"  data-bs-placement="bottom" title="Refresh" onclick="reloadTransaction();"><?=getIcon('refresh')?></button>	
												<?php $addParam = "{'postData':{'party_type' : 1},'modal_id' : 'modal-xl', 'call_function':'addParty', 'form_id' : 'partyForm', 'title' : 'Add Customer'}"; ?>
												<button type="button" class="btn btn-outline-primary bs-tooltip"  data-bs-placement="bottom" title="Add Customer" onclick="modalAction(<?=$addParam?>);"><?=getIcon('user_add')?></button>													
												<!--<div class="btn-group" role="group">
													<button id="btnGroupDrop1" type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
														Dropdown
														<?=getIcon('chevron_down')?>
													</button>
													<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
													<li><a class="dropdown-item" href="#">Dropdown link</a></li>
													<li><a class="dropdown-item" href="#">Dropdown link</a></li>
													</ul>
												</div>-->
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<div class="table-responsive table-scroll lazy-wrapper">
								<table id="invoice-list" class="table dataTable dt-table-hover table-striped table-fixed" style="width:100%">
									<thead class="gradient-theme">
										<tr>
											<th class="checkbox-column"> # </th>
											<th>Party Code</th>
											<th>Party Name</th>
											<th>Business Type</th>
											<th>Contact Person</th>
											<th>Contact No.</th>
											<th>Whatsapp No.</th>
											<th>Sales Executive</th>
											<th>District</th>
											<th>City</th>
											<th class="text-center">Actions</th>
										</tr>
									</thead>
									<tbody id="partyList" class="lazy-load-trans" data-url="<?=base_url('parties/getPartyListing');?>" data-length="20" data-post_data='{"party_type" : 1}'>
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
