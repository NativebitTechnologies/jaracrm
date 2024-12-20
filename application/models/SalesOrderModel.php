<?php
class SalesOrderModel extends MasterModel{
    private $orderMaster = "so_master";
    private $orderTrans = "so_trans";
    private $orderExpense = "sales_expense_trans";

    public function getSalesOrderListing($data){
        $queryData = [];
        $queryData['tableName'] = $this->orderMaster;

        $queryData['select'] = "so_master.id, so_master.trans_number, DATE_FORMAT(so_master.trans_date,'%d-%m-%Y') as trans_date, party_master.party_name, executive_master.emp_name as executive_name,address_master.city, so_master.taxable_amount, so_master.gst_amount, so_master.net_amount, so_master.approve_by, so_master.trans_status";

        $queryData['leftJoin']['party_master'] = "party_master.id = so_master.party_id";
        $queryData['leftJoin']['address_master'] = "party_master.address_id = address_master.id";
        $queryData['leftJoin']['employee_master as executive_master'] = "executive_master.id = so_master.sales_executive";

        if($data['status'] == 0):
            $queryData['where']['so_master.trans_status'] = 0;
            $queryData['where']['so_master.approve_by'] = 0;
        elseif($data['status'] == 1):
            $queryData['where']['so_master.trans_status'] = 0;
            $queryData['where']['so_master.approve_by >'] = 0;
        else:
            $queryData['where']['so_master.trans_status'] = 1;
        endif;

        if(!empty($data['search'])):
            $queryData['like']['so_master.trans_number'] = $data['search'];
            $queryData['like']['DATE_FORMAT(so_master.trans_date,"%d-%m-%Y")'] = $data['search'];
            $queryData['like']['party_master.party_name'] = $data['search'];
            $queryData['like']['address_master.city'] = $data['search'];
            $queryData['like']['executive_master.emp_name'] = $data['search'];
            $queryData['like']['so_master.taxable_amount'] = $data['search'];
            $queryData['like']['so_master.gst_amount'] = $data['search'];
            $queryData['like']['so_master.net_amount'] = $data['search'];
        endif;

        $queryData['order_by']["so_master.trans_date"] = "DESC";
        $queryData['order_by']["so_master.trans_no"] = "DESC";

        if(isset($data['start']) && isset($data['length'])):
            $queryData['start'] = $data['start'];
            $queryData['length'] = $data['length'];
        endif;

        $result =  $this->getData($queryData,"rows");
        return $result;
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            if(!empty($data['id'])):
                $this->trash($this->orderTrans,['so_id'=>$data['id']]);
                $this->trash($this->orderExpense,['vou_name'=>'SOrd','ref_id'=>$data['id']]);
            endif;

            $itemData = $data['itemData'];
            $transExp = getSalesExpArrayMap(((!empty($data['expenseData']))?$data['expenseData']:array()));
			$expAmount = $transExp['exp_amount'];

            unset($data['itemData'],$data['expenseData'],$transExp['exp_amount']);

            $data['taxable_amount'] = array_sum(array_column($itemData,'taxable_amount'));
            $data['disc_amount'] = array_sum(array_column($itemData,'disc_amount'));
            $data['gst_amount'] = array_sum(array_column($itemData,'gst_amount'));
            $data['net_amount'] = array_sum(array_column($itemData,'net_amount'));

            $result = $this->store($this->orderMaster,$data,'Sales Order');

            foreach($itemData as $row):
                $row['from_vou_name'] = $data['from_vou_name'];
                $row['so_id'] = $result['id'];
                $row['is_delete'] = 0;
                
                $this->store($this->orderTrans,$row);
            endforeach;

            if($expAmount <> 0):
                $expenseData = $transExp;
                $expenseData['id'] = "";
                $expenseData['vou_name'] = "SOrd";
				$expenseData['ref_id'] = $result['id'];
                $this->store($this->orderExpense,$expenseData);
            endif;

            if(empty($data['id'])):
                $this->completeQuotation(['party_id'=>$data['party_id'],'item_ids'=>array_unique(array_column($itemData,'item_id'))]);

                $this->party->savePartyActivity(['party_id'=>$data['party_id'],'lead_stage'=>7,'ref_id'=>$result['id'],'ref_date'=>$data['trans_date']." ".date("H:i:s"),'ref_no'=>$data['trans_number']]);
            endif;

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }      
    }

    public function getSalesOrder($data){
        $queryData = [];
        $queryData['tableName'] = $this->orderMaster;
        $queryData['select'] = "so_master.*, party_master.party_name, executive_master.emp_name as executive_name";

        $queryData['leftJoin']['party_master'] = "party_master.id = so_master.party_id";
        $queryData['leftJoin']['employee_master as executive_master'] = "executive_master.id = so_master.sales_executive";

		if(!empty($data['is_print'])):
			$queryData['select'] .= ",created.user_name as created_name, approved.user_name as approved_name,party_detail.address,party_detail.pincode,party_detail.gstin";
			$queryData['leftJoin']['user_master as created'] = "created.id = so_master.created_by";
			$queryData['leftJoin']['user_master as approved'] = "approved.id = so_master.approve_by";
			$queryData['leftJoin']['party_detail'] = "party_master.id = party_detail.party_id";
		endif;

        $queryData['where']['so_master.id'] = $data['id'];

        $result = $this->getData($queryData,'row');

        if(!empty($data['itemList'])):
            $result->itemList = $this->getSalesOrderItems(['so_id'=>$data['id']]);
        endif;

        $queryData = array();
        $queryData['tableName'] = $this->orderExpense;
        $queryData['where']['vou_name'] = "SOrd";
        $queryData['where']['ref_id'] = $data['id'];
        $result->expenseData = $this->getData($queryData,'row');

        return $result;
    }

    public function getSalesOrderItems($data){
        $queryData = [];
        $queryData['tableName'] = $this->orderTrans;
        $queryData['select'] = "so_trans.*,item_master.item_code,item_master.item_name,item_master.category_id,item_master.unit_name,item_master.gst_per,item_master.hsn_code,item_category.category_name";

        $queryData['leftJoin']['item_master'] = 'item_master.id = so_trans.item_id';
        $queryData['leftJoin']['item_category'] = 'item_category.id = item_master.category_id';

        $queryData['where']['so_trans.so_id'] = $data['so_id'];

        $result = $this->getData($queryData,'rows');
        return $result;
    }

    public function changeOrderStatus($data){
        try{
            $this->db->trans_begin();

            if(!empty($data['approve_by'])): 
                $data['approve_at'] = date("Y-m-d H:i:s"); 

                $orderDetail = $this->getSalesOrder(['id'=>$data['id'],'itemList'=>1]);
                $partyDetail = $this->party->getParty(['id'=>$orderDetail->party_id]);
                
                if($partyDetail->party_type == 2):
                    $setData = [];
                    $setData['tableName'] = "party_master";
                    $setData['where']['id'] = $orderDetail->party_id;
                    $setData['update']['party_type'] = 1;
                    $setData['update']['lead_stage'] = 10;
                    $this->setValue($setData);
                endif;

                $this->party->savePartyActivity(['party_id'=>$orderDetail->party_id, 'lead_stage' => 10, 'ref_id'=>$orderDetail->id, 'ref_date'=>$orderDetail->trans_date." ".date("H:i:s"), 'ref_no'=>$orderDetail->trans_number]);

                $setData = [];
                $setData['tableName'] = "item_master";
                $setData['where']['is_temp_item'] = 1;
                $setData['where_in']['id'] = array_column($orderDetail->itemList,'item_id');
                $setData['update']['is_temp_item'] = 0;
                $this->setValue($setData);
            endif;
            
            $result = $this->store($this->orderMaster,$data,'Sales Order');
            $result['message'] = ($result['status'] == 1)?"Order Approved successfully.":$result['message'];

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function delete($data){
        try{
            $this->db->trans_begin();

            $this->trash($this->orderTrans,['so_id'=>$data['id']]);
            $this->trash($this->orderExpense,['vou_name'=>'SOrd','ref_id'=>$data['id']]);
            $result = $this->trash($this->orderMaster,['id'=>$data['id']],'Sales Order');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function completeQuotation($data){
        $queryData = [];
        $queryData['tableName'] = "sq_trans";
        $queryData['select'] = "sq_trans.id,sq_trans.sq_id,sq_trans.item_id,sq_master.party_id";
        $queryData['leftJoin']['sq_master'] = "sq_master.id = sq_trans.sq_id";
        $queryData['where']['sq_master.party_id'] = $data['party_id'];
        $queryData['where_in']['sq_trans.item_id'] = $data['item_ids'];
        $queryData['where']['sq_trans.trans_status'] = 0;
        $queryData['where']['sq_master.approve_by >'] = 0;
        $result = $this->getData($queryData,'rows');
        
        $mainIds = [];
        foreach($result as $row):
            $setData = [];
            $setData['tableName'] = "sq_trans";
            $setData['where']['id'] = $row->id;
            $setData['update']['trans_status'] = "1";
            $this->setValue($setData);

            $mainIds[] = $row->sq_id;
        endforeach;

        $mainIds = array_unique($mainIds);
        foreach($mainIds as $main_id):
            $setData = array();
            $setData['tableName'] = "sq_master";
            $setData['update']['trans_status'] = "(SELECT IF( COUNT(id) = SUM(IF(trans_status <> 0, 1, 0)) ,1 , 0 ) as trans_status FROM sq_trans WHERE sq_id = ".$main_id." AND is_delete = 0)";
            $setData['where']['id'] = $main_id;                    
            $this->setValue($setData);
        endforeach;

        return true;
    }

    public function countOrderForDashboard(){
        $queryData['tableName']  = $this->orderMaster;
        $queryData['select'] = "COUNT(id) AS order_count"; 
        if(!in_array($this->userRole,[1,-1])):
            $queryData['where']['so_master.created_by'] = $this->loginId;
        endif;
        $result = $this->getData($queryData,'row');
        return $result;
    }
}
?>