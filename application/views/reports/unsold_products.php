
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
										<div class="d-flex justify-content-sm-end justify-content-end">
                                            <form id="filter_form" data-page_name="unsoldProductFilters" style="width:80%;">
                                                <div class="row">
                                                    <div class="col-md-12">
														<div class="input-group">
															<label class="text-left" for="executive_id" style="width:40%;">Category</label>
															<label class="text-left" for="executive_id" style="width:20%">Unsold Days</label>
															<label for=""></label>
														</div>

                                                        <div class="input-group">
                                                            <div class="input-group-append" style="width:40%;">
                                                                <select name="category_id" id="category_id" class="form-control selectBox" >
                                                                    <option value="">ALL Category</option>
                                                                    <?=getItemCategoryListOption($categoryList)?>
                                                                </select>
                                                            </div>  
															
															<div class="input-group-append" style="width:20%">
																<input type="text" name="unsold_days" id="unsold_days" class="form-control numericOnly" value="10">
															</div>

                                                            <button type="button" id="applyFilter" class="btn btn-outline-primary bs-tooltip"  data-bs-placement="bottom" title="Load" ><?=getIcon('refresh')?> Load</button>

															<button type="button" id="pdf" class="btn btn-outline-primary bs-tooltip"  data-bs-placement="bottom" title="Load" ><?=getIcon('printer')?> PDF</button>
                                                        </div>
                                                    </div>
                                                </div>											    
                                            </form> 
										</div>
									</div>
								</div>
							</div>
							
							<div class="table-responsive table-scroll lazy-wrapper">
								<table id="inactive-party-list" class="table dataTable dt-table-hover table-striped table-fixed" style="width:100%">
									<thead class="gradient-theme">
										<tr>
                                            <th>#</th>
											<th>Product code</th>
											<th>Product Name</th>
											<th>Category</th>
											<th>HSN Code</th>
											<th>GST(%)</th>
											<th>Price <small>(Exc. Tax)<small></th>
											<th>MRP <small>(Inc. Tax)<small></th>
											<th>Unsold Days</th>
											<th>Last Sold Date</th>
										</tr>
									</thead>
									<tbody id="unsoldProductList" class="lazy-load-trans" data-url="<?=base_url('report/getUnsoldProductList');?>" data-length="20" data-post_data='' data-filter_page_name="unsoldProductFilters">
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


<?php $this->load->view('includes/footer'); ?>