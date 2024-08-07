<?php
class Configuration extends MY_Controller{
	
    private $business_index = "configuration/business_index";
    private $business_form = "configuration/business_form";
    private $terms_index = "configuration/terms_index";
    private $terms_form = "configuration/terms_form";
    private $masterOptions = "configuration/master_options";
    private $master_form = "configuration/master_form";
	
	public $termsTypeArray = ["Purchase","Sales"];

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Configuration";
		$this->data['headData']->controller = "configuration";
        $this->data['headData']->pageUrl = "configuration";
	}
	
	/********** Start Business Type **********/
	public function businessIndex(){
		$this->data['headData']->pageTitle = "Business Type";
        $this->data['headData']->pageUrl = "configuration/businessIndex";
        $this->load->view($this->business_index,$this->data);
    }
	
	public function getBusinessTypeListing(){ 
        $data = $this->input->post();
        $businessList = $this->configuration->getBusinessTypeList($data);

        $tbody = "";$i=($data['start'] + 1);
        foreach($businessList as $row):
			$editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-md', 'form_id' : 'editBusinessType', 'title' : 'Update Business Type','call_function':'editBusinessType','fnsave' : 'saveBusinessType'}";
			$editButton = '<a class="dropdown-item permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');">'.getIcon('edit').' Edit</a>';

			$deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Terms','fndelete':'deleteBusinessType'}";
			$deleteButton = '<a class="dropdown-item permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down">'.getIcon('delete').' Delete</a>';
		
            $tbody .= '<tr>
                <td class="checkbox-column"> '.$i.' </td>
                <td>'.$row->type_name.'</td>
                <td>'.$row->parentType.'</td>
                <td>'.$row->remark.'</td>
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

    public function addBusinessType(){
        $this->data['businessList'] = $this->configuration->getBusinessTypeList();
        $this->load->view($this->business_form, $this->data);
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
            $this->printJson($this->configuration->saveBusinessType($data));
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
	
	/********** Start Terms **********/
	public function termsIndex(){
		$this->data['headData']->pageTitle = "Terms & Conditions";
        $this->data['headData']->pageUrl = "configuration/termsIndex";
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
        /*if(empty($data['title']))
			$errorMessage['title'] = "Title is required.";
        if(empty($data['conditions']))
			$errorMessage['conditions'] = "Conditions is required.";
        if(empty($data['type'])):
			$errorMessage['type'] = "Type is required.";
		endif;*/
        
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
        $this->load->view($this->masterOptions,$this->data);
    }
	
	public function addMasterOptions(){
		$data = $this->input->post();
		$this->data['type'] = $data['type'];
		$this->data['type_name'] = $data['type_name'];
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
            $this->printJson($this->configuration->saveSelectOption($data));
        endif;
	}
	/********** End Master Options **********/

}
?>