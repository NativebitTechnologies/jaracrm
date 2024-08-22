<?php $this->load->view('includes/header'); ?>

<!--  BEGIN CUSTOM STYLE FILE  -->
<link href="<?=base_url();?>assets/src/assets/css/light/widgets/modules-widgets.css" rel="stylesheet" type="text/css">  
<link href="<?=base_url();?>assets/src/assets/css/light/apps/todolist.css" rel="stylesheet" type="text/css" />
<!--  END CUSTOM STYLE FILE  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="middle-content container-xxl p-0 crm_desk">
    
                    <div class="row layout-top-spacing">
                        <div class="col-xl-12 col-lg-12 col-md-12">
    
                            <div class="mail-box-container lazy-wrapper">
                                <div class="mail-overlay"></div>
    
                                <div class="tab-title">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-12 text-center mb-2">
                                            <a href="javascript:void(0);" class="dropdown-toggle btn gradient-theme d-block d-flex justify-content-between" id="leadShortDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Sort By <span><?=getIcon('sliders')?></span>
                                            </a>
                                            <div class="dropdown-menu position-absolute" aria-labelledby="leadShortDropdown">
                                                <div class="dropdown-item">
                                                    <a href="#"><?=getIcon('user')?> Profile</a>
                                                </div>
                                                <div class="dropdown-item">
                                                    <a href="#"><?=getIcon('inbox')?> Inbox</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-12 ps-0 pe-0">
                                            <div class="todoList-sidebar-scroll mt-1">
                                                <ul class="nav nav-pills d-block" id="pills-tab" role="tablist">
												<?php
													if(!empty($stageList)){
														foreach($stageList as $row) {
															if($row->sequence != 1){ 
                                                                $icon = getIcon('sun');$active='';
                                                                if($row->lead_stage==1){$icon = getIcon('thumbs_up');$active='active';} // New
                                                                if($row->lead_stage==11){$icon = getIcon('thumbs_down');} // Lost
                                                ?>
																<li class="nav-item">
																	<a class="nav-link list-actions stageFilter <?=$active?>" id="lead_stage<?=$row->lead_stage?>" data-toggle="pill" href="#" role="tab" onclick="tabLoading('lead_stage<?=$row->lead_stage?>');" aria-selected="true" data-url="<?=base_url('parties/getPartyListing');?>" data-length="15" data-post_data='{"party_type" : 2,"lead_stage" : <?=$row->lead_stage?> }'><?=$icon?> <?=$row->stage_type?></a>
																</li>
															<?php }
														}
													}
												?>
                                                    <li class="nav-item">
                                                        <a class="nav-link list-actions stageFilter" id="not_assigned" data-toggle="pill" href="#" role="tab" onclick="tabLoading('not_assigned');" aria-selected="true" data-url="<?=base_url('parties/getPartyListing');?>" data-length="15" data-post_data='{"party_type" : 2,"executive_id" : 0 }'><?=getIcon('user_close')?> Not Assigned</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12 col-sm-12 col-12 text-center">
                                            <?php $addParam = "{'postData':{'party_type' : 2},'modal_id' : 'modal-xl', 'call_function':'addParty', 'form_id' : 'partyForm', 'title' : 'Add Lead'}"; ?>
                                            <button class="btn btn-secondary" id="addTask" type="button" onclick="modalAction(<?=$addParam?>);"><?=getIcon('plus')?> New Lead</button>
                                        </div>
                                    </div>
                                </div>
    
                                <div id="todo-inbox" class="accordion todo-inbox">
                                    <div class="search">
                                        <input type="text" class="form-control input-search" placeholder="Search Task...">
                                    </div>
                            
                                    <div class="todo-box">
                                        <div id="ct" class="todo-box-scroll searchable-container lazy-load-trans" data-url="<?=base_url('parties/getPartyListing');?>" data-length="15" data-post_data='{"party_type" : 2,"lead_stage" : 1}' ></div>
                                        
                                    </div>
    
                                </div>                                    
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--  END CONTENT AREA  -->

<?php $this->load->view('includes/footer'); ?>

<!--  BEGIN CUSTOM JS FILE  -->
<script src="<?=base_url();?>assets/src/assets/js/widgets/modules-widgets.js"></script>
<script src="<?=base_url();?>assets/src/assets/js/apps/todoList.js"></script>
<script>

$(document).on('click',".leadStage",function(){
    var lead_stage = $(this).data('lead_stage') || "";
    var party_id = $(this).data('party_id') || "";

    $.ajax({
        url : base_url + controller + '/changeLeadStages',
        type:'post',
        data: {id : party_id, lead_stage : lead_stage},
        dataType : 'json',
    }).done(function(response){
        $(".stageFilter.active").trigger("click");
    });
});
</script>
<!--  END CUSTOM JS FILE  -->