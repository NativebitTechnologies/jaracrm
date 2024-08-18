<?php
class SalesZone extends MY_Controller{
    private $index = "sales_zone/index";
    private $form = "sales_zone/form";
    private $statutory_detail = "sales_zone/statutory_detail";
    private $zoneTypeArray = [1=>'Manual',3=>'State',4=>'District',5=>'Taluka'];

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Sales Zone";
		$this->data['headData']->controller = "salesZone";
        $this->data['headData']->pageUrl = "salesZone";
	}
	
	public function index(){
        $this->data['DT_TABLE'] = true;
        $this->load->view($this->index,$this->data); 
    }
	
	public function getZoneListing(){
		$data = $this->input->post();
        $productList = $this->configuration->getSalesZoneList($data);

        $tbody = "";$i=($data['start'] + 1);
        foreach($productList as $row):
			$editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-md', 'form_id' : 'editZone', 'title' : 'Update Sales Zone','call_function':'editZone','fnsave' : 'saveZone'}";
			$editButton = '<a class="dropdown-item permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');">'.getIcon('edit').' Edit</a>';

			$deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Zone','fndelete':'deleteZone'}";
			$deleteButton = '<a class="dropdown-item permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down">'.getIcon('delete').' Delete</a>';
		
            $tbody .= '<tr>
                <td class="checkbox-column"> '.$i.' </td>
                <td>'.$row->zone_name.'</td>
                <td>'.$row->remark.'</td>
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
	
    public function addZone(){
        //$this->data['zoneTypeArray'] = $this->zoneTypeArray;
        $this->load->view($this->form, $this->data);
    }

    public function saveZone(){
        $data = $this->input->post(); 
		$errorMessage = array();

        if(empty($data['zone_name']))
			$errorMessage['zone_name'] = "Zone Name is required.";
        
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->configuration->saveZone($data));
        endif;
    }

    public function editZone(){     
        $data = $this->input->post();
        $this->data['dataRow'] = $this->configuration->getSalesZoneList($data);
        //$this->data['zoneTypeArray'] = $this->zoneTypeArray;       
        $this->load->view($this->form, $this->data);
    }

    public function deleteZone(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $checkData['columnName'] = ['sales_zone_id'];
            $checkData['value'] = $id;
            $checkUsed = $this->configuration->checkUsage($checkData);

            if($checkUsed == true):
                return ['status'=>0,'message'=>'The Zone is currently in use. you cannot delete it.'];
            endif;
            $this->printJson($this->configuration->trash('sales_zone',['id'=>$id]));
        endif;
    }
}
?>