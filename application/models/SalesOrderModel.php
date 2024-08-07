<?php
class SalesOrderModel extends MasterModel{
    private $orderMaster = "so_master";
    private $orderTrans = "so_trans";
    private $orderExpense = "sales_expense_trans";

    public function getSalesOrderListing($data){
        $queryData = [];
        $queryData['tableName'] = $this->orderMaster;

        $queryData['select'] = "so_master.id, so_master.trans_number, DATE_FORMAT(so_master.trans_date,'%d-%m-%Y') as trans_date, party_master.party_name, executive_master.emp_name as executive_name, so_master.taxable_amount, so_master.gst_amount, so_master.net_amount";

        $queryData['leftJoin']['party_master'] = "party_master.id = so_master.party_id";
        $queryData['leftJoin']['employee_master as executive_master'] = "executive_master.id = so_master.sales_executive";

        if(!empty($data['search'])):
            $queryData['like']['so_master.trans_number'] = $data['search'];
            $queryData['like']['DATE_FORMAT(so_master.trans_date,"%d-%m-%Y")'] = $data['search'];
            $queryData['like']['party_master.party_name'] = $data['search'];
            $queryData['like']['executive_master.emp_name'] = $data['search'];
            $queryData['like']['so_master.taxable_amount'] = $data['search'];
            $queryData['like']['so_master.gst_amount'] = $data['search'];
            $queryData['like']['so_master.net_amount'] = $data['search'];
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