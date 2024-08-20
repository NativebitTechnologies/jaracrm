<?php
class SalesEnquiry extends MY_Controller{
    private $index = "sales_enquiry/index";
    private $form = "sales_enquiry/form";

    public function __construct(){
        parent::__construct();
        $this->data['headData']->pageTitle = "Sales Enquiry";
		$this->data['headData']->controller = "salesEnquiry";    
        $this->data['headData']->pageUrl = "salesEnquiry";
    }

    public function index(){
        $this->data['DT_TABLE'] = true;  
        $this->load->view($this->index,$this->data);
    }

    public function getSalesEnquiryListing(){
        $data = $this->input->post();
        $enquiryList = $this->salesEnquiry->getSalesEnquiryListing($data);

        $tbody = "";$i=($data['start'] + 1);
        foreach($enquiryList as $row):

            $editButton = $deleteButton = $quotationButton = '';
            if(empty($row->trans_status)):
                $editParam = "{'postData':{'id' : ".$row->se_id."},'modal_id' : 'modal-xxl', 'call_function':'edit', 'form_id' : 'salesEnquiryForm', 'title' : 'Update Enquiry'}";
                $editButton = '<a class="dropdown-item" href="javascript:void(0);" onclick="modalAction('.$editParam.');">'.getIcon('edit').' Edit</a>';

                $deleteParam = "{'postData':{'id' : ".$row->se_id."},'message' : 'Sales Enquiry'}";
                $deleteButton = '<a class="dropdown-item action-delete" href="javascript:void(0);" onclick="trash('.$deleteParam.');">'.getIcon('delete').' Delete</a>';

                $quotationParam = "{'postData':{'id': ".$row->se_id."},'modal_id' : 'modal-xxl', 'call_function':'addSalesQuotation', 'form_id' : 'quotationForm', 'title' : 'Add Sales Quotation', 'controller' : 'salesQuotation', 'call_function' : 'createQuotation', 'fnsave' : 'save'}";
                $quotationButton = '<a href="javascript:void(0);" class="dropdown-item" onclick="modalAction('.$quotationParam.');">'.getIcon('plus').' Create Quotation</a>';
            endif;

            $tbody .= '<tr>
                <td class="checkbox-column"> '.$i.' </td>
                <td>'.$row->trans_number.'</td>
                <td>'.$row->trans_date.'</td>
                <td>'.$row->party_name.'</td>
                <td>'.$row->executive_name.'</td>
                <td>'.$row->item_name.'</td>
                <td>'.$row->qty.'</td>
                <td>'.$row->uom.'</td>
                <td>'.$row->item_remark.'</td>
                <td class="text-center">
                    <div class="d-inline-block jpdm">
                        <a class="dropdown-toggle" href="#" role="button" id="elementDrodpown3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            '.getIcon('more_h').'
                        </a>

                        <div class="dropdown-menu" aria-labelledby="elementDrodpown3" style="will-change: transform;">
                            '.$editButton.$deleteButton.$quotationButton.'
                        </div>
                    </div>
                </td>
            </tr>';

            $i++;
        endforeach;

        $this->printJson(['status'=>1,'dataList'=>$tbody]);
    }

    public function addSalesEnquiry(){
        $voucherSeries = $this->getVoucherSeries(['vou_name_s'=>'SEnq','tableName'=>'se_master','numberColumn'=>'trans_no','dateColumn'=>'trans_date']);
        
        $this->data['trans_prefix'] = $voucherSeries['vou_prefix'];
        $this->data['trans_no'] = $voucherSeries['vou_no'];
        $this->data['trans_number'] = $voucherSeries['vou_number'];
        $this->data['partyList'] = $this->party->getPartyList(['party_type'=>"1,2"]);
        $this->data['itemList'] = $this->product->getProductList();
        
        $this->load->view($this->form,$this->data);
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
                $voucherSeries = $this->getVoucherSeries(['vou_name_s'=>'SEnq','tableName'=>'se_master','numberColumn'=>'trans_no','dateColumn'=>'trans_date','entry_date'=>$data['trans_date']]);

                $data['trans_prefix'] = $voucherSeries['vou_prefix'];
                $data['trans_no'] = $voucherSeries['vou_no'];
                $data['trans_number'] = $voucherSeries['vou_number'];
            endif;

            $this->printJson($this->salesEnquiry->save($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();

        $this->data['dataRow'] = $this->salesEnquiry->getSalesEnquiry(['id'=>$data['id'],'itemList'=>1]);

        $this->data['partyList'] = $this->party->getPartyList(['party_type'=>"1,2"]);
        $this->data['itemList'] = $this->product->getProductList();
        
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->salesEnquiry->delete($data));
        endif;
    }
}
?>