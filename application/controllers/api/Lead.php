<?php
class Lead extends MY_ApiController{

    public function __construct(){
        parent::__construct();
        $this->data['headData']->pageTitle = "CRM Desk";
        $this->data['headData']->pageUrl = "api/lead/crmDesk";
        $this->data['headData']->base_url = base_url();
    }

    public function getLeadStageList(){
        $this->data['stageList'] = $this->configuration->getLeadStagesList();
        $this->printJson(['status'=>1,'message'=>'Data Found.','data'=>$this->data]);
    }

    public function getPartyListing(){
        $postData = $this->input->post();
        $partyList = $this->party->getPartyList($postData);

        $sendData = []; $i=($postData['start'] + 1);
        if(!empty($partyList)):
            $sendData = [];
            foreach($partyList as $row):			

                $sendData[] = [
                    'id' => $row->id,
                    'user_id' => $row->user_id,
                    'party_code' => $row->party_code,
                    'party_name' => $row->party_name,
                    'contact_person' => $row->contact_person,
                    'contact_no' => $row->contact_no,
                    'city' => $row->city,
                    'executive_name' => $row->executive_name,
                    'created_at' => $row->created_at
                ];

                $i++;
            endforeach;
        endif;

        $this->printJson(['status'=>1,'data'=>['dataList'=>$sendData]]);
    }

    public function addParty(){
        $data = $this->input->post();

        $this->data['party_type'] = 2;
		$this->data['sourceList'] = $this->configuration->getMasterOption(); 
		$this->data['executiveList'] = $this->employee->getEmployeeDetails(); 
		$this->data['sourceList'] = $this->configuration->getMasterOption(['type'=>1]);
		$this->data['businessTypeList'] = $this->configuration->getBusinessTypeList();
        $this->data['salesZoneList'] = $this->configuration->getSalesZoneList();
		$this->data['currencyList'] = $this->party->getCurrencyList();
        $this->data['customFieldList'] = $this->configuration->getCustomFieldList(['type'=>2]);
        $this->data['masterDetailList'] = $this->configuration->getSelectOptionList();
        
        $this->printJson(['status'=>1,'messge'=>'Data Found','data'=>$this->data]);
	}

    public function save(){
        $data = $this->input->post(); 
        if(!empty($data['party_detail'])): $data['party_detail'] = json_decode($data['party_detail'],true); endif;
        //print_r($data);exit;
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
            $result = $this->party->save($data);
            print_r($result);exit;
            $this->printJson($result);
        endif;
    }

    public function edit(){
        $data = $this->input->post();

        $this->data['leadDetail'] = $dataRow = $this->party->getParty(['id'=>$data['id'],'partyDetail'=>1]);
		$this->data['sourceList'] = $this->configuration->getMasterOption();
		$this->data['executiveList'] = $this->employee->getEmployeeDetails();
		$this->data['sourceList'] = $this->configuration->getMasterOption(['type'=>1]);
		$this->data['businessTypeList'] = $this->configuration->getBusinessTypeList();
		$this->data['parentOption'] = $this->getParentType(['business_type'=>$dataRow->parent_type,'sales_zone_id'=>$dataRow->sales_zone_id,'parent_id'=>$dataRow->parent_id]);
		$this->data['currencyList'] = $this->party->getCurrencyList();
        $this->data['customFieldList'] = $this->configuration->getCustomFieldList(['type'=>2]);
        $this->data['masterDetailList'] = $this->configuration->getSelectOptionList();

        $this->printJson(['status'=>1,'messge'=>'Data Found','data'=>$this->data]);
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
		$data = (!empty($postData))?$postData:$this->input->post();
		
        $partyList = $this->party->getPartyList(['business_type'=>$data['business_type'],'sales_zone_id'=>$data['sales_zone_id']]);
		
        $options = '<option value="">Select</option>';
        if(!empty($partyList)):
            foreach($partyList as $row):
                $selected = (!empty($data['parent_id']) && $data['parent_id'] == $row->id)?'selected':'';
                $options .= '<option value="'.$row->id.'" '.$selected.'>'.$row->party_name.'</option>';
            endforeach;
        endif;
		
        if(!empty($postData)): return $options; 
        else: $this->printJson(['status'=>1, 'messge'=>'Data Found','data'=>['options'=>$options]]);
        endif;
	}

    public function reminderModes(){
        $this->data['reminderModes'] = $this->reminderModes;
        $this->printJson(['status'=>1,'messge'=>'Data Found','data'=>$this->data]);
    }

    public function partyActivity(){
        $data = $this->input->post();
        $this->data['activityDetails'] = $this->party->getPartyActivity($data);
        $this->printJson(['status'=>1,'messge'=>'Data Found','data'=>$this->data]);
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