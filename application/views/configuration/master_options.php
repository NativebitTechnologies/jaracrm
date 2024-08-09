
<?php $this->load->view('includes/header'); ?>
<link href="<?=base_url()?>assets/src/assets/css/light/widgets/modules-widgets.css" rel="stylesheet" type="text/css">  
	
	<!--  BEGIN CONTENT AREA  -->
	<div id="content" class="main-content">
		<div class="layout-px-spacing">
			<div class="middle-content container-xxl p-0">
				<div class="row layout-top-spacing">
					<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
						<div class="widget widget-table-one dynamic_opt p-0">
							<div class="widget-heading pad-15 mb-0 bg-light-peach bb-solid border-grey">
								<h5 class="">Business Type</h5>
								<div class="task-action">
									<button type="button" class="btn btn-sm btn-primary" onclick="modalAction({'modal_id' : 'modal-md', 'call_function':'addBusinessType', 'form_id' : 'addBusinessType', 'title' : 'Add Business Type','fnsave':'saveBusinessType'});">
									<?=getIcon('plus')?>
									</button>
								</div>
							</div>
							<div class="widget-content do_wrapper pad-15">
								<?php
									foreach($businessList as $row){
										$editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-md', 'form_id' : 'editBusinessType', 'title' : 'Update Business Type','call_function':'editBusinessType','fnsave' : 'saveBusinessType'}";
										$editButton = '<a class="permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');">'.getIcon('edit').'</a>';

										$deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Business Type','fndelete':'deleteBusinessType'}";
										$deleteButton = '<a class="permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down">'.getIcon('delete').'</a>';
		
										echo '<div class="transactions-list t-info">
											<div class="t-item">
												<div class="t-company-name">
													<div class="t-icon">
														<div class="avatar">
															<span class="avatar-title">'.$row->type_name[0].'</span>
														</div>
													</div>
													<div class="t-name">
														<h4>'.$row->type_name.' - '.$row->parentType.'</h4>
														<p class="meta-date">'.$row->remark.'</p>
													</div>
												</div>
												<div class="t-rate rate-inc">
													'.$editButton.$deleteButton.'
												</div>
											</div>
										</div>';
									}
								?>
							</div>
						</div>
					</div>
					
					<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
						<div class="widget widget-table-one dynamic_opt p-0">
							<div class="widget-heading pad-15 mb-0 bg-light-peach bb-solid border-grey">
								<h5 class="">Lead Stages</h5>
								<div class="task-action">
									<button type="button" class="btn btn-sm btn-primary" onclick="modalAction({'modal_id' : 'modal-md', 'call_function':'addLeadStages', 'form_id' : 'addLeadStages', 'title' : 'Add Lead Stages','fnsave':'saveLeadStages'});">
									<?=getIcon('plus')?>
									</button>
								</div>
							</div>
							<div class="widget-content do_wrapper pad-15">
								<?php
									foreach($stageList as $row){
										$editButton = $deleteButton = "";
										if(empty($row->is_system)){
											$editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-md', 'form_id' : 'editLeadStages', 'title' : 'Update Lead Stages','call_function':'editLeadStages','fnsave' : 'saveLeadStages'}";
											$editButton = '<a class="permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');">'.getIcon('edit').'</a>';

											$deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'LeadStages','fndelete':'deleteLeadStages'}";
											$deleteButton = '<a class="permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down">'.getIcon('delete').'</a>';
										}
										echo '<div class="transactions-list t-info">
											<div class="t-item">
												<div class="t-company-name">
													<div class="t-icon">
														<div class="avatar">
															<span class="avatar-title">'.$row->sequence.'</span>
														</div>
													</div>
													<div class="t-name">
														<h4>'.$row->stage_type.'</h4>
														<p class="meta-date"></p>
													</div>
												</div>
												<div class="t-rate rate-inc">
													'.$editButton.$deleteButton.'
												</div>
											</div>
										</div>';
									}
								?>
							</div>
						</div>
					</div>

					<?php
						$source = ''; $lost_reason = ''; $expense_type = ''; $leave_type = ''; $task_stage = '';
						foreach($selectOptionList as $row){
							$editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-md', 'form_id' : 'editMasterOption', 'title' : 'Update','call_function':'editMasterOption','fnsave' : 'saveMasterOptions'}";
							$editButton = '<a class="permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');">'.getIcon('edit').'</a>';

							$deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Record','fndelete':'deleteMasterOption'}";
							$deleteButton = '<a class="permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down">'.getIcon('delete').'</a>';
	
							if($row->type == 1){
								$source .= '<div class="transactions-list t-info">
									<div class="t-item">
										<div class="t-company-name">
											<div class="t-icon">
												<div class="avatar">
													<span class="avatar-title">'.$row->label[0].'</span>
												</div>
											</div>
											<div class="t-name">
												<h4>'.$row->label.'</h4>
												<p class="meta-date">'.$row->remark.'</p>
											</div>
										</div>
										<div class="t-rate rate-inc">
											'.$editButton.$deleteButton.'
										</div>
									</div>
								</div>';
							}elseif($row->type == 2){
								$lost_reason .= '<div class="transactions-list t-info">
									<div class="t-item">
										<div class="t-company-name">
											<div class="t-icon">
												<div class="avatar">
													<span class="avatar-title">'.$row->label[0].'</span>
												</div>
											</div>
											<div class="t-name">
												<h4>'.$row->label.'</h4>
												<p class="meta-date">'.$row->remark.'</p>
											</div>
										</div>
										<div class="t-rate rate-inc">
											'.$editButton.$deleteButton.'
										</div>
									</div>
								</div>';
							}elseif($row->type == 3){
								$expense_type .= '<div class="transactions-list t-info">
									<div class="t-item">
										<div class="t-company-name">
											<div class="t-icon">
												<div class="avatar">
													<span class="avatar-title">'.$row->label[0].'</span>
												</div>
											</div>
											<div class="t-name">
												<h4>'.$row->label.'</h4>
												<p class="meta-date">'.$row->remark.'</p>
											</div>
										</div>
										<div class="t-rate rate-inc">
											'.$editButton.$deleteButton.'
										</div>
									</div>
								</div>';
							
							//}elseif($row->type == 1){
							
							//}elseif($row->type == 1){
							
							} 
						}
					?>
				
					<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
						<div class="widget widget-table-one dynamic_opt p-0">
							<div class="widget-heading pad-15 mb-0 bg-light-peach bb-solid border-grey">
								<h5 class="">Source</h5>
								<div class="task-action">
									<button type="button" class="btn btn-sm btn-primary" onclick="modalAction({'postData':{'type' : 1},'modal_id' : 'modal-md', 'call_function':'addMasterOptions', 'form_id' : 'addSource', 'title' : 'Add Source','fnsave':'saveMasterOptions'});">
									<?=getIcon('plus')?>
									</button>
								</div>
							</div>
							<div class="widget-content do_wrapper pad-15">
								<?= $source ?>
							</div>
						</div>
					</div>
					
					<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
						<div class="widget widget-table-one dynamic_opt p-0">
							<div class="widget-heading pad-15 mb-0 bg-light-peach bb-solid border-grey">
								<h5 class="">Lost Reason</h5>
								<div class="task-action">
									<button type="button" class="btn btn-sm btn-primary" onclick="modalAction({'postData':{'type' : 2},'modal_id' : 'modal-md', 'call_function':'addMasterOptions', 'form_id' : 'addLostReason', 'title' : 'Add Lost Reason','fnsave':'saveMasterOptions','js_store_fn':'customStore'});">
									<?=getIcon('plus')?>
									</button>
								</div>
							</div>
							<div class="widget-content do_wrapper pad-15">
								<?= $lost_reason ?>
							</div>
						</div>
					</div>
					
					<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
						<div class="widget widget-table-one dynamic_opt p-0">
							<div class="widget-heading pad-15 mb-0 bg-light-peach bb-solid border-grey">
								<h5 class="">Expense Type</h5>
								<div class="task-action">
									<button type="button" class="btn btn-sm btn-primary" onclick="modalAction({'postData':{'type' : 3},'modal_id' : 'modal-md', 'call_function':'addMasterOptions', 'form_id' : 'addExpenseType', 'title' : 'Add Expense Type','fnsave':'saveMasterOptions','js_store_fn':'customStore','res_function':''});">
									<?=getIcon('plus')?>
									</button>
								</div>
							</div>
							<div class="widget-content do_wrapper pad-15">
								<?= $expense_type ?>
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

function getMasterOptionHtml(data,formId="dispatchPlan"){ 
    if(data.status==1){
        var postData = {'postData':{'type':data.type},'table_id':"dispatchPlanTable",'tbody_id':'','tfoot_id':'','fnget':'getMasterOptionHtml'};
        getTransHtml(postData);
    }else {
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) {$("."+key).html(value);});
        }
    }
}
</script>