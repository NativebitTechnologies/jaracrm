<?php
class EmployeeModel extends MasterModel{
    private $empMaster = "employee_master";
    private $attendance_log = "attendance_log";
    private $leaveMaster = "leave_master";
    private $taskMaster = "task_master";
    private $task_log = "task_log";

    /********** Employee **********/
		public function getEmployeeDetails($data=[]){
			$queryData = [];
			$queryData['tableName'] = $this->empMaster;
			$queryData['select'] = "employee_master.*";

			if(isset($data['user_status'])):
				$queryData['where']['employee_master.user_status'] = $data['user_status'];
			endif;

			if(!empty($data['id'])):
				$queryData['where']['employee_master.id'] = $data['id'];
			endif;

			if(!empty($data['search'])):
				$queryData['like']['employee_master.emp_code'] = $data['search'];
				$queryData['like']['employee_master.emp_name'] = $data['search'];
				$queryData['like']['employee_master.designation'] = $data['search'];
				$queryData['like']['employee_master.contact_no'] = $data['search'];
				//$queryData['like']['(CASE WHEN employee_master.user_status = 1 THEN "Active" ELSE "In-Active" END)'] = $data['search'];
			endif;
			
			if(!empty($data['designation'])){
				$queryData['select'] = "DISTINCT(designation) as designation";
				$queryData['like']['designation'] = $data['query']; 
			}

            if(!in_array($this->userRole,[1,-1])):
                if($this->leadRights == 2): // Zone Wise Leads Rights
                    $queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.user_id = '.$this->loginId.')';
                elseif($this->leadRights == 1):
                    $queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.user_id = '.$this->loginId.')';
                endif;
            endif;

			if(!empty($data['limit'])): 
				$queryData['limit'] = $data['limit']; 
				$queryData['order_by']['employee_master.created_at'] = "DESC"; 
			endif;

			if(isset($data['start']) && isset($data['length'])):
				$queryData['start'] = $data['start'];
				$queryData['length'] = $data['length'];
			endif;

			if(!empty($data['result_type'])):
				$result = $this->getData($queryData,$data['result_type']);
			elseif(!empty($data['id'])):
				$result = $this->getData($queryData,'row');
			else:
				$result = $this->getData($queryData,'rows');
			endif;

			return $result;
		}

		public function saveEmployee($data){
			try {
				$this->db->trans_begin();

				$data['checkDuplicate']['first_key'] = 'emp_code';
				$data['checkDuplicate']['customWhere'] = "((emp_code = '".$data['emp_code']."') or (contact_no = '".$data['contact_no']."'))";

				$result = $this->store($this->empMaster,$data,'User');

				if ($this->db->trans_status() !== FALSE) :
					$this->db->trans_commit();
					return $result;
				endif;
			} catch (\Exception $e) {
				$this->db->trans_rollback();
				return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
			}
		}

		public function deleteEmployee($data){
			try{
				$this->db->trans_begin();

				$result = $this->trash($this->empMaster,['id'=>$data['id']],'User');

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

				$result = $this->store($this->empMaster,$postData,'');
				$result['message'] = "Employee ".(($postData['user_status'] == 1)?"Activated":"De-activated")." successfully.";
				
				if ($this->db->trans_status() !== FALSE):
					$this->db->trans_commit();
					return $result;
				endif;
			}catch(\Throwable $e){
				$this->db->trans_rollback();
				return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
			}	
		}
	/********** End Employee **********/

    /********** Attendance **********/
        public function getEmployeeData(){
            $data['tableName'] = $this->attendance_log;
            $data['where']['emp_id'] = $this->loginId;
            $data['order_by']['punch_date'] = "DESC";
            $data['limit'] = 1;
            return $this->getData($queryData,"row");
        }

        public function getEmpLogData(){
            $data['tableName'] = $this->attendance_log;
            $data['where']['emp_id'] = $this->loginId;
            $data['order_by']['punch_date'] = "DESC";
            return $this->getData($queryData,"rows");
        }

        public function saveAttendance($data){
            try{
                $this->db->trans_begin();
                
                $result = $this->store($this->attendance_log,$data,'Attendance Log');
                
                // Insert Location Log
                $locLog = Array();
                $locLog['log_type'] = ($data['type'] == 'IN') ? 1 : 2;
                $locLog['emp_id'] = $this->loginId;
                $locLog['log_time'] = $data['punch_date'];
                $locLog['location'] = $data['start_location'];
                $locLog['address'] = $data['loc_add'];
                $llResult = $this->saveLocationLog($locLog);

                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }	
        }

        public function getPunchByDate($param = []){
            $data['tableName'] = $this->attendance_log;
            $data['select'] = "attendance_log.*,employee_master.emp_code,employee_master.emp_name";
            $data['leftJoin']['employee_master'] = "employee_master.id = attendance_log.emp_id AND employee_master.user_status = 1";
            if(!empty($param['from_date'])){$data['where']['DATE(attendance_log.punch_date) >= '] = $param['from_date'];}
            if(!empty($param['to_date'])){$data['where']['DATE(attendance_log.punch_date) <= '] = $param['to_date'];}
            if(!empty($param['report_date'])){$data['where']['DATE(attendance_log.punch_date)'] = $param['report_date'];}
            
            // 20-05-2024
            if(!in_array($this->userRole,[1,-1])):
                if($this->leadRights == 2): // Zone Wise Leads Rights
                    $data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
                elseif($this->leadRights == 1):
                    $data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
                endif;
            endif;

            return $this->getData($data,"rows");
        }

        public function getMonthlyAttendance($param = []){
            $data['tableName'] = $this->empMaster;
            $data['select'] = "employee_master.*,DATE(aLog.punch_date) as punch_date,lm.leave_date";

            $data['leftJoin']['(SELECT punch_date,emp_id FROM attendance_log WHERE is_delete = 0 AND DATE(punch_date) >= "'.$param['month'].'" AND MONTH(punch_date) = "'.date('m',strtotime($param['month'])).'" AND YEAR(punch_date) = "'.date('Y',strtotime($param['month'])).'" GROUP BY DATE(punch_date),emp_id) as aLog'] = "employee_master.id = aLog.emp_id AND employee_master.user_status = 1";

            $data['leftJoin']['(SELECT leave_date,emp_id FROM leave_master WHERE is_delete = 0 AND approve_by > 0 AND leave_date >= "'.$param['month'].'" AND MONTH(leave_date) = "'.date('m',strtotime($param['month'])).'" AND YEAR(leave_date) = "'.date('Y',strtotime($param['month'])).'" GROUP BY leave_date,emp_id) as lm'] = "employee_master.id = lm.emp_id AND employee_master.user_status = 1";
            
            $data['where']['employee_master.emp_role !='] = "-1";
            $data['order_by']['employee_master.emp_code'] = "ASC";
            return $this->getData($data,"rows");
        }
    /********** End Attendance **********/

    /********** leave **********/
        public function getLeaveDetails($data=[]){
			$queryData = [];
			$queryData['tableName'] = $this->leaveMaster;
			$queryData['select'] = "leave_master.*,employee_master.emp_name";
			$queryData['leftJoin']['employee_master'] = "employee_master.id = leave_master.emp_id";
			
			if(isset($data['leave_status'])):
				$queryData['where']['leave_master.leave_status'] = $data['leave_status'];
			endif;
			
			if(!empty($data['id'])):
				$queryData['where']['leave_master.id'] = $data['id'];
			endif;
			
			if(!empty($data['search'])):
				$queryData['like']['employee_master.emp_name'] = $data['search'];
				$queryData['like']['DATE_FORMAT(leave_master.start_date,"%d-%m-%Y")'] = $data['search'];
				$queryData['like']['DATE_FORMAT(leave_master.end_date,"%d-%m-%Y")'] = $data['search'];
				$queryData['like']['leave_master.total_days'] = $data['search'];
				$queryData['like']['leave_master.reason'] = $data['search'];
			endif;
			
						if(!empty($data['limit'])): 
				$queryData['limit'] = $data['limit']; 
				$queryData['order_by']['leave_master.created_at'] = "DESC"; 
			endif;

			if(isset($data['start']) && isset($data['length'])):
				$queryData['start'] = $data['start'];
				$queryData['length'] = $data['length'];
			endif;

			if(!empty($data['result_type'])):
				$result = $this->getData($queryData,$data['result_type']);
			elseif(!empty($data['id'])):
				$result = $this->getData($queryData,'row');
			else:
				$result = $this->getData($queryData,'rows');
			endif;

			return $result;
		}
			
			
        public function saveLeave($data){
            try{
                $this->db->trans_begin();

				$result = $this->store($this->leaveMaster,$data,'Leave');

				if ($this->db->trans_status() !== FALSE):
					$this->db->trans_commit();
					return $result;
				endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }	
        }

		public function deleteLeave($data){
			try{
				$this->db->trans_begin();
				
				$result = $this->trash($this->leaveMaster,['id'=>$data['id']],'Leave');
				
				if ($this->db->trans_status() !== FALSE):
					$this->db->trans_commit();
					return $result;
				endif;
			}catch(\Throwable $e){
				$this->db->trans_rollback();
				return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
			}			
		}
	
		public function leaveStatus($data){
            try{
				$data['approve_by'] = $this->loginId; $data['approve_at'] = date('Y-m-d H:i:s');
				$result = $this->store($this->leaveMaster,$data,'Leave');
				
				if ($this->db->trans_status() !== FALSE):
					$this->db->trans_commit();
					return $result;
				endif;
			}catch(\Throwable $e){
				$this->db->trans_rollback();
				return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
			}	
        }
    /********** End Leave**********/
}
?>