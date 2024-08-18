<?php
class EmployeeMaster extends MY_Controller{
    private $index = "emp_master/index";
    private $form = "emp_master/form";
	private $leave_index = "emp_master/leave_index";
	private $leave_form = "emp_master/leave_form";

    public function __construct(){
        parent::__construct();
        $this->data['headData']->pageTitle = "Employee";
		$this->data['headData']->controller = "employeeMaster";    
        $this->data['headData']->pageUrl = "employeeMaster";    
    }

    public function index(){
        $this->data['DT_TABLE'] = true;
        $this->load->view($this->index,$this->data);
    }

    public function getEmployeeListing(){ 
        $postData = $this->input->post();
        $empList = $this->employee->getEmployeeDetails($postData);

        $responseHtml = "";$i=($postData['start'] + 1);
        foreach($empList as $row):
            $editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-md', 'call_function':'editEmployee', 'fnsave':'saveEmployee', 'form_id' : 'editEmployee', 'title' : 'Update Employee'}";
            $deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Employee','fndelete':'deleteEmployee'}";

            $row->isActive = ($row->user_status == 1)?' <span class="badge badge-light-success mb-2 me-4">Active</span>':'<span class="badge badge-light-danger mb-2 me-4">In-Active</span>';

            $statusBtn = "";
            $statusParam = "{'postData':{'id' : ".$row->id.", 'user_status' : ".(($row->user_status == 1)?0:1)."},'fnsave':'activeInactive','message':'Are you sure want to ".(($row->user_status == 1)?"In-Active":"Active")." this Employee?'}";
            //$statusBtn = '<a class="dropdown-item action-delete" href="javascript:void(0);" onclick="confirmStore('.$statusParam.');">'.getIcon((($row->user_status == 1)?'close_circle':'check_circle')).' '.(($row->user_status == 1)?"In-Active":"Active").'</a>';

            $responseHtml .= '<tr>
                <td class="checkbox-column"> '.$i.' </td>
                <td>'.$row->emp_code.'</td>
                <td>'.$row->emp_name.'</td>
                <td>'.$row->designation.'</td>
                <td>'.$row->contact_no.'</td>
                <!--<td>'.$row->isActive.'</td>-->
                <td class="text-center">
                    <div class="d-inline-block jpdm">
                        <a class="dropdown-toggle" href="#" role="button" id="elementDrodpown3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            '.getIcon('more_h').'
                        </a>

                        <div class="dropdown-menu" aria-labelledby="elementDrodpown3" style="will-change: transform;">
                            <a class="dropdown-item" href="javascript:void(0);" onclick="modalAction('.$editParam.');">'.getIcon('edit').' Edit</a>

                            <a class="dropdown-item action-delete" href="javascript:void(0);" onclick="trash('.$deleteParam.');">'.getIcon('delete').' Delete</a>

                            '.$statusBtn.'
                        </div>
                    </div>
                </td>
            </tr>';
            $i++;
        endforeach;

        $this->printJson(['status'=>1,'dataList'=>$responseHtml]);
    }

    public function addEmployee(){
        $this->load->view($this->form,$this->data);
    }

    public function saveEmployee(){
        $data = $this->input->post();
        $errorMessage = [];

        if(empty($data['emp_code']))
            $errorMessage['emp_code'] = "Employee code is required.";
        if(empty($data['emp_name']))
            $errorMessage['emp_name'] = "Employee Name is required.";
        if(empty($data['designation']))
            $errorMessage['designation'] = "Designation is required.";
        if(empty($data['contact_no']))  
            $errorMessage['contact_no'] = "Mobile No. is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->employee->saveEmployee($data));
        endif;
    }

    public function editEmployee(){
        $data = $this->input->post();

        $this->data['dataRow'] = $this->employee->getEmployeeDetails($data);

        $this->load->view($this->form,$this->data);
    }

