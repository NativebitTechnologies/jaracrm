<?php
class Parties extends MY_Controller{
    private $index = "party/index";
    private $form = "party/form";
	private $crm_desk = "party/crm_desk";
    private $reminderForm = "party/reminder_form";
	
	public function __construct(){
        parent::__construct();
		$this->data['headData']->pageTitle = "Customer";
		$this->data['headData']->controller = "parties";    
        $this->data['headData']->pageUrl = "parties";    
    }
	
	public function index(){
        $this->data['DT_TABLE'] = true;
        $this->load->view($this->index,$this->data);
    }
	
	public function indexSearch(){
        $this->load->view("party/searchable_list",$this->data);
    }

    public function getPartyListing(){
        $postData = $this->input->post();
        $partyList = $this->party->getPartyList($postData);
        $totalRecords = 0;

        $responseHtml = "";$i=($postData['start'] + 1);
        if(!empty($partyList)){
            $stageList = $this->configuration->getLeadStagesList();
            foreach($partyList as $row):

                $editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-xl', 'call_function':'edit', 'form_id' : 'partyForm', 'title' : 'Update Customer'}";
                $editButton = '<a class="dropdown-item" href="javascript:void(0);" onclick="modalAction('.$editParam.');">'.getIcon('edit').' Edit</a>';

                $deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Customer'}";
                $deleteButton = '<a class="dropdown-item action-delete" href="javascript:void(0);" onclick="trash('.$deleteParam.');">'.getIcon('delete').' Delete</a>';

                $userButton = "";
                if(empty($row->user_id)):
                    $userParam = "{'postData':{'user_role' : 3, 'ref_id' : ".$row->id.", 'user_name' : '".$row->party_name."', 'contact_no' : '".$row->contact_no."'},'modal_id' : 'modal-md', 'call_function':'addUser', 'form_id' : 'userForm', 'title' : 'Add User', 'controller':'userMaster', 'fnsave' : 'save'}";
                    $userButton = '<a class="dropdown-item action-write" href="javascript:void(0);" onclick="modalAction('.$userParam.');">'.getIcon('user_add').' Create User</a>';
                endif;

                $enquiryParam = "{'postData':{'party_id' : ".$row->id."},'modal_id' : 'modal-xxl', 'controller':'salesEnquiry', 'call_function':'addSalesEnquiry', 'form_id' : 'salesEnquiryForm', 'title' : 'Add Sales Enquiry'}";
                $enquiryButton = '<a class="dropdown-item" href="javascript:void(0);" onclick="modalAction('.$enquiryParam.');">'.getIcon('plus').' Sales Enquiry</a>';

                $quotationParam = "{'postData':{'party_id' : ".$row->id."},'modal_id' : 'modal-xxl', 'controller':'salesQuotation', 'call_function':'addSalesQuotation', 'form_id' : 'quotationForm', 'title' : 'Add Sales Quotation'}";
                $quotationButton = '<a class="dropdown-item" href="javascript:void(0);" onclick="modalAction('.$quotationParam.');">'.getIcon('plus').' Sales Quotation</a>';

                $orderParam = "{'postData':{'party_id' : ".$row->id."},'modal_id' : 'modal-xxl', 'controller':'salesOrder', 'call_function':'addSalesOrder', 'form_id' : 'salesOrderForm', 'title' : 'Add Sales Order'}";
                $orderButton = '<a class="dropdown-item" href="javascript:void(0);" onclick="modalAction('.$orderParam.');">'.getIcon('plus').' Sales Order</a>';

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
                                    '.$editButton.$deleteButton.$userButton.$enquiryButton.$quotationButton.$orderButton.'
                                </div>
                            </div>
                        </td>
                    </tr>';

                elseif($postData['party_type']==2):

                    $partyName = (($row->party_code) ? $row->party_code.' - '.$row->party_name : $row->party_name);
                    $cperson = (($row->contact_person) ? getIcon('user').' '.$row->contact_person : '');
                    $cno = (($row->contact_no) ? getIcon('phone_call').' '.$row->contact_no : '');
                    $ename = (($row->executive_name) ? getIcon('smile').' '.$row->executive_name : '');
                    
                    $selectedStageIcon = '';$stages='';
                    if(!empty($stageList)){
                        foreach($stageList as $sc){
                            if($sc->lead_stage != 10){
                                $sc->stage_color = (!empty($sc->stage_color) ? $sc->stage_color : '#3B3B3B');
                                $stages .= '<a class="dropdown-item leadStage" style="color:'.$sc->stage_color.'" data-lead_stage="'.$sc->lead_stage.'" data-party_id="'.$row->id.'" href="javascript:void(0);">'.getIcon('alert_octagon','color:'.$sc->stage_color.';fill:'.$sc->stage_color.'33;').' '.$sc->stage_type.'</a>';
                                if($row->lead_stage == $sc->lead_stage){
                                    $selectedStageIcon = getIcon('alert_octagon','color:'.$sc->stage_color.';fill:'.$sc->stage_color.'33;');
                                }
                            }
                        }
                    }

                    $partyActivityParam = "{'postData':{'party_id':".$row->id."}, 'call_function' : 'partyActivity', 'fnsave' : 'savePartyActivity', 'button' : 'close', 'title' : '".$row->party_name."'}";

                    $reminderParam = "{'postData':{'party_id' : ".$row->id."},'modal_id' : 'modal-md', 'call_function':'addReminder', 'form_id' : 'reminderFrom', 'title' : 'Add Reminder', 'fnsave' : 'saveReminder'}";
                    $reminderButton = '<a class="dropdown-item" href="javascript:void(0);" onclick="modalAction('.$reminderParam.');">'.getIcon('bell').' Reminder</a>';                    

                    $responseHtml .= '<div class="todo-item all-list" onclick="modalAction('.$partyActivityParam.');">
                        <div class="todo-item-inner">
                            <div class="todo-content">
                                <h5 class="todo-heading fs-16 mb-1" data-todoHeading="'.$row->party_name.'">'.$partyName.'</h5>
                                <div class="badge-group">
                                    <span class="badge bg-light-peach text-dark flex-fill">'.getIcon('corner_left_up').' '.$row->source.'</span>
                                    <span class="badge bg-light-teal text-dark flex-fill">'.$cperson.'</span>
                                    <span class="badge bg-light-cream text-dark flex-fill">'.$cno.'</span>
                                    <span class="badge bg-light-raspberry text-dark flex-fill">'.getIcon('clock').' '.formatDate($row->created_at,"d M Y H:i A").'</span>
                                </div>
                                <p class="todo-text">Lorem ipsum dolor sit amet</p>
                            </div>
                            <div class="executive_detail badge-group">
                                <span class="badge bg-light-peach text-dark flex-fill">'.$ename.'</span>
                            </div>

                            <div class="priority-dropdown custom-dropdown-icon">
                                <div class="dropdown p-dropdown">
                                    <a class="dropdown-toggle warning" href="#" role="button" id="dropdownMenuLink-1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    '.(isset($selectedStageIcon) ? $selectedStageIcon : '').'
                                    </a>

                                    <div class="dropdown-menu left" aria-labelledby="dropdownMenuLink-1">'.$stages.'</div>
                                    
                                </div>
                            </div>

                            <div class="action-dropdown custom-dropdown-icon">
                                <div class="dropdown">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink-2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    '.getIcon('more_v').'
                                    </a>

                                    <div class="dropdown-menu left" aria-labelledby="dropdownMenuLink-2">
                                        '.$reminderButton.$editButton.$deleteButton.$enquiryButton.$quotationButton.$orderButton.'
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>';
                endif;

                $i++;
            endforeach;
        }
        $this->printJson(['status'=>1,'dataList'=>$responseHtml,'totalRecords'=>$totalRecords]);
    }
	
