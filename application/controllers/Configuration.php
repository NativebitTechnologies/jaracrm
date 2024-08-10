<?php
class Configuration extends MY_Controller{
	
    private $business_index = "configuration/business_index";
    private $business_form = "configuration/business_form";
    private $terms_index = "configuration/terms_index";
    private $terms_form = "configuration/terms_form";
    private $masterOptions = "configuration/master_options";
    private $master_form = "configuration/master_form";
	private $stage_form = "configuration/stage_form";
	
	public $termsTypeArray = ["Purchase","Sales"];
	public $typeArray = ["","Source","Lost Reason","Expense Type"];

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
		$data = $this->input->post(); $data['single_row'] = 1;
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

        $this->data['selectOptionList'] = $this->configuration->getSelectOption();
        $this->data['businessList'] = $this->getBusinessTypeList();
		$this->data['stageList'] = $this->configuration->getLeadStagesList();

        $this->load->view($this->masterOptions,$this->data);
    }
	
	public function addMasterOptions(){
		$data = $this->input->post();
		$this->data['type'] = $data['type'];
		$this->data['type_name'] = $this->typeArray[$data['type']];
        $this->load->view($this->master_form, $this->data);
	}
	
	public function saveMasterOptions(){
		$data = $this->input->post();
		$errorMessage = array();
        
		if(empty($data['label'])){ 
			$errorMessage['label'] = "Please fill out this field.";
		}
        
		if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			$result = $this->configuration->saveSelectOption($data);
			$result['type'] = $data['type'];
            $this->printJson($result);
        endif;
	}
	
	public function getMasterOptionHtml(){
		$data = $this->input->post(); $resData='';
		$selectOptionList = $this->configuration->getSelectOption(['type'=>$data['type']]);
		
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
		$this->data['dataRow'] = $dataRow = $this->configuration->getSelectOption(['id'=>$data['id']]);
		$this->data['type_name'] = $this->typeArray[$dataRow->type];
        $this->load->view($this->master_form, $this->data);
	}
	
	public function deleteMasterOption(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->configuration->deleteMasterOption(['id'=>$data['id']]));
        endif;
	}
	/********** End Master Options **********/

	/********** Start Business Type **********/
    public function addBusinessType(){
		$this->data['businessList'] = $this->configuration->getBusinessTypeList();
        $this->load->view($this->business_form, $this->data);
    }

	public function getBusinessTypeList($param=[]){
		$postData = (!empty($this->input->post()) ? $this->input->post() : $param);
        $btList = $this->configuration->getBusinessTypeList($postData);
        $responseHtml = "";
        foreach($btList as $row){
			$editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-md', 'form_id' : 'editBusinessType', 'title' : 'Update Business Type','call_function':'editBusinessType','fnsave' : 'saveBusinessType'}";
			$editButton = '<a class="permission-modify mr-5" href="#" type="button" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');">'.getIcon('edit').'</a>';

			$deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Business Type','fndelete':'deleteBusinessType'}";
			$deleteButton = '<a class="permission-remove" href="#" type="button" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down">'.getIcon('delete').'</a>';
			$flag= (!empty($postData['flag']) ? ' @ '.$postData['flag'] : '');
			$responseHtml .=  '<div class="transactions-list t-info">
									<div class="t-item">
										<div class="t-company-name">
											<div class="t-icon">
												<div class="avatar">
													<span class="avatar-title">'.$row->type_name[0].$flag.'</span>
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
		if(!empty($this->input->post()) AND empty($param)):
        	$this->printJson(['status'=>1,'dataList'=>$responseHtml]);
		else:
			return $responseHtml;
		endif;
	}

    public function saveBusinessType(){
        $data = $this->input->post();
		$errorMessage = array();

        if(empty($data['type_name'])){
			$errorMessage['type_name'] = "Type Name is required.";
        }

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			//$result = $this->configuration->saveBusinessType($data);
			$result = ['status'=>1,'message'=>" saved Successfully."];
			$result['responseEle'] = '.bt_list';
			$result['responseHtml'] = $this->getBusinessTypeList(['flag'=>'response Done']);
            $this->printJson($result);
        endif;
    }

    public function editBusinessType(){     
        $data = $this->input->post(); $data['single_row'] = 1; 
        $this->data['dataRow'] = $this->configuration->getBusinessTypeList($data);
        $this->data['businessList'] = $this->configuration->getBusinessTypeList();
        $this->load->view($this->business_form, $this->data);
    }

	public function deleteBusinessType(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->configuration->deleteBusinessType(['id'=>$data['id']]));
        endif;
	}
	/********** End Business Type **********/
	
	/********** Start Lead Stages **********/
	public function addLeadStages(){
		$seqData = $this->configuration->getMaxStageSequence();
		$this->data['next_seq_no'] = (!empty($seqData->next_seq_no) ? ($seqData->next_seq_no + 1) : 1);
		$this->load->view($this->stage_form, $this->data);
	}
	
	public function saveLeadStages(){
		$data = $this->input->post();
		$errorMessage = array();

		if(empty($data['sequence'])){
			$errorMessage['sequence'] = "Sequence is required.";
		}
		if(empty($data['stage_type'])){
			$errorMessage['stage_type'] = "Stage Type is required.";
		}

		if(!empty($errorMessage)):
			$this->printJson(['status'=>0,'message'=>$errorMessage]);
		else:
			$data['created_by'] = $this->loginId;
			$data['created_at'] = date('Y-m-d H:i:s');
			$this->printJson($this->configuration->saveLeadStages($data));
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
			$this->printJson($this->configuration->deleteLeadStages($id));
		endif;
	}
	/********** End Lead Stages **********/
}
?>