    public function deleteEmployee(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->employee->deleteEmployee($data));
        endif;
    }

    public function activeInactive(){
        $postData = $this->input->post();
        if(empty($postData['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->employee->activeInactive($postData));
        endif;
    }
	
	public function getDesignation(){
		$data = $this->input->post();
		$result = $this->employee->getEmployeeDetails($data);
		$this->printJson($result);
	}
	
	/**** LEAVE ****/
	public function leaveIndex(){
        $this->data['headData']->pageTitle = "Leave Request";
        $this->data['DT_TABLE'] = true;
        $this->load->view($this->leave_index,$this->data);
    }

    public function getLeaveListing(){
        $postData = $this->input->post();
        $leaveList = $this->employee->getLeaveDetails($postData);

        $responseHtml = "";$i=($postData['start'] + 1);
        foreach($leaveList as $row):
			$editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-lg', 'call_function':'editLeave','fnsave':'saveLeave', 'form_id' : 'editLeave', 'title' : 'Update Leave'}";
			$editBtn = '<a class="dropdown-item" href="javascript:void(0);" onclick="modalAction('.$editParam.');">'.getIcon('edit').' Edit</a>';
				
			$deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Leave','fndelete':'deleteLeave'}";
			$deleteBtn = '<a class="dropdown-item action-delete" href="javascript:void(0);" onclick="trash('.$deleteParam.');">'.getIcon('delete').' Delete</a>';

            $approveParam = "{'postData':{'id' : ".$row->id.", 'leave_status' : 2},'fnsave':'leaveStatus','message':'Are you sure want to Approve this Leave?'}";
            $approveBtn = '<a class="dropdown-item action-approve" href="javascript:void(0);" onclick="confirmStore('.$approveParam.');">'.getIcon('check_circle').' Approve</a>';

            $rejParam = "{'postData':{'id' : ".$row->id.", 'leave_status' : 3},'fnsave':'leaveStatus','message':'Are you sure want to Reject this Leave?'}";
            $rejBtn = '<a class="dropdown-item action-reject" href="javascript:void(0);" onclick="confirmStore('.$rejParam.');">'.getIcon('close_circle').' Reject</a>';

            $row->status = '';
			if($row->leave_status == 1){ $row->status = '<span class="badge badge-light-danger mb-2 me-4">Pending</span>'; }
			elseif($row->leave_status == 2){ $row->status = ' <span class="badge badge-light-success mb-2 me-4">Approved</span>'; $editBtn = $deleteBtn = '';  }
			elseif($row->leave_status == 3){ $row->status = ' <span class="badge badge-light-warning mb-2 me-4">Rejected</span>'; $editBtn = $deleteBtn = ''; }
			
            $responseHtml .= '<tr>
                <td class="checkbox-column"> '.$i.' </td>
                <td>'.$row->emp_name.'</td>
                <td>'.formatDate($row->start_date).'</td>
                <td>'.formatDate($row->end_date).'</td>
				<td>'.floatval($row->total_days).' Days</td>
				<td>'.$row->reason.'</td>
                <td>'.$row->status.'</td>
                <td class="text-center">
                    <div class="d-inline-block jpdm">
                        <a class="dropdown-toggle" href="#" role="button" id="elementDrodpown3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            '.getIcon('more_h').'
                        </a>

                        <div class="dropdown-menu" aria-labelledby="elementDrodpown3" style="will-change: transform;">
                            '.$approveBtn.$rejBtn.$editBtn.$deleteBtn.'
                        </div>
                    </div>
                </td>
            </tr>';
            $i++;
        endforeach;

        $this->printJson(['status'=>1,'dataList'=>$responseHtml]);
    }
	
	public function saveLeave(){
		$data = $this->input->post();
        $errorMessage = array();
		
		if(empty($data['emp_id']))
            $errorMessage['emp_id'] = "Employee is required.";
		if(empty($data['start_date']))
            $errorMessage['start_date'] = "Start Date is required.";
		if(empty($data['end_date']))
            $errorMessage['end_date'] = "End Date is required.";
		if(empty($data['reason']))
            $errorMessage['reason'] = "Reason is required.";    
		if(empty($data['total_days']))
            $errorMessage['total_days'] = "Total Days is required.";
			
		if(!empty($errorMessage)):
			$this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			$this->printJson($this->employee->saveLeave($data));
        endif;
	}
	
	public function editLeave(){
		$data = $this->input->post();
		$this->data['empList'] = $this->employee->getEmployeeDetails();
		$this->data['dataRow'] = $this->employee->getLeaveDetails($data);
        $this->load->view($this->leave_form,$this->data);
	}
	
	public function deleteLeave(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->employee->deleteLeave($data));
        endif;
    }

    public function leaveStatus(){
        $postData = $this->input->post();
        if(empty($postData['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->employee->leaveStatus($postData));
        endif;
    }
	/**** LEAVE END ****/
}
?>