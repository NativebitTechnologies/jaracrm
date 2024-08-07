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
        $data = $this->input->post();
        $partyList = $this->party->getPartyList($data);

        $tbody = "";$i=($data['start'] + 1);
        foreach($partyList as $row):
            $editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-xl', 'call_function':'edit', 'form_id' : 'partyForm', 'title' : 'Update Customer'}";

            $deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Customer'}";

            $tbody .= '<tr>
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

            $i++;
        endforeach;

        $this->printJson(['status'=>1,'dataList'=>$tbody]);
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
		$this->data['sourceList'] = $this->configuration->getSelectOption();
		$this->data['businessTypeList'] = $this->configuration->getBusinessTypeList();
        $this->load->view($this->form,$this->data);
	}

    public function save(){
        $data = $this->input->post();
        $errorMessage = [];

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
        $this->data['headData']->pageUrl = "parties/crmDesk";    
		$this->data['stageList'] = $this->configuration->getLeadStagesList();
        $this->load->view($this->crm_desk,$this->data);
	}
}
?>