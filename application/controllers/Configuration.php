<?php
class Configuration extends MY_Controller{
	
    private $business_index = "configuration/business_index";
    private $business_form = "configuration/business_form";
    private $terms_index = "configuration/terms_index";
    private $terms_form = "configuration/terms_form";
    private $masterOptions = "configuration/master_options";
    private $master_form = "configuration/master_form";
	private $stage_form = "configuration/stage_form";
	private $cf_form = "configuration/cf_form";
	private $cf_select = "configuration/cf_select";
	
	public $termsTypeArray = ["Purchase","Sales"];
	public $typeArray = ["","Source","Lost Reason","Expense Type"];
	public $moHeads = ['','source','lost_reason','expense_type','NA','N'];
	public $cfHeads = ['','product_udf','customer_udf'];

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Configuration";
		$this->data['headData']->controller = "configuration";
        $this->data['headData']->pageUrl = "configuration";
	}
	
	/********** Start Terms **********/
	public function termsIndex(){
		$this->data['headData']->pageTitle = "Terms & Conditions";
        $this->data['headData']->pageUrl = "configuration/termsIndex";
        $this->data['DT_TABLE'] = true;
        $this->load->view($this->terms_index,$this->data);
    }

    public function getTermsListing(){
        $data = $this->input->post(); 
        $partyList = $this->configuration->getTermsList($data);

        $tbody = "";$i=($data['start'] + 1);
        foreach($partyList as $row):
		    $editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-lg', 'form_id' : 'editTerms', 'title' : 'Update Terms','call_function':'editTerms','fnsave' : 'saveTerms'}";
			$editButton = '<a class="dropdown-item permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');">'.getIcon('edit').' Edit</a>';

			$deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Terms','fndelete':'deleteTerms'}";
			$deleteButton = '<a class="dropdown-item permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down">'.getIcon('delete').' Delete</a>';
	
            $tbody .= '<tr>
                <td class="checkbox-column"> '.$i.' </td>
                <td>'.$row->title.'</td>
                <td>'.$row->conditions.'</td>
                <td>'.str_replace(',',', ',$row->type).'</td>
                <td>
                    <div class="d-inline-block jpdm">
                        <a class="dropdown-toggle" href="#" role="button" id="elementDrodpown3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="elementDrodpown3" style="will-change: transform;">
                            '.$editButton.$deleteButton.'
                        </div>
                    </div>
                </td>
            </tr>';

            $i++;
        endforeach;

        $this->printJson(['status'=>1,'dataList'=>$tbody]);
    }

	public function addTerms(){
        $this->data['typeArray'] = $this->termsTypeArray;
        $this->load->view($this->terms_form,$this->data);	
	}

	public function saveTerms(){
		$data = $this->input->post();
		$errorMessage = array();		
        if(empty($data['title']))
			$errorMessage['title'] = "Title is required.";
        if(empty($data['conditions']))
			$errorMessage['conditions'] = "Conditions is required.";
        if(empty($data['type'])):
			$errorMessage['type'] = "Type is required.";
		endif;
        
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->configuration->saveTerms($data));
        endif;
	}
	
	public function editTerms(){
		$data = $this->input->post();
        $this->data['dataRow'] = $this->configuration->getTermsList($data);
		$this->data['typeArray'] = $this->termsTypeArray; 
        $this->load->view($this->terms_form,$this->data);
	}

	public function deleteTerms(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->configuration->deleteTerms(['id'=>$data['id']]));
        endif;
	}
	/********** End Terms **********/

	/********** Start Master Options **********/
    public function masterOptions(){
		$this->data['headData']->pageTitle = "Master Options";
        $this->data['headData']->pageUrl = "configuration/masterOptions";

        $this->data['moList'] = $this->getMasterOptionList(['ajaxCall'=>1,'separate'=>1]);
        $this->data['businessList'] = $this->getBusinessTypeList(['ajaxCall'=>1]);
		$this->data['stageList'] = $this->getLeadStagesList(['ajaxCall'=>1]);
		$this->data['fieldList'] = $this->getCustomFieldList(['ajaxCall'=>1,'separate'=>1]);

        $this->load->view($this->masterOptions,$this->data);
    }

	public function getMasterOptionList($param=[]){
		$postData = (!empty($param) ? $param : $this->input->post());
        $moList = $this->configuration->getMasterOption($postData);
        $responseHtml = "";$responseArr = Array();$responseArr['source'] = $responseArr['lost_reason'] = $responseArr['expense_type'] = "";
        foreach($moList as $row){
			$editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-md', 'form_id' : 'editMasterOption', 'title' : 'Update','call_function':'editMasterOption','fnsave' : 'saveMasterOptions'}";
			$editButton = '<a class="permission-modify mr-5" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');">'.getIcon('edit').'</a>';

			$deleteParam = "{'postData':{'id' : ".$row->id.",'type' : ".$row->type."},'message' : 'Record','fndelete':'deleteMasterOption'}";
			$deleteButton = '<a class="permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down">'.getIcon('delete').'</a>';
			
			$responseData =  '<div class="transactions-list t-info">
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
			$responseHtml .= $responseData;
			$responseArr[$this->moHeads[$row->type]] .= $responseData;
		}
		if(!empty($param)):
			if(!empty($param['separate'])):
				return $responseArr;
			else:
				return $responseHtml;
			endif;
		else:
        	$this->printJson(['status'=>1,'dataList'=>$responseHtml]);
		endif;
	}
	
	public function addMasterOptions(){
		$data = $this->input->post();
		$this->data['type'] = $data['type'];
		$this->data['type_name'] = $this->typeArray[$data['type']];
        $this->load->view($this->master_form, $this->data);
	}
	
	public function saveMasterOptions(){
		$postData = $this->input->post();
		$errorMessage = array();
        
		if(empty($postData['label'])){ 
			$errorMessage['label'] = "Please fill out this field.";
		}
        
		if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			$result['type'] = $postData['type'];
			$result = $this->configuration->saveMasterOption($postData);
			$result['responseEle'] = '.'.$this->moHeads[$postData['type']].'_list';
			$result['responseHtml'] = $this->getMasterOptionList(['ajaxCall'=>1,'type'=>$postData['type']]);
            $this->printJson($result);
        endif;
	}
	
	public function getMasterOptionHtml(){
		$data = $this->input->post(); $resData='';
		$selectOptionList = $this->configuration->getMasterOption(['type'=>$data['type']]);
		
		foreach($selectOptionList as $row){
			$editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-md', 'form_id' : 'editMasterOption', 'title' : 'Update','call_function':'editMasterOption','fnsave' : 'saveMasterOptions'}";
			$editButton = '<a class="permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');">'.getIcon('edit').'</a>';

			$deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Record','fndelete':'deleteMasterOption'}";
			$deleteButton = '<a class="permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down">'.getIcon('delete').'</a>';
	
			$resData .= '<div class="transactions-list t-info">
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
		}
		
		$this->printJson(['resData'=>$resData]);
	}
	
	public function editMasterOption(){
		$data = $this->input->post();
		$this->data['dataRow'] = $dataRow = $this->configuration->getMasterOption(['id'=>$data['id']]);
		$this->data['type_name'] = $this->typeArray[$dataRow->type];
        $this->load->view($this->master_form, $this->data);
	}
	
	public function deleteMasterOption(){
        $postData = $this->input->post();
        if(empty($postData['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
			$result = $this->configuration->deleteMasterOption(['id'=>$postData['id']]);
			$result['responseEle'] = '.'.$this->moHeads[$postData['type']].'_list';
			$result['responseHtml'] = $this->getMasterOptionList(['ajaxCall'=>1,'type'=>$postData['type']]);
			$this->printJson($result);
        endif;
	}
	/********** End Master Options **********/

	/********** Start Business Type **********/
    public function addBusinessType(){
		$this->data['businessList'] = $this->configuration->getBusinessTypeList();
        $this->load->view($this->business_form, $this->data);
    }

	public function getBusinessTypeList($param=[]){
		$postData = (!empty($param) ? $param : $this->input->post());
        $btList = $this->configuration->getBusinessTypeList($postData);
        $responseHtml = "";
        foreach($btList as $row){
			$editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-md', 'form_id' : 'editBusinessType', 'title' : 'Update Business Type','call_function':'editBusinessType','fnsave' : 'saveBusinessType'}";
			$editButton = '<a class="permission-modify mr-5" href="#" type="button" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');">'.getIcon('edit').'</a>';

			$deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Business Type','fndelete':'deleteBusinessType'}";
			$deleteButton = '<a class="permission-remove" href="#" type="button" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down">'.getIcon('delete').'</a>';
			
			$responseHtml .=  '<div class="transactions-list t-info">
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
		if(!empty($param)):
			return $responseHtml;
		else:
        	$this->printJson(['status'=>1,'dataList'=>$responseHtml]);
		endif;
	}

    public function saveBusinessType(){
        $postData = $this->input->post();
		$errorMessage = array();

        if(empty($postData['type_name'])){
			$errorMessage['type_name'] = "Type Name is required.";
        }
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			$result = $this->configuration->saveBusinessType($postData);
			$postData['ajaxCall']=1;
			$result['responseEle'] = '.bt_list';
			$result['responseHtml'] = $this->getBusinessTypeList(['ajaxCall'=>1]);
            $this->printJson($result);
        endif;
    }

    public function editBusinessType(){     
        $postData = $this->input->post(); $postData['result_type'] = 'row'; 
        $this->data['dataRow'] = $this->configuration->getBusinessTypeList($postData);
        $this->data['businessList'] = $this->configuration->getBusinessTypeList();
        $this->load->view($this->business_form, $this->data);
    }

	public function deleteBusinessType(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
			$result = $this->configuration->deleteBusinessType(['id'=>$data['id']]);
			$result['responseEle'] = '.bt_list';
			$result['responseHtml'] = $this->getBusinessTypeList(['ajaxCall'=>1]);
			$this->printJson($result);
        endif;
	}
	/********** End Business Type **********/
	
	/********** Start Lead Stages **********/
	public function addLeadStages(){
		$postData = $this->input->post();
		$seqData = $this->configuration->getMaxStageSequence();
		$this->data['next_seq_no'] = (!empty($seqData->next_seq_no) ? ($seqData->next_seq_no + 1) : 1);
		$this->load->view($this->stage_form, $this->data);
	}

	public function getLeadStagesList($param=[]){
		$postData = (!empty($param) ? $param : $this->input->post());
        $lsList = $this->configuration->getLeadStagesList($postData);
        $responseHtml = "";
        foreach($lsList as $row){
			$editButton = $deleteButton = "";
			if(empty($row->is_system)){
				$editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-md', 'form_id' : 'editLeadStages', 'title' : 'Update Lead Stages','call_function':'editLeadStages','fnsave' : 'saveLeadStages'}";
				$editButton = '<a class="permission-modify mr-5" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');">'.getIcon('edit').'</a>';

				$deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'LeadStages','fndelete':'deleteLeadStages'}";
				$deleteButton = '<a class="permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down">'.getIcon('delete').'</a>';
			}
			$responseHtml .=  '<div class="transactions-list t-info">
									<div class="t-item">
										<div class="t-company-name">
											<div class="t-icon">
												<div class="avatar">
													<span class="avatar-title">'.$row->sequence.'</span>
												</div>
											</div>
											<div class="t-name">
												<h4>'.$row->stage_type.'</h4>
												<p class="meta-date">'.$row->remark.' ('.$row->crate.'%)</p>
											</div>
										</div>
										<div class="t-rate rate-inc">
											'.$editButton.$deleteButton.'
										</div>
									</div>
								</div>';
		} 
		if(!empty($param)):
			return $responseHtml;
		else:
        	$this->printJson(['status'=>1,'dataList'=>$responseHtml]);
		endif;
	}
	
	public function saveLeadStages(){
		$postData = $this->input->post();
		$errorMessage = array();

		if(empty($postData['stage_type'])){
			$errorMessage['stage_type'] = "Stage Type is required.";
		}

		if(!empty($errorMessage)):
			$this->printJson(['status'=>0,'message'=>$errorMessage]);
		else:
			$postData['created_by'] = $this->loginId;
			$postData['created_at'] = date('Y-m-d H:i:s');

			$result = $this->configuration->saveLeadStages($postData);
			$result['responseEle'] = '.ls_list';
			$result['responseHtml'] = $this->getLeadStagesList(['ajaxCall'=>1]);
            $this->printJson($result);
		endif;
	}
	
	public function editLeadStages(){     
		$data = $this->input->post();
		$this->data['dataRow'] = $this->configuration->getLeadStagesList($data);
		$this->load->view($this->stage_form, $this->data);
	}

	public function deleteLeadStages(){
		$id = $this->input->post('id');
		if (empty($id)) :
			$this->printJson(['status' => 0, 'message' => 'Somthing went wrong...Please try again.']);
		else :
			$result = $this->configuration->deleteLeadStages($id);
			$result['responseEle'] = '.ls_list';
			$result['responseHtml'] = $this->getLeadStagesList(['ajaxCall'=>1]);
			$this->printJson($result);
		endif;
	}
	/********** End Lead Stages **********/
	
	/********** Start Custom Fields **********/
	public function getCustomFieldList($param=[]){
		$postData = (!empty($param) ? $param : $this->input->post());
        $cfList = $this->configuration->getCustomFieldList($postData);
        $responseHtml = "";$responseArr = Array(); $responseArr['customer_udf'] = $responseArr['product_udf'] = "";
        foreach($cfList as $row){
			$editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-md', 'form_id' : 'editCustomField', 'title' : 'Update','call_function':'editCustomField','fnsave' : 'saveCustomField'}";
			$editButton = '<a class="permission-modify mr-5" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');">'.getIcon('edit').'</a>';

			$deleteParam = "{'postData':{'id' : ".$row->id.",'type' : ".$row->type."},'message' : 'Record','fndelete':'deleteCustomField'}";
			$deleteButton = '<a class="permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down">'.getIcon('delete').'</a>';
			
			$fieldBtn = "";
			if($row->field_type == 'SELECT'){
				$fieldParam = "{'postData':{'udf_id' : ".$row->id."},'modal_id' : 'modal-md', 'form_id' : 'addSelectOption', 'title' : 'Add Options','call_function':'addSelectOption','fnsave' : 'saveSelectOption','button':'close'}";
				$fieldBtn = '<a class="permission-modify mr-5" href="javascript:void(0)" datatip="Add Options" flow="down" onclick="modalAction('.$fieldParam.');">'.getIcon('list').'</a>';
			}    
			
			$responseData =  '<div class="transactions-list t-info">
				<div class="t-item">
					<div class="t-company-name">
						<div class="t-icon">
							<div class="avatar">
								<span class="avatar-title">'.$row->field_name[0].'</span>
							</div>
						</div>
						<div class="t-name">
							<h4>'.$row->field_name.'</h4>
							<p class="meta-date">'.$row->field_type.'</p>
						</div>
					</div>
					<div class="t-rate rate-inc">
						'.$fieldBtn.$editButton.$deleteButton.'
					</div>
				</div>
			</div>';
			$responseHtml .= $responseData;
			$responseArr[$this->cfHeads[$row->type]] .= $responseData;
		}
		if(!empty($param)):
			if(!empty($param['separate'])):
				return $responseArr;
			else:
				return $responseHtml;
			endif;
		else:
        	$this->printJson(['status'=>1,'dataList'=>$responseHtml]);
		endif;
	}

	public function addCustomField(){
        $data = $this->input->post();
        $this->data['type'] = $data['type'];
        $this->data['nextIndex'] = $this->configuration->getNextFieldIndex(['type'=>$data['type']]);
        $this->load->view($this->cf_form,$this->data);
    }

	public function saveCustomField(){
        $postData = $this->input->post();
        $errorMessage = array();
		
        if(empty($postData['field_name'])){ $errorMessage['field_name'] = "Field Name is required."; }
        if(empty($postData['field_type'])){ $errorMessage['field_type'] = "Field type is required."; }

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			$result = $this->configuration->saveCustomField($postData);
			$result['responseEle'] = (($postData['type'] == 1) ? '.product_udf' : '.customer_udf');
			$result['responseHtml'] = $this->getCustomFieldList(['ajaxCall'=>1,'type'=>$postData['type']]);
            $this->printJson($result);
        endif;
    }

    public function editCustomField(){
        $data = $this->input->post(); $data['result_type'] = 'row';
        $this->data['dataRow'] = $this->configuration->getCustomFieldList($data);
        $this->load->view($this->cf_form,$this->data);
    }
    
    public function deleteCustomField(){
        $postData = $this->input->post();
        if(empty($postData['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
			$result = $this->configuration->deleteCustomField(['id'=>$postData['id']]);
			$result['responseEle'] = (($postData['type'] == 1) ? '.product_udf' : '.customer_udf');
			$result['responseHtml'] = $this->getCustomFieldList(['ajaxCall'=>1,'type'=>$postData['type']]);
            $this->printJson($result);
        endif;
    }
	
	public function addSelectOption(){
        $postData = $this->input->post();
        $this->data['udf_id'] = $postData['udf_id']; 
        $this->data['dataRow'] = $this->configuration->getCustomFieldList(['id'=>$postData['udf_id'],'result_type'=>'row']);		
		$this->data['optionRows'] = $this->getSelectOptionHtml(['udf_id'=>$postData['udf_id']]);
        $this->load->view($this->cf_select,$this->data);
    }
	
	public function getSelectOptionHtml($param = []){
        $optionList = $this->configuration->getSelectOptionList(['udf_id'=>$param['udf_id']]);
		$optionRows = '';$i=1;
		if(!empty($optionList))
		{
			foreach($optionList as $row)
			{
				$deleteParam = "{'postData':{'id' : ".$row->id.",'udf_id' : ".$row->udf_id."},'message' : 'Master Option'}";
				$deleteButton = '<a class="permission-remove" href="javascript:void(0)" onclick="removeOptions('.$deleteParam.');" datatip="Remove" flow="down">'.getIcon('delete').'</a>';
				
				$optionRows .='<div class="transactions-list t-info">
					<div class="t-item">
						<div class="t-company-name">
							<div class="t-icon">
								<div class="avatar">
									<span class="avatar-title">'.$row->title[0].'</span>
								</div>
							</div>
							<div class="t-name">
								<h4>'.$row->title.'</h4>
							</div>
						</div>
						<div class="t-rate rate-inc">
							'.$deleteButton.'
						</div>
					</div>
				</div>';
			}
		}
        return $optionRows;
    }	
	
	public function saveSelectOption(){
        $postData = $this->input->post();
        $errorMessage = array();
       
        if(empty($postData['title'])){ $errorMessage['title'] = "Title is required.";}
        if(empty($postData['udf_id'])){ $errorMessage['udf_id'] = "Type is required.";}

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			$result = $this->configuration->saveSelectOption($postData);
			$result['optionRows'] = $this->getSelectOptionHtml(['udf_id'=>$postData['udf_id']]);
            $this->printJson($result);
        endif;
	}

	public function deleteSelectOption(){
		$postData = $this->input->post();
        if(empty($postData['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
			$result = $this->configuration->deleteSelectOption($postData);
			$result['optionRows'] = $this->getSelectOptionHtml(['udf_id'=>$postData['udf_id']]);
            $this->printJson($result);
        endif;
	}
	/********** End Custom Fields **********/
}
?>