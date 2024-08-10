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
        $this->data['DT_TABLE'] = true;  
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
        $voucherSeries = $this->getVoucherSeries(['vou_name_s'=>'SOrd','tableName'=>'so_master','numberColumn'=>'trans_no','dateColumn'=>'trans_date']);
        
        $this->data['trans_prefix'] = $voucherSeries['vou_prefix'];
        $this->data['trans_no'] = $voucherSeries['vou_no'];
        $this->data['trans_number'] = $voucherSeries['vou_number'];
        $this->data['partyList'] = $this->party->getPartyList(['party_type'=>1]);
        $this->data['itemList'] = $this->product->getProductList();
        $this->data['expenseList'] = $this->salesExpense->getSalesExpenseList(['is_active'=>1]);

        $this->load->view($this->form,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = [];

        if(empty($data['itemData']))
            $errorMessage['itemData'] = "Item Detail is required.";

        if($_FILES['order_file']['name'] != null || !empty($_FILES['order_file']['name'])):
            $this->load->library('upload');
            $_FILES['userfile']['name']     = $_FILES['order_file']['name'];
            $_FILES['userfile']['type']     = $_FILES['order_file']['type'];
            $_FILES['userfile']['tmp_name'] = $_FILES['order_file']['tmp_name'];
            $_FILES['userfile']['error']    = $_FILES['order_file']['error'];
            $_FILES['userfile']['size']     = $_FILES['order_file']['size'];
            
            $imagePath = realpath(APPPATH . '../assets/uploads/sales_order/');
            $config = ['file_name' => time()."_order_item_".$_FILES['userfile']['name'],'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path'	=>$imagePath];

            $this->upload->initialize($config);
            if (!$this->upload->do_upload()):
                $errorMessage['order_file'] = $this->upload->display_errors();
            else:
                $uploadData = $this->upload->data();
                $data['order_file'] = $uploadData['file_name'];
            endif;
        endif;

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            if(empty($data['id'])):
                $voucherSeries = $this->getVoucherSeries(['vou_name_s'=>'SOrd','tableName'=>'so_master','numberColumn'=>'trans_no','dateColumn'=>'trans_date','entry_date'=>$data['trans_date']]);

                $data['trans_prefix'] = $voucherSeries['vou_prefix'];
                $data['trans_no'] = $voucherSeries['vou_no'];
                $data['trans_number'] = $voucherSeries['vou_number'];
            endif;

            $this->printJson($this->salesOrder->save($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();

        $this->data['dataRow'] = $this->salesOrder->getSalesOrder(['id'=>$data['id'],'itemList'=>1]);

        $this->data['partyList'] = $this->party->getPartyList(['party_type'=>1]);
        $this->data['itemList'] = $this->product->getProductList();
        $this->data['expenseList'] = $this->salesExpense->getSalesExpenseList(['is_active'=>1]);

        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->salesOrder->delete($data));
        endif;
    }
}
?>