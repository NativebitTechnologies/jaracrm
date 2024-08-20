<?php
class SalesQuotation extends MY_Controller{
    private $index = "sales_quotation/index";
    private $masterForm = "sales_master_form";
    private $form = "sales_quotation/form";

    public function __construct(){
        parent::__construct();
        $this->data['headData']->pageTitle = "Sales Quotation";
		$this->data['headData']->controller = "salesQuotation";    
        $this->data['headData']->pageUrl = "salesQuotation";  
    }

    public function index(){
        $this->data['DT_TABLE'] = true;  
        $this->load->view($this->index,$this->data);
    }

    public function getSalesQuotationListing(){
        $data = $this->input->post();
        $orderList = $this->salesQuotation->getSalesQuotationListing($data);

        $tbody = "";$i=($data['start'] + 1);
        foreach($orderList as $row):
            $editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-xxl', 'call_function':'edit', 'form_id' : 'quotationForm', 'title' : 'Update Quotation'}";

            $deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Sales Quotation'}";

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

    public function createQuotation(){
        $data = $this->input->post();

        $voucherSeries = $this->getVoucherSeries(['vou_name_s'=>'Squot','tableName'=>'sq_master','numberColumn'=>'trans_no','dateColumn'=>'trans_date']);

        $dataRow = $this->salesEnquiry->getSalesEnquiry(['id'=>$data['id'],'itemList'=>1,'only_pending_items'=>1]);

        foreach($dataRow->itemList as &$row):
            $row->from_vou_name = 'SEnq';
            $row->ref_id = $row->id;
            $row->id = "";

            $row->taxable_amount = $row->amount = round(($row->qty * $row->price),2);
            $row->disc_per = $row->disc_amount = 0;
            $row->gst_amount = (floatval($row->gst_per) > 0)?round((($row->taxable_amount * $row->gst_per) / 100),):0;
            $row->net_amount = round(($row->taxable_amount + $row->gst_amount),2);
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

        $this->data['entryType'] = "Squot";
        $this->load->view($this->masterForm,$this->data);
    }

    public function addSalesQuotation(){
        $voucherSeries = $this->getVoucherSeries(['vou_name_s'=>'Squot','tableName'=>'sq_master','numberColumn'=>'trans_no','dateColumn'=>'trans_date']);
        
        $this->data['trans_prefix'] = $voucherSeries['vou_prefix'];
        $this->data['trans_no'] = $voucherSeries['vou_no'];
        $this->data['trans_number'] = $voucherSeries['vou_number'];
        $this->data['partyList'] = $this->party->getPartyList(['party_type'=>"1,2"]);
        $this->data['itemList'] = $this->product->getProductList();
        $this->data['expenseList'] = $this->salesExpense->getSalesExpenseList(['is_active'=>1]);

        $this->data['entryType'] = "Squot";
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

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            if(empty($data['id'])):
                $voucherSeries = $this->getVoucherSeries(['vou_name_s'=>'Squot','tableName'=>'sq_master','numberColumn'=>'trans_no','dateColumn'=>'trans_date','entry_date'=>$data['trans_date']]);

                $data['trans_prefix'] = $voucherSeries['vou_prefix'];
                $data['trans_no'] = $voucherSeries['vou_no'];
                $data['trans_number'] = $voucherSeries['vou_number'];
            endif;

            $this->printJson($this->salesQuotation->save($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();

        $this->data['dataRow'] = $this->salesQuotation->getSalesQuotation(['id'=>$data['id'],'itemList'=>1]);

        $this->data['partyList'] = $this->party->getPartyList(['party_type'=>"1,2"]);
        $this->data['itemList'] = $this->product->getProductList();
        $this->data['expenseList'] = $this->salesExpense->getSalesExpenseList(['is_active'=>1]);

        $this->data['entryType'] = "Squot";
        $this->load->view($this->masterForm,$this->data);
    }

    public function delete(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->salesQuotation->delete($data));
        endif;
    }

    /* public function getPendingPartyQuotation(){
        $data = $this->input->post();
        $this->data['itemList'] = $this->salesQuotation->getPendingPartyQuotation($data);
        $this->load->view("sales_quotation/create",$this->data);
    } */
}
?>