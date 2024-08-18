<?php
class ExpenseModel extends MasterModel{
    private $expenseManager = "expense_manager";

    public function getNextExpNo(){
        $queryData['tableName'] = $this->expenseManager;
        $queryData['select'] = "IFNULL((MAX(exp_no) + 1),1) as exp_no";
        $queryData['where']['YEAR(exp_date)'] = date("Y");
        $queryData['where']['MONTH(exp_date)'] = date("m");
        $nextExpNo = $this->getData($queryData,'row')->exp_no;
        return $nextExpNo;
    }

    public function getExpenseDetails($data){
        $queryData = [];
        $queryData['tableName'] = $this->expenseManager;

        $queryData['select'] = "expense_manager.*, employee_master.emp_name, select_master.label as expense_type";

        $queryData['leftJoin']['employee_master'] = "employee_master.id = expense_manager.exp_by_id";
        $queryData['leftJoin']['select_master'] = "select_master.id = expense_manager.exp_type AND select_master.type = 3";

        if(isset($data['status'])):
            $queryData['where']['expense_manager.status'] = $data['status'];
        endif;

        if(!empty($data['search'])):
            $queryData['like']['expense_manager.exp_number'] = $data['search'];
            $queryData['like']['DATE_FORMAT(expense_manager.exp_date,"%d-%m-%Y")'] = $data['search'];
            $queryData['like']['employee_master.emp_name'] = $data['search'];
            $queryData['like']['select_master.label'] = $data['search'];
            $queryData['like']['expense_manager.demand_amount'] = $data['search'];
            $queryData['like']['expense_manager.amount'] = $data['search'];
        endif;

        $queryData['order_by']["expense_manager.exp_date"] = "DESC";
        $queryData['order_by']["expense_manager.exp_no"] = "DESC";

        if(isset($data['start']) && isset($data['length'])):
            $queryData['start'] = $data['start'];
            $queryData['length'] = $data['length'];
        endif;

        if(!empty($data['result_type'])):
            $result = $this->getData($queryData,$data['result_type']);
        elseif(!empty($data['id'])):
            $result = $this->getData($queryData,"row");
        else:
            $result = $this->getData($queryData,"rows");
        endif;

        return $result;
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            $result = $this->store($this->expenseManager,$data,'Expense');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function delete($data){
        try{
            $this->db->trans_begin();

            $result = $this->trash($this->expenseManager,['id'=>$data['id']],'Expense');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }
}
?>