<?php
class ReportModel extends MasterModel{

    public function getInactivePartyDetail($data){
        $data['inactive_days'] = (!empty($data['inactive_days']))?$data['inactive_days']:10; 
        $queryData = [];
        $queryData['tableName'] = "party_master";
        $queryData['select'] = "party_master.id,party_master.party_code,party_master.party_name,party_master.business_type,party_detail.contact_person,party_master.contact_no,executive_master.emp_name as executive_name,address_master.state, address_master.state_code, address_master.district, address_master.city";

        $queryData['leftJoin']['party_detail'] = "party_detail.party_id = party_master.id";
        $queryData['leftJoin']['employee_master as executive_master'] = "executive_master.id = party_master.executive_id";
        $queryData['leftJoin']['address_master'] = "party_master.address_id = address_master.id";
        $queryData['leftJoin']['party_activities'] = "party_master.id = party_activities.party_id AND party_activities.ref_date BETWEEN NOW() - INTERVAL ".$data['inactive_days']." DAY AND NOW()";

        $queryData['customWhere'][] = 'party_activities.party_id IS NULL';

        if(isset($data['executive_id'])):
            $queryData['where']['executive_id'] = $data['executive_id'];
        endif;

        if(!in_array($this->userRole,[1,-1])):
            $queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", executive_master.super_auth_id) >0 OR executive_master.id = '.$this->loginId.')';
        endif;

        if(empty($data['order_by'])):
            $queryData['order_by']['party_name'] = "ASC";
        endif;

        if(!empty($data['search'])):
            $queryData['like']['party_master.party_code'] = $data['search'];
            $queryData['like']['party_master.party_name'] = $data['search'];
            $queryData['like']['party_master.contact_no'] = $data['search'];
            $queryData['like']['CONCAT(address_master.state,", ",address_master.district,", ",address_master.city)'] = $data['search'];
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
        $this->printQuery();
        return $result;
    }

    public function getPartyBudgetAnalysis($data){
        $queryData = [];
        $queryData['tableName'] = "so_master";

        $queryData['select'] = "so_master.party_id, party_master.party_name, address_master.state, address_master.district, address_master.city, DATE_FORMAT(so_master.trans_date,'%Y-%m') as month,SUM(so_master.taxable_amount) as taxable_amount";

        $queryData['leftJoin']['party_master'] = "so_master.party_id = party_master.id";
        $queryData['leftJoin']['address_master'] = "party_master.address_id = address_master.id";

        $queryData['where']['so_master.trans_date >='] = $data['from_date'];
        $queryData['where']['so_master.trans_date <='] = $data['to_date'];

        if(isset($data['executive_id'])):
            $queryData['where']['executive_id'] = $data['executive_id'];
        endif;

        $queryData['group_by'][] = "DATE_FORMAT(so_master.trans_date,'%Y-%m'),so_master.party_id";

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
}
?>