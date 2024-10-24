<?php
class Employee extends MY_ApiController{
    public function __construct(){
        parent::__construct();        
        $this->data['headData']->pageTitle = "Employee";
        $this->data['headData']->pageUrl = "api/employee";
        $this->data['headData']->base_url = base_url();
    }

    public function getEmployeeDetail(){
        $this->data['empData'] = $this->user->getUserDetails(['id'=>$this->loginId,'all'=>1]);
        $this->printJson(['status'=>1,'message'=>'Data Found.','data'=>$this->data]);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();
        if(empty($data['emp_name']))
            $errorMessage['emp_name'] = "Employee name is required.";
             
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['emp_name'] = ucwords($data['emp_name']);      
            $this->printJson($this->employee->save($data));
        endif;
    }
}
?>