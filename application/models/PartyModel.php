<?php
class PartyModel extends MasterModel{
    private $partyMaster = "party_master";
	private $otherParty = "other_parties";
	private $partyDetail = "party_detail";
    private $custComplaint = "customer_complaint";
    private $leadMaster = "lead_master";
	private $lead_detail = "lead_detail";
	private $partyActivities = "party_activities";
    private $addressMaster = "address_master";
    
    /********** Customer **********/
    public function getPartyCode($type=1){
        $queryData['tableName'] = $this->partyMaster;
        $queryData['select'] = "ifnull((MAX(CAST(REGEXP_SUBSTR(party_code,'[0-9]+') AS UNSIGNED)) + 1),1) as code";
        $queryData['where']['party_type'] = $type;
        $result = $this->getData($queryData,"row");
        return (!empty($result->code)) ? $result->code : 0;
    }

    public function getPartyList($data=array()){
        $queryData = array();
        $queryData['tableName']  = $this->partyMaster;
        $queryData['select'] = "party_master.*,party_detail.contact_person,executive_master.emp_name as executive_name, address_master.state, address_master.state_code, address_master.district, address_master.city"; 

        $queryData['leftJoin']['party_detail'] = "party_detail.party_id = party_master.id";
        $queryData['leftJoin']['employee_master as executive_master'] = "executive_master.id = party_master.executive_id";
        $queryData['leftJoin']['address_master'] = "party_master.address_id = address_master.id";


        /*if(!empty($data['party_type'])):
            $queryData['where_in']['party_type'] = $data['party_type'];
        endif;*/

        if(!empty($data['lead_stage'])):
            $queryData['where']['party_type'] = 2;
            $queryData['where']['lead_stage'] = $data['lead_stage'];
        elseif(!empty($data['party_type'])):
            $queryData['where_in']['party_type'] = $data['party_type'];
        endif;

        if(isset($data['executive_id'])):
            $queryData['where']['executive_id'] = $data['executive_id'];
        endif;

        if(!empty($data['sales_zone_id'])):
            $queryData['where']['sales_zone_id'] = $data['sales_zone_id'];
        endif;

        if(!empty($data['business_type'])):
            $queryData['where']['business_type'] = $data['business_type'];
        endif;
        
        if(!in_array($this->userRole,[1,-1])):
            $queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", executive_master.super_auth_id) >0 OR executive_master.id = '.$this->loginId.')';
        endif;

        if(empty($data['order_by'])):
            $queryData['order_by']['party_name'] = "ASC";
        endif;

        if(isset($data['is_active'])):
            $queryData['where']['party_master.is_active'] = $data['is_active'];        
        endif;

        if(isset($data['executive_required']) && $data['executive_required'] == 1):
            $queryData['where']['executive_id >'] = 0;
        endif;

        if(!empty($data['search'])):
            $queryData['like']['party_master.party_code'] = $data['search'];
            $queryData['like']['party_master.party_name'] = $data['search'];
            $queryData['like']['party_master.contact_no'] = $data['search'];
            $queryData['like']['party_master.whatsapp_no'] = $data['search'];
        endif;

        if(!empty($data['limit'])): 
            $queryData['limit'] = $data['limit']; 
            $queryData['order_by']['party_master.created_at'] = "DESC"; 
        endif;

        $queryData['group_by'][] = "party_master.id"; 

        if(isset($data['start']) && isset($data['length'])):
            $queryData['start'] = $data['start'];
            $queryData['length'] = $data['length'];
        endif;
        if(!empty($data['result_type'])){$result =  $this->getData($queryData,$data['result_type']);}
        else{$result =  $this->getData($queryData,"rows");}
        return $result;
    }

