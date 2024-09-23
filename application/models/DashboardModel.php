<?php
class DashboardModel extends MasterModel{
    
    public function getSalesVsTarget(){
		//Sales Data
		$queryData = [];
		$queryData['tableName'] = "so_master";
		$queryData['select'] = "SUM(so_master.taxable_amount) as total_taxable_amount";

		$queryData['leftJoin']['party_master'] = "so_master.party_id = party_master.id";
		$queryData['leftJoin']['employee_master as executive_master'] = "executive_master.id = party_master.executive_id";

		$queryData['where']['DATE_FORMAT(so_master.trans_date,"%Y-%m")'] = date("Y-m");

		if(!in_array($this->userRole,[1,-1])):
            $queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", executive_master.super_auth_id) > 0 OR executive_master.id = '.$this->loginId.')';
        endif;

		$totalSales = $this->getData($queryData,'row');

		//Target Data
		$queryData = [];
		$queryData['tableName'] = "executive_targets";
		$queryData['select'] = "SUM(executive_targets.sales_amount) as total_target_amount";

		$queryData['leftJoin']['employee_master as executive_master'] = "executive_master.id = executive_targets.emp_id";
		
		$queryData['where']['DATE_FORMAT(executive_targets.target_month,"%Y-%m")'] = date("Y-m");

		if(!in_array($this->userRole,[1,-1])):
            $queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", executive_master.super_auth_id) > 0 OR executive_master.id = '.$this->loginId.')';
        endif;

		$totalTarget = $this->getData($queryData,'row');

		$sales = (!empty($totalSales->total_taxable_amount))?round($totalSales->total_taxable_amount,0):0;
		$target = (!empty($totalTarget->total_target_amount))?round($totalTarget->total_target_amount,0):0;
		$per = 0;
		if($sales > 0 && $target > 0):
			$per = round((($sales * 100) / $target),0);
		endif;

		return ['sales'=>moneyFormatIndia($sales),'target'=>moneyFormatIndia($target),'per'=>$per];
	}
    
    public function sendSMS($mobiles,$message){
        
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"http://sms.scubeerp.in/sendSMS?");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "username=9427235336&message=".$message."&sendername=NTVBIT&smstype=TRANS&numbers=".$mobiles."&apikey=7d37fc6d-a141-4f81-9d79-159cf37c3342");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec($ch);
		curl_close ($ch);
	}
	
}
?>