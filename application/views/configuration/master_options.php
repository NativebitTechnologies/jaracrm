
<?php $this->load->view('includes/header'); ?>
<link href="<?=base_url()?>assets/src/assets/css/light/widgets/modules-widgets.css" rel="stylesheet" type="text/css">  
	
	<!--  BEGIN CONTENT AREA  -->
	<div id="content" class="main-content">
		<div class="layout-px-spacing">
			<div class="middle-content container-xxl p-0">
				<div class="row layout-top-spacing">
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
						<div class="widget widget-table-one dynamic_opt">
							<div class="widget-heading ">
								<h5 class="">Source</h5>
								<div class="task-action">
									<button type="button" class="btn btn-sm btn-primary" onclick="modalAction({'postData':{'type' : 1},'modal_id' : 'modal-md', 'call_function':'addMasterOptions', 'form_id' : 'addSource', 'title' : 'Add Source','fnsave':'saveMasterOptions','js_store_fn':'customStore','res_function':'getMasterOptionHtml'});">
									<?=getIcon('plus')?>
									</button>
								</div>
							</div>
							<div class="widget-content do_wrapper">
								<?= $source ?>
							</div>
						</div>
					</div>
					
					<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
						<div class="widget widget-table-one dynamic_opt">
							<div class="widget-heading ">
								<h5 class="">Lost Reason</h5>
								<div class="task-action">
									<button type="button" class="btn btn-sm btn-primary" onclick="modalAction({'postData':{'type' : 2},'modal_id' : 'modal-md', 'call_function':'addMasterOptions', 'form_id' : 'addLostReason', 'title' : 'Add Lost Reason','fnsave':'saveMasterOptions','js_store_fn':'customStore'});">
									<?=getIcon('plus')?>
									</button>
								</div>
							</div>
							<div class="widget-content do_wrapper">
								<?= $lost_reason ?>
							</div>
						</div>
					</div>
					
					<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
						<div class="widget widget-table-one dynamic_opt">
							<div class="widget-heading ">
								<h5 class="">Expense Type</h5>
								<div class="task-action">
									<button type="button" class="btn btn-sm btn-primary" onclick="modalAction({'postData':{'type' : 3},'modal_id' : 'modal-md', 'call_function':'addMasterOptions', 'form_id' : 'addExpenseType', 'title' : 'Add Expense Type','fnsave':'saveMasterOptions','js_store_fn':'customStore','res_function':''});">
									<?=getIcon('plus')?>
									</button>
								</div>
							</div>
							<div class="widget-content do_wrapper">
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
        var postData = {'postData':{'type':data.type},'table_id':"dispatchPlanTable",'tbody_id':'','tfoot_id':'','fnget':'getDispatchHtml'};
        getTransHtml(postData);
    }else {
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) {$("."+key).html(value);});
        }
    }
}
</script>