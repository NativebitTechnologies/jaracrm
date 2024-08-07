<?php
class SalesExpenseMaster extends MasterModel{
    private $salesExpMaster = "sales_expense_master";

    public function getSalesExpenseList($data){
        $queryData = [];
        $queryData['tableName'] = $this->salesExpMaster;
        $queryData['select'] = "sales_expense_master.*";

        if(!empty($data['is_active'])):
            $queryData['where']['sales_expense_master.is_active'] = $data['is_active'];
        endif;

        if(!empty($data['id'])):
            $queryData['where']['sales_expense_master.id'] = $data['id'];
        endif;

        $queryData['order_by']['sales_expense_master.seq'] = "ASC";

        if(!empty($data['id']) || !empty($data['single_row'])):
            $result = $this->getData($queryData,'row');
        else:
            $result = $this->getData($queryData,'rows');
        endif;
        return $result;
    }
}
?>