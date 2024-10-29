<?php
class SalesOrder extends MY_ApiController{

    public function __construct(){
		parent::__construct();
        $this->data['headData']->pageTitle = "Sales Order";
        $this->data['headData']->pageUrl = "api/salesOrder";
        $this->data['headData']->base_url = base_url();
	}

    public function getSalesOrderListing(){
        $data = $this->input->post();
        $orderList = $this->salesOrder->getSalesOrderListing($data);

        $sendData = [];$i=($data['start'] + 1);
        foreach($orderList as $row):
            $sendData[] = [
                'id' => $row->id,
                'trans_number' => $row->trans_number,
                'trans_date' => $row->trans_date,
                'party_name' => $row->party_name,
                //'contact_no' => $row->contact_no,
                'city' => $row->city,
                'executive_name' => $row->executive_name,
                'taxable_amount' => $row->taxable_amount,
                'gst_amount' => $row->gst_amount,
                'net_amount' => $row->net_amount,
                'trans_status' => $row->trans_status
            ];

            $i++;
        endforeach;

        $this->printJson(['status'=>1,'data'=>['dataList'=>$sendData]]);
    }

    public function addSalesOrder($party_id=0){
        $voucherSeries = $this->getVoucherSeries(['vou_name_s'=>'SOrd','tableName'=>'so_master','numberColumn'=>'trans_no','dateColumn'=>'trans_date']);
        $this->data['trans_prefix'] = $voucherSeries['vou_prefix'];
        $this->data['trans_no'] = $voucherSeries['vou_no'];
        $this->data['trans_number'] = $voucherSeries['vou_number'];
        $this->data['entryType'] = "SOrd";
        $this->data['party_id'] = $party_id;
        $this->data['partyList'] = $this->party->getPartyList();
		$this->data['itemList'] = $this->product->getProductList();
        $this->data['categoryList'] = $this->product->getCategoryList(['category_type'=>1,'final_category'=>1]);
        //$this->data['expenseList'] = $this->salesExpense->getSalesExpenseList(['is_active'=>1]);
        //$this->data['termsList'] = $this->configuration->getTermsList();

        $this->printJson(['status'=>1,'message'=>'Data Found.','data'=>$this->data]);
    }

    public function save(){
        $data = $this->input->post();
        if(!empty($data['itemData'])): $data['itemData'] = json_decode($data['itemData'],true); endif;
        $errorMessage = []; //print_r($data);exit;

        if(empty($data['trans_date']))
            $errorMessage['trans_date'] = "Date is required.";
        if(empty($data['party_id']))
            $errorMessage['party_id'] = "Customer name is required.";
        if(empty($data['itemData']))
            $errorMessage['itemData'] = "Item Detail is required.";

		/* if(isset($_FILES['order_file'])):
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
		endif; */

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            if(empty($data['id'])):
                $voucherSeries = $this->getVoucherSeries(['vou_name_s'=>'SOrd','tableName'=>'so_master','numberColumn'=>'trans_no','dateColumn'=>'trans_date','entry_date'=>$data['trans_date']]);

                $data['trans_prefix'] = $voucherSeries['vou_prefix'];
                $data['trans_no'] = $voucherSeries['vou_no'];
                $data['trans_number'] = $voucherSeries['vou_number'];
            endif;
            
			$data['conditions'] = (isset($data['conditions']) && !empty($data['conditions']))?json_encode($data['conditions']):"";

            $this->printJson($this->salesOrder->save($data));
        endif;
    }

    public function changeOrderStatus(){
        $data = $this->input->post();
        $this->printJson($this->salesOrder->changeOrderStatus($data));
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
        $this->data['dataRow'] = $dataRow = $this->salesOrder->getSalesOrder(['id'=>$id,'itemList'=>1,'is_print'=>1]);
        $this->data['expenseList'] = $this->salesExpense->getSalesExpenseList(['is_active'=>1]);
        $this->data['partyData'] = $this->party->getParty(['id'=>$dataRow->party_id]);
        $this->data['companyData'] = $companyData = $this->masterModel->getCompanyInfo();
        
        $this->data['letter_head'] =  base_url('assets/images/'.$companyData->company_letterhead);
 
        $pdfData = $this->load->view('sales_order/print', $this->data, true);   
		
		$mpdf = new \Mpdf\Mpdf();
        $pdfFileName = str_replace(["/","-"],"_",$dataRow->trans_number) . '.pdf';
        $stylesheet = file_get_contents(base_url('assets/src/pdf_style.css'));
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->SetDisplayMode('fullpage');
		$mpdf->AddPage('P','','','','',5,5,5,15,5,5,'','','','','','','','','','A4-P');
        $mpdf->WriteHTML($pdfData);
		$mpdf->Output($pdfFileName, 'F');			
    }
}
?>