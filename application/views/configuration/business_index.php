
<?php $this->load->view('includes/header'); ?>
<!--  BEGIN CUSTOM STYLE FILE  -->
<link href="<?=base_url();?>assets/src/listing.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url();?>assets/src/dt_table.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/src/plugins/css/light/table/datatable/dt-global_style.css">
<!--  END CUSTOM STYLE FILE  -->

	
	<!--  BEGIN CONTENT AREA  -->
	<div id="content" class="main-content">
		<div class="layout-px-spacing">
			<div class="middle-content container-xxl p-0">
				<div class="row layout-spacing layout-top-spacing" id="cancel-row">
					<div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
						<div class="widget-content widget-content-area br-8">
							<div class="listing-header">
								<div class="row">
									<div class="col-xl-4 col-lg-5 col-md-5 col-sm-7 filtered-list-search align-self-center">
										<form class="form-inline my-2 my-lg-0">
											<div class="">
												<?=getIcon('search')?>
												<input type="text" class="form-control product-search" id="commanSerach" placeholder="Search...">
											</div>
										</form>
									</div>

									<div class="col-xl-8 col-lg-7 col-md-7 col-sm-5 text-sm-right text-center align-self-center">
										<div class="d-flex justify-content-sm-end justify-content-center">
											<div class="btn-group" role="group">
												<button type="button" class="btn btn-outline-primary" onclick="reloadTransaction();"><?=getIcon('refresh')?></button>	
												<?php $addParam = "{'modal_id' : 'modal-md', 'call_function':'addBusinessType', 'form_id' : 'addBusinessType', 'title' : 'Add Business Type', 'fnsave' : 'saveBusinessType'}"; ?>
												<button type="button" class="btn btn-outline-primary" onclick="modalAction(<?=$addParam?>);"><?=getIcon('plus')?></button>
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<div class="table-responsive table-scroll">
								<table id="business-list" class="table dataTable table-fixed dt-table-hover table-striped" style="width:100%">
									<thead class="thead-info1 gradient-theme">
										<tr>
											<th class="checkbox-column"> # </th>
											<th>Business Type</th>
											<th>Parent Type</th>
											<th>Remark</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody id="businessList" class="lazy-load-trans vh-20" data-url="<?=base_url('configuration/getBusinessTypeListing');?>" data-length="20" data-postData=""  >
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

<!-- END GLOBAL MANDATORY SCRIPTS -->
<script src="<?=base_url();?>assets/src/lazy-load.js?v="<?=time()?>></script>