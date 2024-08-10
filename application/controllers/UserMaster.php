<?php
class UserMaster extends MY_Controller{
    private $index = "user_master/index";
    private $form = "user_master/form";

    public function __construct(){
        parent::__construct();
        $this->data['headData']->pageTitle = "Users";
		$this->data['headData']->controller = "userMaster";    
        $this->data['headData']->pageUrl = "userMaster";    
    }

    public function index(){
        $this->data['DT_TABLE'] = true;
        $this->load->view($this->index,$this->data);
    }

    public function getUserListing(){
        $postData = $this->input->post();
        $userList = $this->user->getUserDetails($postData);

        $responseHtml = "";$i=($postData['start'] + 1);
        foreach($userList as $row):
            $editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-md', 'call_function':'edit', 'form_id' : 'userForm', 'title' : 'Update User Detail'}";
            $deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'User'}";

            $row->isActive = ($row->is_active == 1)?' <span class="badge badge-light-success mb-2 me-4">Active</span>':'<span class="badge badge-light-danger mb-2 me-4">In-Active</span>';

            $statusBtn = "";
            $statusParam = "{'postData':{'id' : ".$row->id.", 'is_active' : ".(($row->is_active == 1)?0:1)."},'fnsave':'activeInactive','message':'Are you sure want to ".(($row->is_active == 1)?"In-Active":"Active")." this User?'}";
            $statusBtn = '<a class="dropdown-item action-delete" href="javascript:void(0);" onclick="confirmStore('.$statusParam.');">'.getIcon((($row->is_active == 1)?'close_circle':'check_circle')).' '.(($row->is_active == 1)?"In-Active":"Active").'</a>';

            $resetPsw = "";
            if(in_array($this->userRole,[-1,1])):
                $resetParam = "{'postData':{'id' : ".$row->id."},'fnsave':'resetPassword','message':'Are you sure want to Change ".$row->user_name." Password?'}";
                $resetPsw = '<a class="dropdown-item action-delete" href="javascript:void(0);" onclick="confirmStore('.$resetParam.');" >'.getIcon('key').' Reset Password</a>';
            endif;

            $responseHtml .= '<tr>
                <td class="checkbox-column"> '.$i.' </td>
                <td>'.$row->user_code.'</td>
                <td>'.$row->user_name.'</td>
                <td>'.$row->contact_no.'</td>
                <td>'.$row->role_name.'</td>
                <td>'.$row->isActive.'</td>
                <td class="text-center">
                    <div class="d-inline-block jpdm">
                        <a class="dropdown-toggle" href="#" role="button" id="elementDrodpown3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            '.getIcon('more_h').'
                        </a>

                        <div class="dropdown-menu" aria-labelledby="elementDrodpown3" style="will-change: transform;">
                            <a class="dropdown-item" href="javascript:void(0);" onclick="modalAction('.$editParam.');">'.getIcon('edit').' Edit</a>

                            <a class="dropdown-item action-delete" href="javascript:void(0);" onclick="trash('.$deleteParam.');">'.getIcon('delete').' Delete</a>

                            '.$statusBtn.'

                            '.$resetPsw.'
                        </div>
                    </div>
                </td>
            </tr>';
            $i++;
        endforeach;

        $this->printJson(['status'=>1,'dataList'=>$responseHtml]);
    }

    public function addUser(){
        $this->load->view($this->form,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = [];

        if(empty($data['user_code']))
            $errorMessage['user_code'] = "User code is required.";
        if(empty($data['user_name']))
            $errorMessage['user_name'] = "User Name is required.";
        if(empty($data['user_role']))
            $errorMessage['user_role'] = "User Role is required.";
        if(empty($data['contact_no']))  
            $errorMessage['contact_no'] = "Mobile No. is required.";
        if(empty($data['id'])):
            if(empty($data['user_psw'])):
                $errorMessage['user_psw'] = "Password is required.";
            else:
                if($data['user_psw'] != $data['user_psc']):
                    $errorMessage['user_psc'] = "Password mismatch.";
                endif;
            endif;
        endif;

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->user->save($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();

        $this->data['dataRow'] = $this->user->getUserDetails($data);

        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->user->delete($data));
        endif;
    }

    public function activeInactive(){
        $postData = $this->input->post();
        if(empty($postData['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->user->activeInactive($postData));
        endif;
    }

    public function changePassword(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['old_password']))
            $errorMessage['old_password'] = "Old Password is required.";
        if(empty($data['new_password']))
            $errorMessage['new_password'] = "New Password is required.";
        if(empty($data['cpassword']))
            $errorMessage['cpassword'] = "Confirm Password is required.";
        if(!empty($data['new_password']) && !empty($data['cpassword'])):
            if($data['new_password'] != $data['cpassword'])
                $errorMessage['cpassword'] = "Confirm Password and New Password is Not match!.";
        endif;

        if(!empty($errorMessage)):
			$this->printJson(['status'=>0,'message'=>$errorMessage]);
		else:
            $data['id'] = $this->loginId;
			$result =  $this->user->changePassword($data);
			$this->printJson($result);
		endif;
    }

    public function resetPassword(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->user->resetPassword($data['id']));
        endif;
    }
}
?>