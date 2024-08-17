<?php
class EmployeeModel extends MasterModel{
    private $empMaster = "employee_master";
    private $attendance_log = "attendance_log";
    private $leaveMaster = "leave_master";
    private $taskMaster = "task_master";
    private $task_log = "task_log";

    /********** Employee **********/
		public function getEmployeeDetails($data){
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

			if(!empty($data['limit'])): 
				$queryData['limit'] = $data['limit']; 
				$queryData['order_by']['user_master.created_at'] = "DESC"; 
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
        public function getLeaveDTRows($data){
            $data['tableName'] = $this->leaveMaster;
            $data['select'] = "leave_master.id,employee_master.emp_name,select_master.label,leave_master.leave_date,leave_master.remark,leave_master.approve_by";
            $data['leftJoin']['employee_master'] = "employee_master.id = leave_master.emp_id";
            $data['leftJoin']['select_master'] = "select_master.id = leave_master.leave_type_id";

            if($data['login_emp_id'] != 1):
                $data['where']['leave_master.emp_id'] = $data['login_emp_id'];
            endif;

            if($data['status'] == 2){
                $data['where']['leave_master.approve_by >'] = 0;
            }else{
                $data['where']['leave_master.approve_by'] = 0;
            }

            if(!in_array($this->userRole,[1,-1])):
                $data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
            endif;
            
            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "employee_master.emp_name";
            $data['searchCol'][] = "leave_master.leave_date";
            $data['searchCol'][] = "select_master.label";
            $data['searchCol'][] = "leave_master.remark";

            $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

            if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
            $result = $this->pagingRows($data);
            return $result;
        }

        public function checkDuplicateLeave($leave_date,$emp_id,$id=""){
            $month = date('m',strtotime($leave_date));
            $year = date('Y',strtotime($leave_date));
            $data['tableName'] = $this->leaveMaster;
            $data['where']['leave_date'] = $leave_date;
            $data['where']['emp_id'] = $emp_id;
            $data['where']['MONTH(leave_master.leave_date)'] = $month;
            $data['where']['YEAR(leave_master.leave_date)'] = $year ;
            if(!empty($id))
                $data['where']['id !='] = $id;
            return $this->getData($data,"numRows");
        }

        public function getLeave($data){
            $queryData['tableName'] = $this->leaveMaster;
            $queryData['where']['id'] = $data['id'];
            return $this->getData($queryData,"row");
        }

        public function saveLeave($data){
            try{
                $this->db->trans_begin();

                if($this->checkDuplicateLeave($data['leave_date'],$data['emp_id'],$data['id']) > 0):
                    $errorMessage['leave_date'] = "Leave date is duplicate.";
                    return ['status'=>0,'message'=>$errorMessage];
                else:
                    $result = $this->store($this->leaveMaster,$data,'Leave');

                    if ($this->db->trans_status() !== FALSE):
                        $this->db->trans_commit();
                        return $result;
                    endif;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }	
        }

        public function approveLeave($data){
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
    /********** End Leave**********/

    /********** Task Manager **********/
        public function getTask($data){
            $queryData['tableName'] = $this->taskMaster;
            $queryData['select'] = "task_master.*,employee_master.emp_name,GROUP_CONCAT(task_stages.stage_type) as task_stages_label";
            $queryData['leftJoin']['employee_master'] = "employee_master.id = task_master.assign_to";
            $queryData['leftJoin']['task_stages'] = "FIND_IN_SET (task_stages.id,task_master.task_stage) > 0";
            $queryData['where']['task_master.id'] = $data['id'];
            $queryData['group_by'][] = "task_master.id"; 
            return $this->getData($queryData,"row");
        }

        public function saveTask($data){
            try{
                $this->db->trans_begin();

                $result = $this->store($this->taskMaster, $data, 'Task');

                if($result['status'] == 1){
                    $taskId = (!empty($data['id']) ? $data['id'] : $result['insert_id']);

                    if(empty($data['id'])){
                        $logData = [
                            'id' => '',
                            'task_id' => $taskId,
                            'log_type' => 1,
                            'narration' => $this->taskLogTitle[1],
                            'created_by' => $data['created_by'],
                            'created_at' => $data['created_at']
                        ];
                        $this->saveTaskLogs($logData);	
                    }
                }

                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }	
        }

        public function saveTaskLogs($data){
            try{
                $this->db->trans_begin();

                $result = $this->store($this->task_log,$data);

                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }
        }
        
        public function getTaskList($param=array()){    
            $queryData['tableName'] = $this->taskMaster;
            $queryData['select'] = "task_master.*,taskLog.log_type";

            $queryData['leftJoin']['(SELECT log_type,task_id FROM task_log WHERE is_delete = 0 GROUP BY task_id) as taskLog'] = "taskLog.task_id = task_master.id";
            // $queryData['leftJoin']['task_log'] = "task_log.task_id = task_master.id";

            if(!empty($param['customWhere'])){
                $queryData['customWhere'][] = $param['customWhere']; 
            }

            if(!empty($param['task_stage'])):
                $queryData['where']['task_stage'] = $param['task_stage']; 
            endif;

            if(!empty($param['status'])):
                $queryData['where_in']['status'] = $param['status']; 
            endif;

            if(!empty($param['limit'])):
                $queryData['limit'] = $param['limit']; 
            endif;

            if(isset($param['start'])):
                $queryData['start'] = $param['start'];
            endif;

            if(!empty($param['length'])):
                $queryData['length'] = $param['length'];
            endif;

            // $queryData['group_by'][] = "task_master.id";            
            $queryData['order_by']['taskLog.log_type'] = "DESC";
            $queryData['order_by']['task_master.created_at'] = "DESC";

            return $this->getData($queryData,"rows");
        }

        public function deleteTask($id){
            try {
                $this->db->trans_begin();
        
                $result = $this->trash($this->taskMaster, ['id' => $id], 'Task');
                $this->trash($this->task_log,['task_id'=>$id]);

                if ($this->db->trans_status() !== FALSE) :
                    $this->db->trans_commit();
                    return $result;
                endif;
            } catch (\Exception $e) {
                $this->db->trans_rollback();
                return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
            }
        }

        public function changeTaskStatus($data){
            try {
                $this->db->trans_begin();

                if(!empty($data['status'])):
                    $result = $this->store($this->taskMaster,['id'=>$data['id'],'status'=>$data['status']]); 
                endif;

                $notes='';
                if(!empty($data['log_type']) && in_array($data['log_type'], [1,2,3])){
                    $notes = $this->taskLogTitle[$data['log_type']];
                }
                elseif(!empty($data['notes'])){
                    $notes = $data['notes'];
                }
                $logData = [
                    'id' => '',
                    'log_type' => $data['log_type'],
                    'task_stage' => !empty($data['task_stage']) ? $data['task_stage'] : 0,
                    'task_id' => $data['id'],
                    'narration' => $notes,
                    'created_by' => $this->loginId,
                    'created_at' => date("Y-m-d H:i:s")
                ];
                $this->saveTaskLogs($logData);

                if ($this->db->trans_status() !== FALSE) :
                    $this->db->trans_commit();
                    return ['status' => 1, 'message' => "Task Status Updated Successfully."];
                endif;
            } catch (\Exception $e) {
                $this->db->trans_rollback();
                return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
            }
        }

        public function saveProgress($data){
            try{
                $this->db->trans_begin();

                $result = $this->store($this->taskMaster,$data);

                $taskId = (!empty($data['id']) ? $data['id'] : $result['insert_id']);

                $logData = [
                    'id' => '',
                    'task_id' => $taskId,
                    'log_type' => 4,
                    'narration' => $this->taskLogTitle[4].' : '.$data['task_progress'].' %',
                    'created_by' => $this->loginId,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $this->saveTaskLogs($logData);	

                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }
        }
    /********** End Task Manager **********/
}
?>