<?php
class UserMasterModel extends MasterModel{
    private $userMaster = "user_master"; 

    public function getUserDetails($data=[]){
        $queryData = [];
        $queryData['tableName'] = $this->userMaster;
        $queryData['select'] = "user_master.*,(CASE WHEN user_master.user_role = 1 THEN 'Admin' WHEN user_master.user_role = 2 THEN 'Employee' WHEN user_master.user_role = 3 THEN 'Customer' ELSE '' END) as role_name";

        if(isset($data['is_active'])):
            $queryData['where']['user_master.is_active'] = $data['is_active'];
        endif;

        if(!empty($data['id'])):
            $queryData['where']['user_master.id'] = $data['id'];
        endif;

		if(empty($data['all'])):
			if(empty($data['user_role'])):
				$queryData['where_not_in']['user_master.user_role'] = [-1];
			else:
				$queryData['where_in']['user_master.user_role'] = $data['user_role'];
			endif;
		endif;

        if(!empty($data['search'])):
            $queryData['like']['user_master.user_code'] = $data['search'];
            $queryData['like']['user_master.user_name'] = $data['search'];
            $queryData['like']['user_master.contact_no'] = $data['search'];
            $queryData['like']['(CASE WHEN user_master.is_active = 1 THEN "Active" ELSE "In-Active" END)'] = $data['search'];
            $queryData['like']["(CASE WHEN user_master.user_role = 1 THEN 'Admin' WHEN user_master.user_role = 2 THEN 'Employee' WHEN user_master.user_role = 3 THEN 'Customer' ELSE '' END)"] = $data['search'];
        endif;

        if(!empty($data['limit'])): 
            $queryData['limit'] = $data['limit']; 
            $queryData['order_by']['user_master.created_at'] = "DESC"; 
        endif;

        if(isset($data['start']) && isset($data['length'])):
            $queryData['start'] = $data['start'];
            $queryData['length'] = $data['length'];
        endif;

        if(!empty($data['id']) || !empty($data['single_row'])):
            $result = $this->getData($queryData,'row');
        else:
            $result = $this->getData($queryData,'rows');
        endif;

        return $result;
    }

    public function save($data){
        try {
            $this->db->trans_begin();

            $data['checkDuplicate']['first_key'] = 'user_code';
            $data['checkDuplicate']['customWhere'] = "((user_code = '".$data['user_code']."') or (contact_no = '".$data['contact_no']."'))";

            if(empty($data['id'])):
                $data['user_psw'] = md5($data['user_psw']);
            endif;

            $result = $this->store($this->userMaster,$data,'User');

            if(!empty($data['ref_id']) && $result['status'] == 1):
                if($data['user_role'] == 2):
                    $setData = [];
                    $setData['tableName'] = "employee_master";
                    $setData['where']['id'] = $data['ref_id'];
                    $setData['update']['user_id'] = $result['id'];
                    $this->setValue($setData);
                elseif($data['user_role'] == 3):
                    $setData = [];
                    $setData['tableName'] = "party_master";
                    $setData['where']['id'] = $data['ref_id'];
                    $setData['update']['user_id'] = $result['id'];
                    $this->setValue($setData);
                endif;
            endif;

            if ($this->db->trans_status() !== FALSE) :
                $this->db->trans_commit();
                return $result;
            endif;
        } catch (\Exception $e) {
            $this->db->trans_rollback();
            return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
        }
    }

    public function delete($data){
        try{
            $this->db->trans_begin();

            $result = $this->trash($this->userMaster,['id'=>$data['id']],'User');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function activeInactive($postData){
        try{
            $this->db->trans_begin();

            $result = $this->store($this->userMaster,$postData,'');
            $result['message'] = "User ".(($postData['is_active'] == 1)?"Activated":"De-activated")." successfully.";
            
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function changePassword($data){
        try{
            $this->db->trans_begin();

            if(empty($data['id'])):
                return ['status'=>2,'message'=>'Somthing went wrong...Please try again.'];
            endif;

            $userData = $this->getUserDetails(['id'=>$data['id']]);
            if(md5($data['old_password']) != $userData->user_psw):
                return ['status'=>0,'message'=>['old_password'=>"Old password not match."]];
            endif;

            $postData = ['id'=>$data['id'],'user_psw'=>md5($data['new_password']),'user_psc'=>$data['new_password']];
            $result = $this->store($this->userMaster,$postData);
            $result['message'] = "Password changed successfully.";

            if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function resetPassword($id){
        try{
            $this->db->trans_begin();

            $data['id'] = $id;
            $data['user_psc'] = '123456';
            $data['user_psw'] = md5($data['user_psc']); 
            
            $result = $this->store($this->userMaster,$data);
            $result['message'] = 'Password Reset successfully.';

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
	}
}
?>