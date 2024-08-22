<?php $this->load->view('includes/header'); ?>
	<div id="content" class="main-content">
		<div class="layout-px-spacing">
			<div class="middle-content container-xxl p-0"> 
				<div class="row layout-spacing layout-top-spacing" id="cancel-row">
					<div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
						<div class="widget-content widget-content-area br-8">
							<form id="targetDataForm">
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
													<button id="btnGroupDrop1" type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
														Dropdown
														<?=getIcon('chevron_down')?>
													</button>
													<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
														<?php   
															foreach($monthList as $row): 
																echo "<li><a class='dropdown-item' href='javascript:void(0);' onclick='tabLoading(".$row['val'].");' data-url='".base_url('executiveTarget/getSalesTargetDetils')."' data-length='20' data-post_data='{'month':".$row['val']."}'>".$row['label']."</a></li>";
															endforeach; 
														?>
													</ul>
												</div>
											</div>
										</div>								
									</div>                                         
								</div>
								
								<div class="table-responsive table-scroll lazy-wrapper">
									<table id="target-list" class="table dataTable dt-table-hover table-striped table-fixed" style="width:100%">
										<thead class="gradient-theme">
											<tr>
												<th style="width:5%;">#</th>
												<th>Employee Code</th>
												<th>Employee Name</th>
												<th>Designation</th>
												<th>Sales Zone</th>
												<th>New Lead</th>
												<th>Amount</th>
											</tr>
										</thead>
										<tbody id="targetList" class="lazy-load-trans" data-url="" data-length="20" data-post_data=''>
										</tbody>
									</table>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="bottomBtn bottom-15 permission-write">
<?php $postData = "{'formId':'targetDataForm','fnsave':'saveTargets','table_id':'targetTable'}"; ?>
    <button type="button" class=" btn btn-primary btn-round btn-outline-dashed font-bold permission-write save-form" style="letter-spacing:1px;" onclick="customStore(<?=$postData?>);">SAVE TARGET</button>
</div>

<?php $this->load->view('includes/footer'); ?>