<?php
class SalesOrder extends MY_Controller{
    private $index = "sales_order/index";
    private $masterForm = "sales_master_form";
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
            $editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-xxl', 'call_function':'edit', 'form_id' : 'salesOrderForm', 'title' : 'Update Order'}";
            $editButton = '<a class="dropdown-item" href="javascript:void(0);" onclick="modalAction('.$editParam.');">'.getIcon('edit').' Edit</a>';

            $deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Sales Order'}";
            $deleteButton = '<a class="dropdown-item action-delete" href="javascript:void(0);" onclick="trash('.$deleteParam.');">'.getIcon('delete').' Delete</a>';

            $printButton = '<a href="'.base_url('salesOrder/printOrder/'.$row->id).'" class="dropdown-item" target="_blank">'.getIcon('printer').' Print</a>';

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
                            '.$printButton.$editButton.$deleteButton.'
                        </div>
                    </div>
                </td>
            </tr>';

            $i++;
        endforeach;

        $this->printJson(['status'=>1,'dataList'=>$tbody]);
    }

    public function createOrder(){
        $data = $this->input->post();
        $voucherSeries = $this->getVoucherSeries(['vou_name_s'=>'SOrd','tableName'=>'so_master','numberColumn'=>'trans_no','dateColumn'=>'trans_date']);

        $dataRow = $this->salesQuotation->getSalesQuotation(['id'=>$data['id'],'itemList'=>1,'only_pending_items'=>1]);

        foreach($dataRow->itemList as &$row):
            $row->from_vou_name = 'Squot';
            $row->ref_id = $row->id;
            $row->id = "";
        endforeach;

        $dataRow->doc_no = $dataRow->trans_number;
        $dataRow->trans_prefix = "";
        $dataRow->trans_no = "";
        $dataRow->trans_date = "";
        $dataRow->trans_number = "";
        $dataRow->from_vou_name = "Squot";
        $dataRow->from_ref_id = $dataRow->id;
        $dataRow->id = "";

        $this->data['dataRow'] = $dataRow;
        
        $this->data['trans_prefix'] = $voucherSeries['vou_prefix'];
        $this->data['trans_no'] = $voucherSeries['vou_no'];
        $this->data['trans_number'] = $voucherSeries['vou_number'];
        $this->data['partyList'] = $this->party->getPartyList(['party_type'=>"1,2"]);
        $this->data['itemList'] = $this->product->getProductList();
        $this->data['expenseList'] = $this->salesExpense->getSalesExpenseList(['is_active'=>1]);
        
        $this->data['entryType'] = "SOrd";
        $this->load->view($this->masterForm,$this->data);
    }

    public function addSalesOrder(){
        $voucherSeries = $this->getVoucherSeries(['vou_name_s'=>'SOrd','tableName'=>'so_master','numberColumn'=>'trans_no','dateColumn'=>'trans_date']);
        
        $this->data['trans_prefix'] = $voucherSeries['vou_prefix'];
        $this->data['trans_no'] = $voucherSeries['vou_no'];
        $this->data['trans_number'] = $voucherSeries['vou_number'];
        $this->data['partyList'] = $this->party->getPartyList(['party_type'=>1]);
        $this->data['itemList'] = $this->product->getProductList();
        $this->data['expenseList'] = $this->salesExpense->getSalesExpenseList(['is_active'=>1]);
        
        $this->data['entryType'] = "SOrd";
        $this->load->view($this->masterForm,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = [];

        if(empty($data['trans_date']))
            $errorMessage['trans_date'] = "Date is required.";
        if(empty($data['party_id']))
            $errorMessage['party_id'] = "Customer name is required.";
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

        $this->data['entryType'] = "SOrd";
        $this->load->view($this->masterForm,$this->data);
    }

    public function delete(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->salesOrder->delete($data));
        endif;
    }

    public function printOrder($id){
        $this->data['dataRow'] = $dataRow = $this->salesOrder->getSalesOrder(['id'=>$id,'itemList'=>1]);
        $this->data['expenseList'] = $this->salesExpense->getSalesExpenseList(['is_active'=>1]);
        $this->data['partyData'] = $this->party->getParty(['id'=>$dataRow->party_id]);
        $this->data['companyData'] = $this->masterModel->getCompanyInfo();
        
        $this->data['letter_head'] =  base_url('assets/images/letterhead_top.png');

        $prepare = $this->user->getUserDetails(['id'=>$dataRow->created_by]);
		$this->data['dataRow']->prepareBy = $prepareBy = ((!empty($prepare->user_name))?$prepare->user_name:"").' <br>('.formatDate($dataRow->created_at).')'; 
		$this->data['dataRow']->approveBy = $approveBy = '';
		if(!empty($dataRow->is_approve)):
			$approve = $this->user->getUserDetails(['id'=>$dataRow->is_approve]);
			$this->data['dataRow']->approveBy = $approveBy .= $approve->user_name.' <br>('.formatDate($dataRow->approve_date).')'; 
        endif;
 
        $pdfData = $this->load->view('sales_order/print', $this->data, true);   
		$mpdf = new \Mpdf\Mpdf();
        $pdfFileName = str_replace(["/","-"],"_",$dataRow->trans_number) . '.pdf';
        $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->SetDisplayMode('fullpage');
		$mpdf->AddPage('P','','','','',5,5,5,15,5,5,'','','','','','','','','','A4-P');
        $mpdf->WriteHTML($pdfData);
		$mpdf->Output($pdfFileName, 'I');		
    }
}
?>