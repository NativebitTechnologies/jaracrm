<?php
class Expense extends MY_ApiController{

    public function __construct(){
        parent::__construct();        
        $this->data['headData']->pageTitle = "Expense";
        $this->data['headData']->pageUrl = "api/expense";
        $this->data['headData']->base_url = base_url();
    }

    public function getExpenseListing(){
        $data = $this->input->post();
        $expenseList = $this->expense->getExpenseDetails($data);

        $sendData = [];$i=($data['start'] + 1);
        foreach($expenseList as $row):
            $sendData[] = [
                'id' => $row->id,
                'emp_name' => $row->emp_name,
                'exp_number' => $row->exp_number,
                'exp_date' => $row->exp_date,
                'expense_type' => $row->expense_type,
                'demand_amount' => $row->demand_amount,
                'pay_mode' => $row->pay_mode,
                'amount' => $row->amount,
                'approved_by' => $row->approved_by
            ];
            $i++;
        endforeach;

        $this->printJson(['status'=>1,'data'=>['dataList'=>$sendData]]);
    }

    public function addExpense(){
        $this->data['exp_prefix'] = "EXP".n2y(date('Y')).n2m(date('m'));  
        $this->data['exp_no'] = $this->expense->getNextExpNo();
        $this->data['expTypeList'] = $this->configuration->getMasterOption(['type'=>3]);
        $this->printJson(['status'=>1,'message'=>'Data Found.','data'=>$this->data]);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['exp_number']))
            $errorMessage['exp_number'] = "Expense Number is required.";  
        if(empty($data['exp_date']))
            $errorMessage['exp_date'] = "Expense date is required.";
        if(empty($data['exp_by_id']))
            $errorMessage['exp_by_id'] = "Employee is required.";
        if(empty($data['exp_type']))
            $errorMessage['exp_type'] = "Expense type is required.";
        if(empty($data['demand_amount']))
            $errorMessage['demand_amount'] = "Amount is required.";
        
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
        $this->printJson(['status'=>1,'message'=>'Data Found.','data'=>$this->data]);
    }

    public function delete(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->expense->delete($data));
        endif;
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