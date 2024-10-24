<?php
class SalesQuotation extends MY_ApiController{

    public function __construct(){
        parent::__construct();  
        $this->data['headData']->pageTitle = "Sales Quotation";
        $this->data['headData']->pageUrl = "api/salesQuotation";
        $this->data['headData']->base_url = base_url();
    }

    public function getSalesQuotationListing(){
        $data = $this->input->post();
        $quotationList = $this->salesQuotation->getSalesQuotationListing($data);

        $sendData = [];$i=($data['start'] + 1);
        foreach($quotationList as $row):
            $sendData[] = [
                'id' => $row->id,
                'trans_number' => $row->trans_number,
                'trans_date' => $row->trans_date,
                'party_name' => $row->party_name,
                'contact_no' => $row->contact_no,
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

    public function addSalesQuotation($party_id=0){
        $voucherSeries = $this->getVoucherSeries(['vou_name_s'=>'Squot','tableName'=>'sq_master','numberColumn'=>'trans_no','dateColumn'=>'trans_date']);
        
        $this->data['trans_prefix'] = $voucherSeries['vou_prefix'];
        $this->data['trans_no'] = $voucherSeries['vou_no'];
        $this->data['trans_number'] = $voucherSeries['vou_number'];
        $this->data['entryType'] = "Squot";
        $this->data['party_id'] = $party_id;

        $this->data['partyList'] = $this->party->getPartyList(['party_type'=>"1,2"]);
        $this->data['itemList'] = $this->product->getProductList();
        $this->data['categoryList'] = $this->product->getCategoryList(['category_type'=>1,'final_category'=>1]);
        $this->data['expenseList'] = $this->salesExpense->getSalesExpenseList(['is_active'=>1]);
		$this->data['termsList'] = $this->configuration->getTermsList();

        $this->printJson(['status'=>1,'message'=>'Data Found.','data'=>$this->data]);
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

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            if(empty($data['id'])):
                $voucherSeries = $this->getVoucherSeries(['vou_name_s'=>'Squot','tableName'=>'sq_master','numberColumn'=>'trans_no','dateColumn'=>'trans_date','entry_date'=>$data['trans_date']]);

                $data['trans_prefix'] = $voucherSeries['vou_prefix'];
                $data['trans_no'] = $voucherSeries['vou_no'];
                $data['trans_number'] = $voucherSeries['vou_number'];
            endif;
			
			$data['conditions'] = (isset($data['conditions']) && !empty($data['conditions']))?json_encode($data['conditions']):"";

            $this->printJson($this->salesQuotation->save($data));
        endif;
    }

    public function changeQuotationStatus(){
        $data = $this->input->post();
        $this->printJson($this->salesQuotation->changeQuotationStatus($data));
    }

    public function delete(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->salesQuotation->delete($data));
        endif;
    }

    public function printQuotation($id){
        $this->data['dataRow'] = $dataRow = $this->salesQuotation->getSalesQuotation(['id'=>$id,'itemList'=>1,'is_print'=>1]);
        $this->data['expenseList'] = $this->salesExpense->getSalesExpenseList(['is_active'=>1]);
        $this->data['partyData'] = $this->party->getParty(['id'=>$dataRow->party_id]);
        $this->data['companyData'] = $companyData = $this->masterModel->getCompanyInfo();
        
        $this->data['letter_head'] =  base_url('assets/images/'.$companyData->company_letterhead);
 
        $pdfData = $this->load->view('sales_quotation/print', $this->data, true);
		
		$mpdf = new \Mpdf\Mpdf();
        $pdfFileName = str_replace(["/","-"],"_",$dataRow->trans_number) . '.pdf';
        $stylesheet = file_get_contents(base_url('assets/src/pdf_style.css'));
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->SetDisplayMode('fullpage');
		$mpdf->AddPage('P','','','','',5,5,5,15,5,5,'','','','','','','','','','A4-P');
        $mpdf->WriteHTML($pdfData);
		$mpdf->Output($pdfFileName, 'I');
    }
}
?>