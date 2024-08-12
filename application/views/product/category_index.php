<?php $this->load->view('includes/header'); ?>
	
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
										<form class="form-inline my-2 my-lg-0 justify-content-center">
											<div class="w-100">
												<input type="text" class="w-100 form-control product-search br-30" id="commanSerach" placeholder="Search...">
											</div>
										</form>
									</div>
									<div class="col-xl-8 col-lg-7 col-md-7 col-sm-5 text-sm-right text-center align-self-center">
										<div class="d-flex justify-content-sm-end justify-content-center">
											<div class="btn-group" role="group">
												<button type="button" class="btn btn-outline-primary" onclick="reloadTransaction();"><?=getIcon('refresh')?></button>	
												<?php $addParam = "{'postData':{'ref_id' : ".$main_ref_id."},'modal_id' : 'modal-md', 'call_function':'addCategory', 'form_id' : 'addCategory', 'title' : 'Add Category','fnsave':'saveCategory'}"; ?>
												<button type="button" class="btn btn-outline-primary" onclick="modalAction(<?=$addParam?>);"><?=getIcon('plus')?></button>													
												<a type="button" class="btn btn-outline-primary" href="<?=base_url("product/categoryIndex/" . $main_ref_id)?>"><?=getIcon('arrow_left')?></a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="table-responsive table-scroll lazy-wrapper">
								<table id="category-list" class="table dataTable dt-table-hover table-striped table-fixed" style="width:100%">
									<thead class="gradient-theme">
										<tr>
											<th class="checkbox-column"> # </th>
											<th>Category Name</th>
											<th>Parent Category</th>
											<th>Is Final (Last)?</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody id="productList" class="lazy-load-trans" data-url="<?=base_url('product/getCategoryListing');?>" data-length="20" data-post_data='{"ref_id":"<?=$ref_id?>"}'>
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