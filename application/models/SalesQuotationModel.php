<?php
class SalesQuotationModel extends MasterModel{
    private $quotationMaster = "sq_master";
    private $quotationTrans = "sq_trans";
    private $quotationExpense = "sales_expense_trans";

    public function getSalesQuotationListing($data){
        $queryData = [];
        $queryData['tableName'] = $this->quotationMaster;

        $queryData['select'] = "sq_master.id, sq_master.trans_number, DATE_FORMAT(sq_master.trans_date,'%d-%m-%Y') as trans_date, party_master.party_name, executive_master.emp_name as executive_name, sq_master.taxable_amount, sq_master.gst_amount, sq_master.net_amount, sq_master.approve_by, sq_master.trans_status";

        $queryData['leftJoin']['party_master'] = "party_master.id = sq_master.party_id";
        $queryData['leftJoin']['employee_master as executive_master'] = "executive_master.id = sq_master.sales_executive";

        if($data['status'] == 0):
            $queryData['where']['sq_master.trans_status'] = 0;
            $queryData['where']['sq_master.approve_by'] = 0;
        elseif($data['status'] == 1):
            $queryData['where']['sq_master.trans_status'] = 0;
            $queryData['where']['sq_master.approve_by >'] = 0;
        else:
            $queryData['where']['sq_master.trans_status'] = 1;
        endif;

        if(!empty($data['search'])):
            $queryData['like']['sq_master.trans_number'] = $data['search'];
            $queryData['like']['DATE_FORMAT(sq_master.trans_date,"%d-%m-%Y")'] = $data['search'];
            $queryData['like']['party_master.party_name'] = $data['search'];
            $queryData['like']['executive_master.emp_name'] = $data['search'];
            $queryData['like']['sq_master.taxable_amount'] = $data['search'];
            $queryData['like']['sq_master.gst_amount'] = $data['search'];
            $queryData['like']['sq_master.net_amount'] = $data['search'];
        endif;

        $queryData['order_by']["sq_master.trans_date"] = "DESC";
        $queryData['order_by']["sq_master.trans_no"] = "DESC";

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
                $this->trash($this->quotationTrans,['sq_id'=>$data['id']]);
                $this->trash($this->quotationExpense,['vou_name'=>'Squot','ref_id'=>$data['id']]);
            endif;

            $itemData = $data['itemData'];
            $transExp = getSalesExpArrayMap(((!empty($data['expenseData']))?$data['expenseData']:array()));
			$expAmount = $transExp['exp_amount'];

            unset($data['itemData'],$data['expenseData'],$transExp['exp_amount']);

            $data['taxable_amount'] = array_sum(array_column($itemData,'taxable_amount'));
            $data['disc_amount'] = array_sum(array_column($itemData,'disc_amount'));
            $data['gst_amount'] = array_sum(array_column($itemData,'gst_amount'));
            $data['net_amount'] = array_sum(array_column($itemData,'net_amount'));

            $result = $this->store($this->quotationMaster,$data,'Sales Quotation');

            foreach($itemData as $row):
                $row['sq_id'] = $result['id'];
                $row['from_vou_name'] = $data['from_vou_name'];
                $row['is_delete'] = 0;
                unset($row['category_id']);
                
                $this->store($this->quotationTrans,$row);
            endforeach;

            if($expAmount <> 0):
                $expenseData = $transExp;
                $expenseData['id'] = "";
                $expenseData['vou_name'] = "Squot";
				$expenseData['ref_id'] = $result['id'];
                $this->store($this->quotationExpense,$expenseData);
            endif;

            if(empty($data['id'])):
                $this->completeEnquiry(['party_id'=>$data['party_id'],'item_ids'=>array_unique(array_column($itemData,'item_id'))]);

                $this->party->savePartyActivity(['party_id'=>$data['party_id'],'lead_stage'=>6,'ref_id'=>$result['id'],'ref_date'=>$data['trans_date']." ".date("H:i:s"),'ref_no'=>$data['trans_number']]);
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

    public function getSalesQuotation($data){
        $queryData = [];
        $queryData['tableName'] = $this->quotationMaster;
        $queryData['select'] = "sq_master.*, party_master.party_name, executive_master.emp_name as executive_name";

        $queryData['leftJoin']['party_master'] = "party_master.id = sq_master.party_id";
        $queryData['leftJoin']['employee_master as executive_master'] = "executive_master.id = sq_master.sales_executive";

		if(!empty($data['is_print'])):
			$queryData['select'] .= ",created.user_name as created_name, approved.user_name as approved_name,party_detail.address,party_detail.pincode,party_detail.gstin";
			$queryData['leftJoin']['user_master as created'] = "created.id = sq_master.created_by";
			$queryData['leftJoin']['user_master as approved'] = "approved.id = sq_master.approve_by";
			$queryData['leftJoin']['party_detail'] = "party_master.id = party_detail.party_id";
		endif;

        $queryData['where']['sq_master.id'] = $data['id'];

        $result = $this->getData($queryData,'row');

        if(!empty($data['itemList'])):
            $result->itemList = $this->getSalesQuotationItems(['sq_id'=>$data['id'],'only_pending_items'=>((isset($data['only_pending_items']))?1:0)]);
        endif;

        $queryData = array();
        $queryData['tableName'] = $this->quotationExpense;
        $queryData['where']['vou_name'] = "Squot";
        $queryData['where']['ref_id'] = $data['id'];
        $result->expenseData = $this->getData($queryData,'row');

        return $result;
    }

    public function getSalesQuotationItems($data){
        $queryData = [];
        $queryData['tableName'] = $this->quotationTrans;
        $queryData['select'] = "sq_trans.*,item_master.item_code,item_master.item_name,item_master.category_id,item_master.unit_name,item_master.gst_per,item_master.hsn_code,item_category.category_name";

        $queryData['leftJoin']['item_master'] = 'item_master.id = sq_trans.item_id';
        $queryData['leftJoin']['item_category'] = 'item_category.id = item_master.category_id';

        $queryData['where']['sq_trans.sq_id'] = $data['sq_id'];

        if(!empty($data['only_pending_items'])):
            $queryData['where']['sq_trans.trans_status'] = 0;
        endif;

        $result = $this->getData($queryData,'rows');
        return $result;
    }

    public function changeQuotationStatus($data){
        try{
            $this->db->trans_begin();

            if(!empty($data['approve_by'])): $data['approve_at'] = date("Y-m-d H:i:s"); endif;
            $result = $this->store($this->quotationMaster,$data,'Sales Quotation');
            $result['message'] = ($result['status'] == 1)?"Quotation Approved successfully.":$result['message'];

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

            $this->trash($this->quotationTrans,['sq_id'=>$data['id']]);
            $this->trash($this->quotationExpense,['vou_name'=>'Squot','ref_id'=>$data['id']]);
            $result = $this->trash($this->quotationMaster,['id'=>$data['id']],'Sales Quotation');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function completeEnquiry($data){
        $queryData = [];
        $queryData['tableName'] = "se_trans";
        $queryData['select'] = "se_trans.id,se_trans.se_id,se_trans.item_id,se_master.party_id";
        $queryData['leftJoin']['se_master'] = "se_master.id = se_trans.se_id";
        $queryData['where']['se_master.party_id'] = $data['party_id'];
        $queryData['where_in']['se_trans.item_id'] = $data['item_ids'];
        $queryData['where']['se_trans.trans_status'] = 0;
        $result = $this->getData($queryData,'rows');
        
        $mainIds = [];
        foreach($result as $row):
            $setData = [];
            $setData['tableName'] = "se_trans";
            $setData['where']['id'] = $row->id;
            $setData['update']['trans_status'] = "1";
            $this->setValue($setData);

            $setData = [];
            $setData['tableName'] = "item_master";
            $setData['where']['id'] = $row->item_id;
            $setData['update']['is_temp_item'] = "0";
            $this->setValue($setData);

            $mainIds[] = $row->se_id;
        endforeach;

        $mainIds = array_unique($mainIds);
        foreach($mainIds as $main_id):
            $setData = array();
            $setData['tableName'] = "se_master";
            $setData['update']['trans_status'] = "(SELECT IF( COUNT(id) = SUM(IF(trans_status <> 0, 1, 0)) ,1 , 0 ) as trans_status FROM se_trans WHERE se_id = ".$main_id." AND is_delete = 0)";
            $setData['where']['id'] = $main_id;                    
            $this->setValue($setData);
        endforeach;

        return true;
    }
}
?>