    public function getParty($data){
        $queryData = array();
        $queryData['tableName']  = $this->partyMaster;
        $queryData['select'] = "party_master.*,executive_master.emp_name as executive_name";

        $queryData['leftJoin']['employee_master executive_master'] = "executive_master.id = party_master.executive_id";

        if(!empty($data['partyDetail'])):
            $queryData['select'] .= ",party_detail.id as pd_id, party_detail.contact_person, party_detail.email_id, party_detail.address, party_detail.pincode, party_detail.gstin, party_detail.currency, party_detail.business_capacity, party_detail.product_used, party_detail.f1, party_detail.f2, party_detail.f3, party_detail.f4, party_detail.f5, party_detail.f6, party_detail.f7, party_detail.f8, party_detail.f9, party_detail.f10,address_master.country,address_master.state,address_master.district,address_master.city,business_type.parent_id as parent_type";
            $queryData['leftJoin']['party_detail'] = "party_detail.party_id = party_master.id";
			$queryData['leftJoin']['address_master'] = "address_master.id = party_master.address_id";
			$queryData['leftJoin']['business_type'] = "business_type.type_name = party_master.business_type";
        endif;

        if(!empty($data['id'])):
            $queryData['where']['party_master.id'] = $data['id'];
        endif;

        if(!empty($data['party_type'])):
            $queryData['where']['party_master.party_type'] = $data['party_type'];
        endif;

        if(!empty($data['executive_id'])):
            $queryData['where']['party_master.executive_id'] = $data['executive_id'];
        endif;

        if(!empty($data['sales_zone_id'])):
            $queryData['where']['party_master.sales_zone_id'] = $data['sales_zone_id'];
        endif;

        if(!empty($data['business_type'])):
            $queryData['where']['party_master.business_type'] = $data['business_type'];
        endif;

        $result =  $this->getData($queryData,"row");
        return $result; 
    }

    public function save($data){
        try {
            $this->db->trans_begin();

            $partyDetail = (!empty($data['party_detail']))?$data['party_detail']:[];

            unset($data['party_detail']);

            $data['checkDuplicate']['first_key'] = 'party_name';
            $data['checkDuplicate']['customWhere'] = "((party_name = '".$data['party_name']."') or (contact_no = '".$data['contact_no']."'))";

            $result = $this->store($this->partyMaster, $data, 'Party');

            if($result['status'] == 1):
                if(!empty($partyDetail)):
                    if(empty($data['id'])):
                        $partyDetail['id'] = "";
                    endif;
                    $partyDetail['party_id'] = $result['id'];                
                    $this->savePartyDetails($partyDetail);
                endif;

                if(empty($data['id']) && $data['party_type'] == 2):
                    $this->savePartyActivity(['party_id'=>$result['id'],'lead_stage'=>1]);
                endif;
            endif;
            
            if ($this->db->trans_status() !== FALSE) :
                $this->db->trans_commit();
                return $result;
            endif;
        } catch (\Exception $e) {
            $this->db->trans_rollback();
            return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
        }
    }

    public function savePartyDetails($data){
        try {
            $this->db->trans_begin();

            if(empty($data['id'])):
                $data['id'] = "";
                $result = $this->store($this->partyDetail, $data,'Party Detail');
            else:
                $result = $this->edit($this->partyDetail, ['party_id'=>$data['party_id']], $data,'Party Detail');
            endif;

            if ($this->db->trans_status() !== FALSE) :
                $this->db->trans_commit();
                return $result;
            endif;
        } catch (\Exception $e) {
            $this->db->trans_rollback();
            return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
        }
    }

