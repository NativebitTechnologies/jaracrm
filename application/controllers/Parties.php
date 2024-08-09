<?php
class Parties extends MY_Controller{
    private $index = "party/index";
    private $form = "party/form";
	private $crm_desk = "party/crm_desk";
	
	public function __construct(){
        parent::__construct();
		$this->data['headData']->pageTitle = "Customer";
		$this->data['headData']->controller = "parties";    
        $this->data['headData']->pageUrl = "parties";    
    }
	
	public function index(){
        $this->load->view($this->index,$this->data);
    }
	
	public function indexSearch(){
        $this->load->view("party/searchable_list",$this->data);
    }

    public function getPartyListing(){
        $postData = $this->input->post();
        $partyList = $this->party->getPartyList($postData);

        $responseHtml = "";$i=($postData['start'] + 1);
        foreach($partyList as $row):
            $editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-xl', 'call_function':'edit', 'form_id' : 'partyForm', 'title' : 'Update Customer'}";

            $deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Customer'}";
            if($postData['party_type']==1):
                $responseHtml .= '<tr>
                                <td class="checkbox-column"> '.$i.' </td>
                                <td>'.$row->party_code.'</td>
                                <td>'.$row->party_name.'</td>
                                <td>'.$row->business_type.'</td>
                                <td>'.$row->contact_person.'</td>
                                <td>'.$row->contact_no.'</td>
                                <td>'.$row->whatsapp_no.'</td>
                                <td>'.$row->executive_name.'</td>
                                <td>'.$row->state.', '.$row->district.'</td>
                                <td>'.$row->city.'</td>
                                <td class="text-center">
                                    <div class="d-inline-block jpdm">
                                        <a class="dropdown-toggle" href="#" role="button" id="elementDrodpown3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            '.getIcon('more_h').'
                                        </a>

                                        <div class="dropdown-menu" aria-labelledby="elementDrodpown3" style="will-change: transform;">
                                            <a class="dropdown-item" href="javascript:void(0);" onclick="modalAction('.$editParam.');">'.getIcon('edit').' Edit</a>

                                            <a class="dropdown-item action-delete" href="javascript:void(0);" onclick="trash('.$deleteParam.');">'.getIcon('delete').' Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>';
            elseif($postData['party_type']==2):
                    $responseHtml .= '<div class="todo-item all-list">
                                        <div class="todo-item-inner">
                                            <div class="todo-content badge-group">
                                                <h5 class="todo-heading fs-16 mb-1" data-todoHeading="'.$row->party_name.'">'.$row->party_name.'</h5>
                                                <span class="badge bg-light-peach text-dark flex-fill">'.getIcon('corner_left_up').' '.$row->source.'</span>
                                                <span class="badge bg-light-teal text-dark flex-fill">'.getIcon('user').' '.$row->contact_person.'</span>
                                                <span class="badge bg-light-cream text-dark flex-fill">'.getIcon('phone_call').' '.$row->contact_no.'</span>
                                                <span class="badge bg-light-raspberry text-dark flex-fill">'.getIcon('clock').' '.formatDate($row->created_at,"d M Y H:i:s").'</span>

                                                <p class="todo-text">Lorem ipsum dolor sit amet</p>
                                            </div>
                                            <div class="executive_detail badge-group">
                                                <span class="badge bg-light-peach text-dark flex-fill">'.getIcon('smile').' '.$row->executive_name.'</span>
                                            </div>

                                            <div class="priority-dropdown custom-dropdown-icon">
                                                <div class="dropdown p-dropdown">
                                                    <a class="dropdown-toggle warning" href="#" role="button" id="dropdownMenuLink-1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    '.getIcon('alert_octagon').'
                                                    </a>

                                                    <div class="dropdown-menu left" aria-labelledby="dropdownMenuLink-1">
                                                        <a class="dropdown-item danger" href="javascript:void(0);">'.getIcon('alert_octagon').' High</a>
                                                        <a class="dropdown-item warning" href="javascript:void(0);">'.getIcon('alert_octagon').' Middle</a>
                                                        <a class="dropdown-item primary" href="javascript:void(0);">'.getIcon('alert_octagon').' Low</a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="action-dropdown custom-dropdown-icon">
                                                <div class="dropdown">
                                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink-2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    '.getIcon('more_v').'
                                                    </a>

                                                    <div class="dropdown-menu left" aria-labelledby="dropdownMenuLink-2">
                                                        <a class="edit dropdown-item" href="javascript:void(0);">Edit</a>
                                                        <a class="dropdown-item delete" href="javascript:void(0);">Delete</a>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>';
            endif;

            $i++;
        endforeach;

        $this->printJson(['status'=>1,'dataList'=>$responseHtml]);
    }
	
    public function getPartyList(){
        $data = $this->input->post();
        $partyList = $this->party->getPartyList($data);
        $this->printJson(['status'=>1,'data'=>['partyList'=>$partyList]]);
    }
	
	public function addParty(){
        $data = $this->input->post();
        $this->data['party_type'] = $data['party_type'];
		$this->data['sourceList'] = $this->configuration->getSelectOption();
		$this->data['executiveList'] = $this->usersModel->getEmployeeList();
		$this->data['sourceList'] = $this->configuration->getSelectOption(['type'=>1]);
		$this->data['businessTypeList'] = $this->configuration->getBusinessTypeList();
        $this->load->view($this->form,$this->data);
	}

    public function save(){
        $data = $this->input->post();
        $errorMessage = [];

        if(empty($data['party_name']))
			$errorMessage['party_name'] = "Party Name is required.";
        if(empty($data['source']))
			$errorMessage['source'] = "Source is required.";
        if(empty($data['contact_no'])):
			$errorMessage['contact_no'] = "Contact No. is required.";
		endif;

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->party->save($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->party->getParty(['id'=>$data['id'],'partyDetail'=>1]);
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->party->delete($id));
        endif;
    }

	/*CRM DESK*/
	public function crmDesk(){
		$this->data['headData']->pageTitle = "CRM DESK";
        $this->data['headData']->pageUrl = "parties/crmDesk";    
		$this->data['stageList'] = $this->configuration->getLeadStagesList();
        $this->load->view($this->crm_desk,$this->data);
	}
}
?>