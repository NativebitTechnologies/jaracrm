<?php
class AddressDetail extends MY_Controller{
    private $index = "address_detail/index";
    private $form = "address_detail/form";
	
	public function __construct(){
        parent::__construct();
		$this->data['headData']->pageTitle = "Address Detail";
		$this->data['headData']->controller = "addressDetail";    
        $this->data['headData']->pageUrl = "addressDetail";    
    }
	
	public function index(){
        $this->data['DT_TABLE'] = true;
        $this->load->view($this->index,$this->data); 
    }
	
	public function getAddressList(){
		$data = $this->input->post();
        $productList = $this->address->getAddressList($data);

        $tbody = "";$i=($data['start'] + 1);
        foreach($productList as $row):
			$editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-md', 'form_id' : 'editAddress', 'title' : 'Update Address','call_function':'editAddress','fnsave' : 'saveAddress'}";
			$editButton = '<a class="dropdown-item permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');">'.getIcon('edit').' Edit</a>';

			$deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Product','fndelete':'deleteAddress'}";
			$deleteButton = '<a class="dropdown-item permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down">'.getIcon('delete').' Delete</a>';
		
            $tbody .= '<tr>
                <td class="checkbox-column"> '.$i.' </td>
                <td>'.$row->country.'</td>
                <td>'.$row->state.'</td>
                <td>'.$row->district.'</td>
                <td>'.$row->city.'</td>
                <td>
                    <div class="d-inline-block jpdm">
                        <a class="dropdown-toggle" href="#" role="button" id="elementDrodpown3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="elementDrodpown3" style="will-change: transform;">
							'.$editButton.$deleteButton.'
                        </div>
                    </div>
                </td>
            </tr>';

            $i++;
        endforeach;
        $this->printJson(['status'=>1,'dataList'=>$tbody]);
	}

	public function addAddress(){
        $this->load->view($this->form,$this->data);
	}

    public function saveAddress(){
        $data = $this->input->post(); 
        $errorMessage = array();
		if(empty($data['country'])){
			$errorMessage['country'] = "Country is required.";
		}elseif(strtolower($data['country']) == 'india'){
			if(empty($data['state_code'])){
				$errorMessage['state_code'] = "State Code is required.";
			}
		}
		if(empty($data['state']))
			$errorMessage['state'] = "State is required.";
		if(empty($data['district']))
			$errorMessage['district'] = "District is required.";
		if(empty($data['city']))
			$errorMessage['city'] = "City is required.";
		

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:        
            $this->printJson($this->address->saveAddress($data)); 
        endif;
    }

    public function editAddress(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->address->getAddressList($data); 
        $this->load->view($this->form,$this->data);
    }

    public function deleteAddress(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->address->deleteAddress($data));
        endif;
    }

	public function getAddressSearch(){
		$data = $this->input->post();
		$result = $this->address->getAddressList($data);
		$this->printJson($result);
	}
}
?>
	