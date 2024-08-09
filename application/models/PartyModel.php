<?php
class PartyModel extends MasterModel{
    private $partyMaster = "party_master";
	private $otherParty = "other_parties";
	private $partyDetail = "party_detail";
    private $custComplaint = "customer_complaint";
    private $leadMaster = "lead_master";
	private $lead_detail = "lead_detail";
	private $sales_logs = "sales_logs";
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

        if(isset($data['start']) && isset($data['length'])):
            $queryData['start'] = $data['start'];
            $queryData['length'] = $data['length'];
        endif;

        $result =  $this->getData($queryData,"rows");
        return $result;
    }

    public function getParty($data){
        $queryData = array();
        $queryData['tableName']  = $this->partyMaster;
        $queryData['select'] = "party_master.*,executive_master.emp_name as executive_name";

        $queryData['leftJoin']['employee_master executive_master'] = "executive_master.id = party_master.executive_id";

        if(!empty($data['partyDetail'])):
            $queryData['select'] .= ",party_detail.contact_person, party_detail.email_id, party_detail.address, party_detail.pincode, party_detail.gst_type, party_detail.gstin, party_detail.currency, party_detail.f1, party_detail.f2, party_detail.f3, party_detail.f4, party_detail.f5, party_detail.f6, party_detail.f7, party_detail.f8, party_detail.f9, party_detail.f10,address_master.country,address_master.state,address_master.district,address_master.city,business_type.parent_id as parent_type";//, party_detail.biz_capacity
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

            if(!empty($partyDetail)):
                if(empty($data['id'])):
                    $partyDetail['id'] = ""; $partyDetail['party_id'] = $result['id'];
                    $this->store($this->partyDetail,$partyDetail);
                else:
                    $this->edit($this->partyDetail,['party_id'=>$result['id']],$partyDetail);
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
    /********** End Customer **********/
	
	/********** Statutory Detail **********/

    public function getCityList($data){
        $queryData = [];
        $queryData['tableName'] = $this->addressMaster;
        $queryData['select'] = "address_master.*";
        $queryData['like']['CONCAT(country,",",state,",",district,",",city)'] = $data['query'];        
        $result = $this->getData($queryData,"rows");
        return $result;
    }

    /********** End Statutory Detail **********/
}
?>