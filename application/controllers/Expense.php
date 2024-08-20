<?php
class Expense extends MY_Controller{
    private $index = "expense/index";
    private $form = "expense/form";
    private $approveForm = "expense/approve_form";

    public function __construct(){
        parent::__construct();
        $this->data['headData']->pageTitle = "Eexpense";
		$this->data['headData']->controller = "expense";    
        $this->data['headData']->pageUrl = "expense";
    }

    public function index(){
        $this->data['DT_TABLE'] = true;  
        $this->load->view($this->index,$this->data);
    }

    public function getExpenseListing(){
        $data = $this->input->post();
        $expenseList = $this->expense->getExpenseDetails($data);

        $tbody = "";$i=($data['start'] + 1);
        foreach($expenseList as $row):
            $editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-md', 'call_function':'edit', 'form_id' : 'expenseForm', 'title' : 'Update Expense'}";
            $deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Expense'}";
			
			$editbtn = '<a class="dropdown-item" href="javascript:void(0);" onclick="modalAction('.$editParam.');">'.getIcon('edit').' Edit</a>';
            $deleteBtn = '<a class="dropdown-item action-delete" href="javascript:void(0);" onclick="trash('.$deleteParam.');">'.getIcon('delete').' Delete</a>';

            $approveButton = '';
            if(empty($row->approved_by)):
                $approveParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-md', 'form_id' : 'expenseForm', 'title' : 'Approve / Reject Expense ','call_function' : 'getApprovedData' , 'fnsave' : 'saveApprovedData'}";

                $approveButton = '<a class="dropdown-item" href="javascript:void(0)" onclick="modalAction('.$approveParam.');">'.getIcon('check').' Approve/Rejecct</a>';
            else:
				$editbtn = $deleteBtn = '';
			endif;

            $tbody .= '<tr>
                <td class="checkbox-column"> '.$i.' </td>
                <td>'.$row->exp_number.'</td>
                <td>'.formatDate($row->exp_date).'</td>
                <td>'.$row->emp_name.'</td>
                <td>'.$row->expense_type.'</td>
                <td>'.$row->demand_amount.'</td>
                <td>'.$row->amount.'</td>
                <td class="text-center">
                    <div class="d-inline-block jpdm">
                        <a class="dropdown-toggle" href="#" role="button" id="elementDrodpown3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            '.getIcon('more_h').'
                        </a>

                        <div class="dropdown-menu" aria-labelledby="elementDrodpown3" style="will-change: transform;">
                            '.$editbtn.$deleteBtn.$approveButton.'
                        </div>
                    </div>
                </td>
            </tr>';

            $i++;
        endforeach;

        $this->printJson(['status'=>1,'dataList'=>$tbody]);
    }

    public function addExpense(){
        $this->data['exp_prefix'] = "EXP".n2y(date('Y')).n2m(date('m'));  
        $this->data['exp_no'] = $this->expense->getNextExpNo();
        $this->data['expTypeList'] = $this->configuration->getMasterOption(['type'=>3]);
        $this->data['empList'] = $this->employee->getEmployeeDetails();
        $this->load->view($this->form, $this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['exp_number'])){
            $errorMessage['exp_number'] = "Expense Number is required.";
        }  
        if(empty($data['exp_date'])){
            $errorMessage['exp_date'] = "Expense date is required.";
        }      
        if(empty($data['exp_by_id'])){
            $errorMessage['exp_by_id'] = "Employee is required.";
        }     
        if(empty($data['exp_type'])){
            $errorMessage['exp_type'] = "Expense type is required.";
        }      
        if(empty($data['demand_amount'])){
            $errorMessage['demand_amount'] = "Amount is required.";
        }
        
        if(!empty($_FILES['proof_file'])):
            if($_FILES['proof_file']['name'] != null || !empty($_FILES['proof_file']['name'])):
                $this->load->library('upload');
                $_FILES['userfile']['name']     = $_FILES['proof_file']['name'];
                $_FILES['userfile']['type']     = $_FILES['proof_file']['type'];
                $_FILES['userfile']['tmp_name'] = $_FILES['proof_file']['tmp_name'];
                $_FILES['userfile']['error']    = $_FILES['proof_file']['error'];
                $_FILES['userfile']['size']     = $_FILES['proof_file']['size'];
                
                $imagePath = realpath(APPPATH . '../assets/uploads/expense/');
                $config = ['file_name' => time()."_EXP_".$_FILES['userfile']['name'],'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path'	=>$imagePath];

                $this->upload->initialize($config);
                if (!$this->upload->do_upload()):
                    $errorMessage['proof_file'] = $this->upload->display_errors();
                else:
                    $uploadData = $this->upload->data();
                    $data['proof_file'] = $uploadData['file_name'];
                endif;
            endif;
        endif;
      
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:			
            $this->printJson($this->expense->save($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post(); 
        $this->data['dataRow'] = $this->expense->getExpenseDetails($data);        
        $this->data['expTypeList'] = $this->configuration->getMasterOption(['type'=>3]);
        $this->data['empList'] = $this->employee->getEmployeeDetails();
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->expense->delete($data));
        endif;
    }

    public function getApprovedData(){
        $data = $this->input->post(); 
        $this->data['id'] = $data['id'];
        $this->data['dataRow'] = $this->expense->getExpenseDetails($data);
        $this->load->view($this->approveForm,$this->data);
    }

    public function saveApprovedData(){
        $data = $this->input->post();
        $errorMessage = array();
       
        if($data['status'] == 1):
            if(empty($data['amount'])):
                $errorMessage['amount'] = "Amount is required.";
            endif;
        else:
            if(empty($data['rej_reason'])):
                $errorMessage['rej_reason'] = "Reason is required.";
            endif;
        endif;
      
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['approved_by'] = $this->loginId;
            $data['approved_at'] = date('Y-m-d H:i:s');
            $this->printJson($this->expense->save($data));
        endif;
    }
}
?>