    public function getPartyList(){
        $data = $this->input->post();
        $partyList = $this->party->getPartyList($data);
        $this->printJson(['status'=>1,'data'=>['partyList'=>$partyList]]);
    }
	
	public function addParty(){
        $data = $this->input->post();
        $this->data['party_type'] = $data['party_type'];
		$this->data['sourceList'] = $this->configuration->getMasterOption(); 
		$this->data['executiveList'] = $this->employee->getEmployeeDetails(); 
		$this->data['sourceList'] = $this->configuration->getMasterOption(['type'=>1]);
		$this->data['businessTypeList'] = $this->configuration->getBusinessTypeList();
        $this->data['salesZoneList'] = $this->configuration->getSalesZoneList();
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
        $this->data['dataRow'] = $dataRow = $this->party->getParty(['id'=>$data['id'],'partyDetail'=>1]);
		$this->data['sourceList'] = $this->configuration->getMasterOption();
		$this->data['executiveList'] = $this->employee->getEmployeeDetails();
		$this->data['sourceList'] = $this->configuration->getMasterOption(['type'=>1]);
		$this->data['businessTypeList'] = $this->configuration->getBusinessTypeList();
		$this->data['parentOption'] = $this->getParentType(['business_type'=>$dataRow->parent_type,'sales_zone_id'=>$dataRow->sales_zone_id,'parent_id'=>$dataRow->parent_id]);
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

	public function getParentType($postData=[]){
		if(!empty($postData)){ $data = $postData; }else{ $data = $this->input->post(); }
		
        $partyList = $this->party->getPartyList(['business_type'=>$data['business_type'],'sales_zone_id'=>$data['sales_zone_id']]);
		
        $options = '<option value="">Select</option>';
        if(!empty($partyList)){
            foreach($partyList as $row){ 
                $selected = (!empty($data['parent_id']) && $data['parent_id'] == $row->id)?'selected':'';
                $options .= '<option value="'.$row->id.'" '.$selected.'>'.$row->party_name.'</option>';
            }
        }
		
        if(!empty($postData)){ return $options; }
		else{ $this->printJson(['status'=>1, 'options'=>$options]); }
	}

	/*CRM DESK*/
	public function crmDesk(){
		$this->data['headData']->pageTitle = "CRM DESK";
        $this->data['headData']->pageUrl = "parties/crmDesk";    
		$this->data['stageList'] = $this->configuration->getLeadStagesList();
        $this->load->view($this->crm_desk,$this->data);
	}

    public function changeLeadStages(){
        $postData = $this->input->post();
        $errorMessage = [];

        if(empty($postData['id']))
			$errorMessage['id'] = "Party is required.";
        if(empty($postData['lead_stage']))
			$errorMessage['lead_stage'] = "Lead Stage is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->party->changeLeadStages($postData));
        endif;
    }

    public function addReminder(){
        $data = $this->input->post();
        $this->data['party_id'] = $data['party_id'];
        $this->load->view($this->reminderForm,$this->data);
    }

    public function saveReminder(){
        $data = $this->input->post();
        $errorMessage = [];

        if(empty($data['ref_date']))
            $errorMessage['ref_date'] = "Date is required.";
        if(empty($data['reminder_time']))
            $errorMessage['reminder_time'] = "Time is required.";
        if(empty($data['mode']))
            $errorMessage['mode'] = "Mode is required.";
        if(empty($data['remark']))
            $errorMessage['remark'] = "Notes is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['ref_date'] = date("Y-m-d H:i:s",strtotime($data['ref_date']." ".$data['reminder_time']));
            unset($data['reminder_time']);
            $result = $this->party->savePartyActivity($data);
            $result['message'] = ($result['status'] == 1)?"Reminder saved successfully.":$result['message'];
            $this->printJson($result);
        endif;
    }
}
?>