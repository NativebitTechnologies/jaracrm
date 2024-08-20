<?php
class ExecutiveTarget extends MY_Controller{
    private $indexPage = "executive_target/index";
	public function __construct(){
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "Target";
		$this->data['headData']->controller = "ExecutiveTarget";
        $this->data['headData']->pageUrl = "executiveTarget";
	}
	
	public function index(){
        $this->data['DT_TABLE'] = true;
		$this->data['monthList'] = $this->getMonthListFY();
        $this->load->view($this->indexPage,$this->data);
    }
	
    public function getSalesTargetDetils(){
		$postData = $this->input->post();
        $errorMessage = array();
		
        if(empty($postData['month']))
            $errorMessage['month'] = "Month is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			$postData['executive_target'] = 1;
			$empData = $this->usersModel->getEmployeeList($postData);  print_r($empData); exit;
			
			$targetData=''; $i=1;
			if(!empty($empData)):
				foreach($empData as $row):
					$targetData .= 	'<tr>
						<td>'.$i++.'</td>
						<td>'.$row->emp_code.'</td>
						<td>'.$row->emp_name.'</td>
						<td>'.$row->designation.'</td>
						<td>'.$row->zone_name.'</td>
						<td>
							<input type="hidden" name="id[]" value="'.$row->target_id.'">
							<input type="hidden" name="emp_id[]" value="'.$row->id.'">
							<input type="hidden" name="zone_id[]" value="'.$row->zone_id.'">
							<input type="text" name="new_lead[]" value="'.$row->new_lead.'" class="form-control numericOnly">
						</td>
						<td>
							<input type="text" name="sales_amount[]" value="'.$row->sales_amount.'" class="form-control floatOnly">
						</td>
					</tr>';
				endforeach;
			endif;
			$this->printJson(['status'=>1,'dataList'=>$targetData]);
		endif;
    }

    public function saveTargets(){
        $postData = $this->input->post();
        $errorMessage = array();
        if(empty($postData['month']))
            $errorMessage['month'] = "Month is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			$this->printJson($this->sales->saveTargets($postData));
		endif;
    }	
}
?>