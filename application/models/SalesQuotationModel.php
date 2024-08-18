<?php
class SalesQuotationModel extends MasterModel{
    private $quotationMaster = "sq_master";
    private $quotationTrans = "sq_trans";
    private $quotationExpense = "sales_expense_trans";

    public function getSalesQuotationListing($data){
        $queryData = [];
        $queryData['tableName'] = $this->quotationMaster;

        $queryData['select'] = "sq_master.id, sq_master.trans_number, DATE_FORMAT(sq_master.trans_date,'%d-%m-%Y') as trans_date, party_master.party_name, executive_master.emp_name as executive_name, sq_master.taxable_amount, sq_master.gst_amount, sq_master.net_amount";

        $queryData['leftJoin']['party_master'] = "party_master.id = sq_master.party_id";
        $queryData['leftJoin']['employee_master as executive_master'] = "executive_master.id = sq_master.sales_executive";

        $queryData['where']['sq_master.trans_status'] = $data['status'];

        if(!empty($data['search'])):
            $queryData['like']['sq_master.trans_number'] = $data['search'];
            $queryData['like']['DATE_FORMAT(sq_master.trans_date,"%d-%m-%Y")'] = $data['search'];
            $queryData['like']['party_master.party_name'] = $data['search'];
            $queryData['like']['executive_master.emp_name'] = $data['search'];
            $queryData['like']['sq_master.taxable_amount'] = $data['search'];
            $queryData['like']['sq_master.gst_amount'] = $data['search'];
            $queryData['like']['sq_master.net_amount'] = $data['search'];
        endif;

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
                $row['is_delete'] = 0;
                
                $this->store($this->quotationTrans,$row);
            endforeach;

            if($expAmount <> 0):
                $expenseData = $transExp;
                $expenseData['id'] = "";
                $expenseData['vou_name'] = "Squot";
				$expenseData['ref_id'] = $result['id'];
                $this->store($this->quotationExpense,$expenseData);
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

        $queryData['where']['sq_master.id'] = $data['id'];

        $result = $this->getData($queryData,'row');

        if(!empty($data['itemList'])):
            $result->itemList = $this->getSalesQuotationItems(['sq_id'=>$data['id']]);
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
        $queryData['select'] = "sq_trans.*,item_master.item_code,item_master.item_name,item_category.category_name";

        $queryData['leftJoin']['item_master'] = 'item_master.id = sq_trans.item_id';
        $queryData['leftJoin']['item_category'] = 'item_category.id = item_master.category_id';

        $queryData['where']['sq_trans.sq_id'] = $data['sq_id'];

        $result = $this->getData($queryData,'rows');
        return $result;
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

    /* public function getPendingPartyQuotation($data){
        $queryData = [];
        $queryData['tableName'] = $this->quotationTrans;
        $queryData['select'] = "sq_trans.*,sq_master.trans_number,sq_master.trans_date,item_master.item_code,item_master.item_name, (sq_trans.qty - IFNULL(com_trans.qty,0)) as pending_qty, 'Squot' as vou_name";

        $queryData['leftJoin']['sq_master'] = "sq_master.id = sq_trans.trans_main_id";
        $queryData['leftJoin']['item_master'] = "item_master.id = sq_trans.item_id";
        $queryData['leftJoin']['(SELECT ref_id,SUM(qty) as qty FROM so_trans WHERE from_vou_name = "Squot" AND is_delete = 0 GROUP BY ref_id) as com_trans'] = "sq_trans.id = com_trans.ref_id";

        $queryData['where']['sq_master.party_id'] = $data['party_id'];
        $queryData['where']['(sq_trans.qty - IFNULL(com_trans.qty,0)) >'] = 0;

        $result = $this->getData($queryData,'rows');
        return $result;
    } */
}
?>