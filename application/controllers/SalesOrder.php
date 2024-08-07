<?php
class SalesOrder extends MY_Controller{
    private $index = "sales_order/index";
    private $form = "sales_order/form";

    public function __construct(){
        parent::__construct();
        $this->data['headData']->pageTitle = "Sales Order";
		$this->data['headData']->controller = "salesOrder";    
        $this->data['headData']->pageUrl = "salesOrder";  
    }

    public function index(){
        $this->load->view($this->index,$this->data);
    }

    public function getSalesOrderListing(){
        $data = $this->input->post();
        $orderList = $this->salesOrder->getSalesOrderListing($data);

        $tbody = "";$i=($data['start'] + 1);
        foreach($orderList as $row):
            $editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-xxl', 'call_function':'edit', 'form_id' : 'partyForm', 'title' : 'Update Customer'}";

            $deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Customer'}";

            $tbody .= '<tr>
                <td class="checkbox-column"> '.$i.' </td>
                <td>'.$row->trans_number.'</td>
                <td>'.$row->trans_date.'</td>
                <td>'.$row->party_name.'</td>
                <td>'.$row->executive_name.'</td>
                <td>'.$row->taxable_amount.'</td>
                <td>'.$row->gst_amount.'</td>
                <td>'.$row->net_amount.'</td>
                <td class="text-center">
                    <div class="d-inline-block jpdm">
                        <a class="dropdown-toggle" href="#" role="button" id="elementDrodpown3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            '.getIcon('more_h').'
                        </a>

                        <div class="dropdown-menu" aria-labelledby="elementDrodpown3" style="will-change: transform;">
                            <a class="dropdown-item" href="javascript:void(0);" onclick="modalAction('.$editParam.');">'.getIcon('edit').' Edit</a>

                            <a class="dropdown-item action-delete" href="javascript:void(0);" onclick="trash('.$deleteParam.');">'.getIcon('delete').' Delete</a>
                        </div>
                    </div>
                </td>
            </tr>';

            $i++;
        endforeach;

        $this->printJson(['status'=>1,'dataList'=>$tbody]);
    }

    public function addSalesOrder(){
        $this->data['partyList'] = $this->party->getPartyList(['party_type'=>1]);
        $this->data['itemList'] = $this->item->getItemList();
        $this->data['expenseList'] = $this->salesExpense->getSalesExpenseList(['is_active'=>1]);

        $this->load->view($this->form,$this->data);
    }
}
?>