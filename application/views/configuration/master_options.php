
<?php $this->load->view('includes/header'); ?>
<link href="<?=base_url()?>assets/src/assets/css/light/widgets/modules-widgets.css" rel="stylesheet" type="text/css">  
	
	<!--  BEGIN CONTENT AREA  -->
	<div id="content" class="main-content">
		<div class="layout-px-spacing">
			<div class="middle-content container-xxl p-0 config-box">
				<div class="row layout-top-spacing">
					<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
						<div class="widget widget-table-one dynamic_opt p-0">
							<div class="widget-heading rounded-tp-2 mb-0 gradient-theme text-white">
								<h5 class="text-white box_title">Business Type</h5>
								<div class="task-action">
									<a href="javascript:void(0)" class="addBusinessType" data-modal_id="modal-md" data-call_function="addBusinessType" data-form_id="addBusinessType" data-title="Add Business Type" data-fnsave="saveBusinessType" onclick="showModal(this);"><span class="badge bg-warning text-dark flex-fill border-light border-1"><?=getIcon('plus')?> Add</span></a>	
									<!--<a href="javascript:void(0)" onclick="modalAction({'modal_id' : 'modal-md', 'call_function':'addBusinessType', 'form_id' : 'addBusinessType', 'title' : 'Add Business Type','fnsave':'saveBusinessType'});"><span class="badge bg-warning text-dark flex-fill border-light border-1"><?=getIcon('plus')?> Add</span></a>-->
								</div>
							</div>
							<div class="widget-content do_wrapper bt_list pad-15"><?=$businessList?></div>
						</div>
					</div>
					
					<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
						<div class="widget widget-table-one dynamic_opt p-0">
							<div class="widget-heading rounded-tp-2 mb-0 gradient-theme  text-white">
								<h5 class="text-white">Lead Stages</h5>
								<div class="task-action">
									<a href="javascript:void(0)" class="addLeadStage" data-modal_id="modal-md" data-call_function="addLeadStages" data-form_id="addLeadStages" data-title="Add Lead Stages" data-fnsave="saveLeadStages" onclick="showModal(this);"><span class="badge bg-warning text-dark flex-fill border-light border-1"><?=getIcon('plus')?> Add</span></a>	
									<!--<a href="javascript:void(0)" class="addLeadStage" onclick="modalAction({'modal_id' : 'modal-md', 'call_function':'addLeadStages', 'form_id' : 'addLeadStages', 'title' : 'Add Lead Stages','fnsave':'saveLeadStages'},this);"><span class="badge bg-warning text-dark flex-fill border-light border-1"><?=getIcon('plus')?> Add</span></a>-->
								</div>
							</div>
							<div class="widget-content do_wrapper ls_list pad-15"><?=$stageList?></div>
						</div>
					</div>
				
					<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
						<div class="widget widget-table-one dynamic_opt p-0">
							<div class="widget-heading rounded-tp-2 mb-0 gradient-theme  text-white">
								<h5 class="text-white">Source</h5>
								<div class="task-action">
									<a href="javascript:void(0)" class="addSource" data-modal_id="modal-md" data-type="1" data-call_function="addMasterOptions" data-form_id="addSource" data-title="Add Source" data-fnsave="saveMasterOptions" onclick="showModal(this);"><span class="badge bg-warning text-dark flex-fill border-light border-1"><?=getIcon('plus')?> Add</span></a>
									<!--<a href="javascript:void(0)" onclick="modalAction({'postData':{'type' : 1},'modal_id' : 'modal-md', 'call_function':'addMasterOptions', 'form_id' : 'addSource', 'title' : 'Add Source','fnsave':'saveMasterOptions'});"><span class="badge bg-warning text-dark flex-fill border-light border-1"><?=getIcon('plus')?> Add</span></a>-->
								</div>
							</div>
							<div class="widget-content do_wrapper source_list pad-15">
								<?=(!empty($moList['source']) ? $moList['source']  : "" ) ?>
							</div>
						</div>
					</div>
					
					<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
						<div class="widget widget-table-one dynamic_opt p-0">
							<div class="widget-heading rounded-tp-2 mb-0 gradient-theme  text-white">
								<h5 class="text-white">Lost Reason</h5>
								<div class="task-action">
									<a href="javascript:void(0)" class="addSource" data-modal_id="modal-md" data-type="2" data-call_function="addMasterOptions" data-form_id="addLostReason" data-title="Add Lost Reason" data-fnsave="saveMasterOptions" onclick="showModal(this);"><span class="badge bg-warning text-dark flex-fill border-light border-1"><?=getIcon('plus')?> Add</span></a>
									<!--<a href="javascript:void(0)" onclick="modalAction({'postData':{'type' : 2},'modal_id' : 'modal-md', 'call_function':'addMasterOptions', 'form_id' : 'addLostReason', 'title' : 'Add Lost Reason','fnsave':'saveMasterOptions','js_store_fn':'customStore'});"><span class="badge bg-warning text-dark flex-fill border-light border-1"><?=getIcon('plus')?> Add</span></a>-->
								</div>
							</div>
							<div class="widget-content do_wrapper lost_reason_list pad-15">
								<?=(!empty($moList['lost_reason']) ? $moList['lost_reason']  : "" ) ?>
							</div>
						</div>
					</div>
					
					<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
						<div class="widget widget-table-one dynamic_opt p-0">
							<div class="widget-heading rounded-tp-2 mb-0 gradient-theme  text-white">
								<h5 class="text-white">Expense Type</h5>
								<div class="task-action">
									<a href="javascript:void(0)" class="addSource" data-modal_id="modal-md" data-type="3" data-call_function="addMasterOptions" data-form_id="addExpenseType" data-title="Add Expense Type" data-fnsave="saveMasterOptions" onclick="showModal(this);"><span class="badge bg-warning text-dark flex-fill border-light border-1"><?=getIcon('plus')?> Add</span></a>
									<!--<a href="javascript:void(0)" onclick="modalAction({'postData':{'type' : 3},'modal_id' : 'modal-md', 'call_function':'addMasterOptions', 'form_id' : 'addExpenseType', 'title' : 'Add Expense Type','fnsave':'saveMasterOptions','js_store_fn':'customStore','res_function':''});"><span class="badge bg-warning text-dark flex-fill border-light border-1"><?=getIcon('plus')?> Add</span></a>-->
								</div>
							</div>
							<div class="widget-content do_wrapper expense_type_list pad-15">
								<?=(!empty($moList['expense_type']) ? $moList['expense_type']  : "" ) ?>
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