    public function changeLeadStages($param){
        try{
            $this->db->trans_begin();
            
            $result = $this->store($this->partyMaster, $param, 'Party');

            $pa = $this->savePartyActivity(['party_id'=>$param['id'],'lead_stage'=>$param['lead_stage']]);

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function delete($id){
        try {
            $this->db->trans_begin();

            $this->trash($this->partyDetail, ['party_id' => $id]);

            $checkData['columnName'] = ['party_id','acc_id','opp_acc_id','vou_acc_id'];
            $checkData['value'] = $id;
            $checkUsed = $this->checkUsage($checkData);

            if($checkUsed == true):
                $this->db->trans_rollback();
                return ['status'=>0,'message'=>'The Party is currently in use. you cannot delete it.'];
            endif;

            $result = $this->trash($this->partyMaster, ['id' => $id], 'Party');

            if ($this->db->trans_status() !== FALSE) :
                $this->db->trans_commit();
                return $result;
            endif;
        } catch (\Exception $e) {
            $this->db->trans_rollback();
            return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
        }
    }

    public function savePartyActivity($param){
        try{
			if(empty($param['id']))
			{
				$activityNotes =Array();
				$activityNotes[1] = 'New Lead generated';
				$activityNotes[2] = 'New appointment scheduled';
				$activityNotes[3] = (!empty($param['notes']))?$param['notes']:"";
				$activityNotes[4] = 'New Enquiry Received';
				$activityNotes[5] = 'Quotation request';
				$activityNotes[6] = 'Quotation Generated';
				$activityNotes[7] = 'Order Received';
				$activityNotes[8] = 'De-activated Customer';
				$activityNotes[9] = 'Executive assigned';
				$activityNotes[10] = 'Order Confirmed';
				$activityNotes[11] = 'Ohh..No ! We Lost..😞';
				$activityNotes[12] = 'Re-opened Customer';

				$this->db->trans_begin();

				$data = Array();
				if($param['lead_stage'] >= 21 AND $param['lead_stage']<=30) 
				{
					$leadStageData = $this->configuration->getLeadStagesList(["lead_stage"=>$param['lead_stage'],"result_type"=>"row"]);
					$param['notes'] = 'Status updated to ';
					if(!empty($leadStageData->stage_type)){$param['notes'] .= '<b>'.$leadStageData->stage_type.'<b>';}
				}
				else{$param['notes'] = $activityNotes[$param['lead_stage']];}
				if(empty($param['ref_date'])){$param['ref_date'] = date('Y-m-d H:i:s');}
				$param['id'] = "";
			}
			
			$result = $this->store($this->partyActivities, $param, 'Party Activity');
			
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }
	
    public function getPartyActivity($data){
        $queryData = [];
        $queryData['tableName'] = $this->partyActivities;
        $queryData['select'] = "party_activities.*, employee_master.emp_name as created_by_name";
        $queryData['leftJoin']['employee_master'] = "employee_master.user_id = party_activities.created_by";
        $queryData['order_by']['party_activities.ref_date'] = 'DESC';
        if(!empty($data['party_id'])){ $queryData['where']['party_activities.party_id'] = $data['party_id']; }
        if(!empty($data['created_by'])){ $queryData['where']['party_activities.created_by'] = $data['created_by']; }
        if(!empty($data['lead_stage'])){ $queryData['where']['party_activities.lead_stage'] = $data['lead_stage']; }
        if(!empty($data['customWhere'])){ 
            $queryData['customWhere'][] = $data['customWhere']; 
        }
        if(!empty($data['numRows'])){
            return $this->getData($queryData,"numRows");
        }
        $result = $this->getData($queryData,'rows');
        return $result;
    }

    public function countLeadForDashboard(){
        $queryData['tableName']  = $this->partyMaster;
        $queryData['select'] = "SUM(CASE WHEN lead_stage = 1 THEN 1 ELSE 0 END) AS new_lead,SUM(CASE WHEN lead_stage = 11 THEN 1 ELSE 0 END) AS lost_lead"; 
        $queryData['leftJoin']['employee_master as executive_master'] = "executive_master.id = party_master.executive_id";
        $queryData['where']['party_type'] = 2;
        $queryData['where_in']['lead_stage'] = '1,11';
        if(!in_array($this->userRole,[1,-1])):
            $queryData['where']['executive_master.user_id'] = $this->loginId;
        endif;
        $result = $this->getData($queryData,'row');
        return $result;
    }
    /********** End Customer **********/
	
	/********** Address Detail **********/

    public function getCityList($data){
        $queryData = [];
        $queryData['tableName'] = $this->addressMaster;
        $queryData['select'] = "address_master.*";
        $queryData['like']['CONCAT(country,",",state,",",district,",",city)'] = $data['query'];        
        $result = $this->getData($queryData,"rows");
        return $result;
    }

    /********** End Address Detail **********/

    public function getCurrencyList(){
        $queryData = [];
        $queryData['tableName'] = "currency";
        $result = $this->getData($queryData,'rows');
        return $result;
    }
}
?>