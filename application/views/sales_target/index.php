<?php $this->load->view('includes/header'); ?>
	<div id="content" class="main-content">
		<form id="targetDataForm">
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
											<div class="d-flex justify-content-lg-end justify-content-center">
												<div class="btn-group" role="group">
													<select name="month" id="month" class="form-control">	
														<?php   
															foreach($monthList as $row): 
																$selected = ($row['val'] == date('Y-m-01')) ? "selected" : "";
																echo "<option value='".$row['val']."' ".$selected.">".$row['label']."</option>";
															endforeach; 
														?>
													</select>
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
										<tbody id="targetList" class="lazy-load-trans" data-url="<?=base_url('salesTarget/getSalesTargetDetils');?>" data-length="20" data-post_data='{"month":"<?=date('Y-m-01')?>"}'>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="bottomBtn bottom-15 permission-write">
<?php $postData = "{'formId':'targetDataForm','fnsave':'saveTargets','table_id':'targetTable'}"; ?>
    <button type="button" class=" btn btn-primary btn-round btn-outline-dashed font-bold permission-write save-form" style="letter-spacing:1px;" onclick="customStore(<?=$postData?>);">SAVE TARGET</button>
</div>

<?php $this->load->view('includes/footer'); ?>
<script>
$(document).ready(function(){
    initSelectBox('id','month');  
	
	$(document).on('change',"#month",function(){
		var month = $(this).val();
		
		var tabId = 'targetList';
		load_flag = 0;ajax_call = false;
		$(".lazy-load-trans").removeData('url');
		$(".lazy-load-trans").data('url',$("#"+tabId).data('url'));

		$(".lazy-load-trans").removeData('post_data');
		$(".lazy-load-trans").data('post_data',{month:month} || "{}");

		$(".lazy-load-trans").removeData('length');
		$(".lazy-load-trans").data('length',($("#"+tabId).data('length') || 20));

		$(".lazy-load-trans").html('');
		if(tblScroll){tblScroll.update();}
		loadTransaction();
	});
});
</script>