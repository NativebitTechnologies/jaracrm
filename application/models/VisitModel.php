<?php
class VisitModel extends MasterModel{
    private $visits = "visits";
    
	public function getVisit($id){
        $data['tableName'] = $this->visits;
        $data['select'] = "visits.*,(CASE WHEN visits.party_id > 0 THEN party_master.party_name ELSE lead_master.party_name END) as party_name,lead_master.party_type";
        $data['leftJoin']['party_master'] = "party_master.id = visits.party_id";
        $data['leftJoin']['lead_master'] = "lead_master.id = visits.lead_id";
        $data['where']['visits.id'] = $id;
        return $this->row($data);
    }
    
	public function save($data){
        try{
            $this->db->trans_begin();
            
            unset($data['party_type'],$data['business_type']);
            $result = $this->store($this->visits,$data,'Visit');
            
            // Insert Location Log
            $locLog = Array();
            $locLog['log_type'] = 3;
            $locLog['emp_id'] = $this->loginId;
            $locLog['party_id'] = (!empty($data['party_id']) ? $data['party_id'] : 0);
            $locLog['lead_id'] = (!empty($data['lead_id']) ? $data['lead_id'] : 0);
            $locLog['log_time'] = $data['start_at'];
            $locLog['location'] = $data['start_location'];
            $locLog['address'] = $data['s_add'];
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

    public function saveEndVisit($data){
        try{
            $this->db->trans_begin();

            $party_type = $data['party_type'];
            $next_visit = $data['next_visit'];
            $reminder_date = $data['reminder_date']; $reminder_time = $data['reminder_time']; $reminder_note = $data['reminder_note'];
            unset($data['party_type'],$data['reminder_date'],$data['reminder_time'],$data['reminder_note'],$data['next_visit']);
            $this->store($this->visits,$data);
            
            // Insert Location Log
            $vLog = $this->getVisit($data['id']);
            $locLog = Array();
            $locLog['log_type'] = 4;
            $locLog['emp_id'] = $this->loginId;
            $locLog['party_id'] = (!empty($vLog->party_id) ? $vLog->party_id : 0);
            $locLog['lead_id'] = (!empty($vLog->lead_id) ? $vLog->lead_id : 0);
            $locLog['log_time'] = $data['end_at'];
            $locLog['location'] = $data['end_location'];
            $locLog['address'] = $data['e_add'];
            $llResult = $this->saveLocationLog($locLog);

            /** Sales Log Entry */
            $logData = [
                'id' => '',
                'log_type' => 26,
                'party_id' => (!empty($vLog->party_id) ? $vLog->party_id : 0),
                'lead_id' => (!empty($vLog->lead_id) ? $vLog->lead_id : 0),
                'ref_id' => $data['id'],
                'ref_date' => date("Y-m-d"),
                'ref_no' =>'',
                'executive_id' => $this->loginId,
                'notes' => 'Purpose : '.$vLog->purpose,
                'remark' => 'Discussion : '.$vLog->discussion_points.'<br>Contact Person : '.$vLog->contact_person,
                'created_by' => $this->loginId,
                'created_at' => date("Y-m-d H:i:s")
            ];            
            $this->sales->saveSalesLogs($logData);

            /*** If Lead status Changed  */
            if(!empty($party_type) && !empty($vLog->lead_id) && $vLog->party_type != $party_type){
                $stage = $this->configuration->getLeadStage(['id'=>$party_type]);
                $stageData = [
                    'id'=>$vLog->lead_id,
                    'party_type'=>$party_type,
                    'log_type'=>$stage->log_type,
                    'ref_date' => date("Y-m-d"),
                    'notes' => $stage->stage_type,
                    'executive_id' => $this->loginId,
                    'created_by' => $this->loginId,
                    'is_active' => 1,
                    'remark' => '',
                    'created_at' => date("Y-m-d H:i:s")
                ];
                $this->party->changeLeadStatus($stageData);
            }

            /*** If Next Reminder Set */
            if($next_visit == 'Yes'){
                $logData = [
                    'id' => '',
                    'log_type' => 3,
                    'party_id' => (!empty($vLog->party_id) ? $vLog->party_id : 0),
                    'lead_id' => (!empty($vLog->lead_id) ? $vLog->lead_id : 0),
                    'ref_id' => $data['id'],
                    'ref_date' =>$reminder_date,
                    'reminder_time' =>$reminder_time,
                    'notes' =>$reminder_note,
                    'mode' => 'Visit',
                    'executive_id' => $this->loginId,
                    'created_by' => $this->loginId,
                    'created_at' => date("Y-m-d H:i:s")
                ];
                $this->sales->saveSalesLogs($logData);
            }
            
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return ['status'=>1,'message'=>"Visit Ended Successfully."];
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }
    
	public function delete($id){
        try{
            $this->db->trans_begin();

            $result = $this->trash($this->visits,['id'=>$id],'Visit');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function getVisitList($param = Array()){
        $data['tableName'] = $this->visits;
        $data['select'] = "visits.*, party_master.party_name,party_master.lead_stage";
        $data['leftJoin']['party_master'] = "party_master.id = visits.party_id";

        $data['where']['visits.created_by'] = $this->loginId;
        if(!empty($param['from_date']) && !empty($param['to_date'])):
            $data['where']['visits.start_at >= '] = $param['from_date'];
            $data['where']['visits.start_at <= '] = $param['to_date'];
		endif;
        if(!empty($param['visit_status']) && $param['visit_status'] == 1){
            $data['where']['visits.end_at'] = null;
        }
       
		if(!empty($param['sales_executive'])){$data['where']['visits.created_by'] = $param['sales_executive'];}else{
            if(!in_array($this->userRole,[-1,1])){$data['where']['visits.created_by'] = $this->loginId;}
        }
        
		if(!empty($param['visit_status']) && $param['visit_status'] == 2){
            $data['customWhere'][] = 'visits.end_at IS NOT NULL';
            $data['order_by']['visits.end_at'] = 'DESC';
        }

        if(!empty($param['search'])):
            $data['like']['visits.purpose'] = $param['search'];
            $data['like']['party_master.party_code'] = $param['search'];
            $data['like']['party_master.party_name'] = $param['search'];
            $data['like']['party_master.contact_person'] = $param['search'];
        endif;

        if(!empty($param['limit'])): 
            $data['limit'] = $data['limit']; 
            $data['order_by']['party_master.created_at'] = "DESC"; 
        endif;

        if(isset($param['start']) && isset($param['length'])):
            $data['start'] = $param['start'];
            $data['length'] = $param['length'];
        endif;
        if(!empty($param['single_row'])){
            $result =  $this->getData($data,"row");
        }else{
            $result =  $this->getData($data,"rows");
        }
        
        return $result;
    }

    public function getVisitHistory($data = Array()){
        $queryData = array();
		$queryData['tableName'] = $this->visits;
        $queryData['select'] = "visits.id,DATE_FORMAT(visits.start_at, '%d-%m-%Y') as date, DATE_FORMAT(visits.start_at, '%H:%i:%s') as time, visits.start_location as location, party_master.party_name,party_master.party_address as address";
		$queryData['leftJoin']['party_master'] = "party_master.id = visits.party_id";
        $queryData['customWhere'][] = "start_at BETWEEN '".date('Y-m-d H:i:s',strtotime($data['from_date'].' 00:00:00'))."' AND '".date('Y-m-d H:i:s',strtotime($data['to_date'].' 23:59:59'))."'";
		if(!empty($data['party_id'])){$queryData['where']['party_master.id'] = $data['party_id'];}
		//if(!empty($data['sales_executive'])){$queryData['where']['party_master.executive_id'] = $data['sales_executive'];}
		if(!in_array($this->userRole,[-1,1])){$data['where']['visits.created_by'] = $this->loginId;}
		if(!empty($data['sales_executive'])){$queryData['where']['visits.created_by'] = $data['sales_executive'];}
		$queryData['order_by']['visits.start_at'] = 'DESC';
		$result = $this->rows($queryData);
		//$this->printQuery();
		return $result;
    }
	
	//-----------  API Function Start -----------//
		
    public function getVisitList_api($limit, $start,$emp_id=0){
        $data['tableName'] = $this->visits;
        $data['select'] = "visits.*,party_master.party_name";
        $data['leftJoin']['party_master'] = "party_master.id = visits.party_id";
        if(!empty($emp_id) && !in_array($this->userRole,[-1,1]))
            $data['where']['party_master.sales_executive'] = $emp_id;
            
        $data['order_by']['visits.id'] = "DESC";
        $data['length'] = $limit;
        $data['start'] = $start;
        return $this->rows($data);
    }
    
	public function getCount($emp_id=0){
        $data['tableName'] = $this->visits;
        $data['select'] = "visits.*,party_master.party_name";
        $data['leftJoin']['party_master'] = "party_master.id = visits.party_id";
        if(!empty($emp_id) && !in_array($this->userRole,[-1,1])) 
            $data['where']['party_master.sales_executive'] = $emp_id;
        return $this->numRows($data);
    }
    
	//----------- API Function End -----------//
}
?>