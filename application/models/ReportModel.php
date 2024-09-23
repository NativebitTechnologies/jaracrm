<?php
class ReportModel extends MasterModel{

    public function getInactivePartyDetail($data){
        $data['inactive_days'] = (!empty($data['filters']['inactive_days']))?$data['filters']['inactive_days']:10; 
        $queryData = [];
        $queryData['tableName'] = "party_master";
        $queryData['select'] = "party_master.id,party_master.party_code,party_master.party_name,party_master.business_type,party_detail.contact_person,party_master.contact_no,executive_master.emp_name as executive_name,address_master.state, address_master.state_code, address_master.district, address_master.city, IFNULL(pa.inactive_days, DATEDIFF(NOW(), party_master.created_at)) as inactive_days, IFNULL(pa.last_activity_date,party_master.created_at) as last_activity_date";

        $queryData['leftJoin']['party_detail'] = "party_detail.party_id = party_master.id";
        $queryData['leftJoin']['employee_master as executive_master'] = "executive_master.id = party_master.executive_id";
        $queryData['leftJoin']['address_master'] = "party_master.address_id = address_master.id";
        $queryData['leftJoin']['(SELECT party_id, DATEDIFF(NOW(), MAX(ref_date)) as inactive_days, MAX(ref_date) as last_activity_date FROM party_activities WHERE is_delete = 0  GROUP BY party_id) as pa'] = "pa.party_id = party_master.id";

        $queryData['customWhere'][] = '(IFNULL(pa.inactive_days,0) >'. $data['inactive_days']. ' OR pa.inactive_days IS NULL)';

        if(!empty($data['filters']['executive_id'])):
            $queryData['where']['party_master.executive_id'] = $data['filters']['executive_id'];
        endif;

        if(!in_array($this->userRole,[1,-1])):
            $queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", executive_master.super_auth_id) > 0 OR executive_master.id = '.$this->loginId.')';
        endif;

        if(empty($data['order_by'])):
            $queryData['order_by']['party_master.party_name'] = "ASC";
        endif;

        $queryData['group_by'][] = "party_master.id";

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
        
        return $result;
    }

    public function getPartyBudgetAnalysis($data){
        $queryData = [];
        $queryData['tableName'] = "so_master";

        $queryData['select'] = "so_master.party_id, party_master.party_name, party_master.contact_no, address_master.state, address_master.district, address_master.city, DATE_FORMAT(so_master.trans_date,'%Y-%m') as month,SUM(so_master.taxable_amount) as taxable_amount,party_detail.business_capacity,executive_master.emp_name as executive_name,";

        $queryData['leftJoin']['party_master'] = "so_master.party_id = party_master.id";
        $queryData['leftJoin']['party_detail'] = "party_detail.party_id = party_master.id";
        $queryData['leftJoin']['address_master'] = "party_master.address_id = address_master.id";
        $queryData['leftJoin']['employee_master as executive_master'] = "executive_master.id = party_master.executive_id";

        $queryData['where']['so_master.trans_date >='] = $data['from_date'];
        $queryData['where']['so_master.trans_date <='] = $data['to_date'];

        if(!empty($data['filters']['executive_id'])):
            $queryData['where']['party_master.executive_id'] = $data['filters']['executive_id'];
        endif;

        if(!in_array($this->userRole,[1,-1])):
            $queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", executive_master.super_auth_id) > 0 OR executive_master.id = '.$this->loginId.')';
        endif;

        if(!empty($data['search'])):
            $queryData['like']['party_master.party_code'] = $data['search'];
            $queryData['like']['party_master.party_name'] = $data['search'];
            $queryData['like']['party_master.contact_no'] = $data['search'];
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

    public function getUnsoldProductsDetails($data){
        $data['unsold_days'] = (!empty($data['filters']['unsold_days']))?$data['filters']['unsold_days']:10; 

        $queryData = [];
        $queryData['tableName'] = "item_master";
        $queryData['select'] = "item_master.id, item_master.item_code, item_master.item_name, item_category.category_name, item_master.hsn_code, item_master.gst_per, item_master.price, item_master.mrp, IFNULL(item_history.unsold_days, DATEDIFF(NOW(), item_master.created_at)) as unsold_days, IFNULL(item_history.last_sold_date, item_master.created_at) as last_sold_date";

        $queryData['leftJoin']['item_category'] = 'item_category.id = item_master.category_id';
        $queryData['leftJoin']['(SELECT so_trans.item_id, DATEDIFF(NOW(), MAX(so_master.trans_date)) as unsold_days, MAX(so_master.trans_date) as last_sold_date FROM so_trans LEFT JOIN so_master ON so_trans.so_id = so_master.id WHERE so_trans.is_delete = 0 GROUP BY so_trans.item_id) as item_history'] = "item_history.item_id = item_master.id";

        $queryData['customWhere'][] = '(IFNULL(item_history.unsold_days,0) >'. $data['unsold_days']. ' OR item_history.unsold_days IS NULL)';
        $queryData['where']['item_master.is_temp_item'] = 0;

        if(!empty($data['filters']['category_id'])):
			$queryData['where']['item_master.category_id'] = $data['filters']['category_id'];
		endif;

        if(!empty($data['search'])):
            $queryData['like']['item_master.item_code'] = $data['search'];
            $queryData['like']['item_master.item_name'] = $data['search'];
            $queryData['like']['item_category.category_name'] = $data['search'];
            $queryData['like']['item_master.hsn_code'] = $data['search'];
            $queryData['like']['item_master.gst_per'] = $data['search'];
            $queryData['like']['item_master.price'] = $data['search'];
            $queryData['like']['item_master.mrp'] = $data['search'];
        endif;

        if(!empty($data['limit'])):
			$queryData['limit'] = $data['limit'];
		endif;

        if(isset($data['start']) && isset($data['length'])):
			$queryData['start'] = $data['start'];
			$queryData['length'] = $data['length'];
		endif;

        $result = $this->getData($queryData,"rows");

        return $result;
    }
}
?>