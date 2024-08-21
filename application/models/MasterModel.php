<?php /* Master Modal Ver. : 2  */
class MasterModel extends CI_Model{
	/*** Mapping Conditions ***/
	public function mapConditions($data){
		if(isset($data['select'])):
            if(!empty($data['select'])):
                $this->db->select($data['select']);
            endif;
        endif;

        if(isset($data['join'])):
            if(!empty($data['join'])):
                foreach($data['join'] as $key=>$value):
                    $this->db->join($key,$value);
                endforeach;
            endif;
        endif;

        if(isset($data['leftJoin'])):
            if(!empty($data['leftJoin'])):
                foreach($data['leftJoin'] as $key=>$value):
                    $this->db->join($key,$value,'left',false);
                endforeach;
            endif;
        endif;

        if(isset($data['where'])):
            if(!empty($data['where'])):
                foreach($data['where'] as $key=>$value):
                    $this->db->where($key,$value);
                endforeach;
            endif;            
        endif;

        if(isset($data['where_or'])):
            if(!empty($data['where_or'])):
                $i=1;
                $this->db->group_start();
                foreach($data['where_or'] as $key=>$value):
                    if($i == 1):
                        $this->db->where($key,$value);
                    else:
                        $this->db->or_where($key,$value);
                    endif;
                    $i++;
                endforeach;
                $this->db->group_end();
            endif;
        endif;
        
        if(isset($data['whereFalse'])):
            if(!empty($data['whereFalse'])):
                foreach($data['whereFalse'] as $key=>$value):
                    $this->db->where($key,$value,false); 
                endforeach;
            endif;            
        endif;
        
        if(isset($data['customWhere'])):
            if(!empty($data['customWhere'])):
                foreach($data['customWhere'] as $value):
                    $this->db->where($value);
                endforeach;
            endif;
        endif;

        if(isset($data['where_in'])):
            if(!empty($data['where_in'])):
                foreach($data['where_in'] as $key=>$value):
                    $this->db->where_in($key,$value,false);
                endforeach;
            endif;
        endif;

        if(isset($data['where_not_in'])):
            if(!empty($data['where_not_in'])):
                foreach($data['where_not_in'] as $key=>$value):
                    $this->db->where_not_in($key,$value,false);
                endforeach;
            endif;
        endif;

        if (isset($data['having'])) :
			if (!empty($data['having'])) :
				foreach ($data['having'] as $value) :
					$this->db->having($value);
				endforeach;
			endif;
		endif;

        if(isset($data['like'])):
            if(!empty($data['like'])):
                $i=1;
                $this->db->group_start();
                foreach($data['like'] as $key=>$value):
                    if($i == 1):
                        $this->db->like($key,$value,'both',false);
                    else:
                        $this->db->or_like($key,$value,'both',false);
                    endif;
                    $i++;
                endforeach;
                $this->db->group_end();
            endif;
        endif;

        if(isset($data['columnSearch'])):
            if(!empty($data['columnSearch'])):                
                $this->db->group_start();
                foreach($data['columnSearch'] as $key=>$value):
                    $this->db->like($key,$value);
                endforeach;
                $this->db->group_end();
            endif;
        endif;

        if(isset($data['order_by'])):
            if(!empty($data['order_by'])):
                foreach($data['order_by'] as $key=>$value):
                    $this->db->order_by($key,$value);
                endforeach;
            endif;
        endif;

        if(isset($data['order_by_field'])):
            if(!empty($data['order_by_field'])):
                foreach($data['order_by_field'] as $key=>$value):
                    $this->db->order_by("FIELD(".$key.", ".implode(",",$value).")", '', false);
                endforeach;
            endif;
        endif;

        if(isset($data['group_by'])):
            if(!empty($data['group_by'])):
                foreach($data['group_by'] as $key=>$value):
                    $this->db->group_by($value);
                endforeach;
            endif;
        endif;

		if(isset($data['limit'])):
            if(!empty($data['limit'])):
                $this->db->limit($data['limit']);
            endif;
        endif;

        if(isset($data['start']) && isset($data['length'])):
            if(!empty($data['length'])):
                $this->db->limit($data['length'],$data['start']);
            endif;
        endif;
	}

    /* Get All Rows */
	public function getData($param,$selectType = "rows"){
		
		$this->mapConditions($param);
		
		if(isset($param['all'])):
            if(!empty($param['all'])):
                foreach($param['all'] as $key=>$value):
                    $this->db->where_in($key,$value,false);
                endforeach;
            endif;
        else:
            $this->db->where($param['tableName'].'.is_delete',0);
        endif;
        
		
		if($selectType == "rows"):
			return $this->db->get($param['tableName'])->result();
		endif;
		
		if($selectType == "row"):
			return $this->db->get($param['tableName'])->row();
		endif;
		
		if($selectType == "numRows"):
			return $this->db->get($param['tableName'])->num_rows();
		endif;
		
        //print_r($this->db->last_query());
        return $result;
	}
	
    /* Save and Update Row */
    public function store($tableName,$data,$msg = "Record"){
        $checkDupli = 0;
        if(isset($data['checkDuplicate']) AND !empty($data['checkDuplicate'])):
            $dArr = Array();$firstKey = '';
            $dArr['tableName'] = $tableName;
            if(isset($data['checkDuplicate']['customWhere'])):
                $firstKey =$data['checkDuplicate']['first_key'];
                if(!empty($data['checkDuplicate']['customWhere'])):
                    $dArr['customWhere'][] = $data['checkDuplicate']['customWhere'];
                endif;
            else:
                foreach($data['checkDuplicate'] as $key):
                    if(empty($firstKey)){$firstKey = $key;}
                    $dArr['where'][$tableName.'.'.$key] = $data[$key];
                endforeach;
            endif;
            if(!empty($data['id'])): $dArr['where']['id !='] = $data['id']; endif;
            if(!empty($data['cm_id'])):
                $this->db->where_in($dArr['tableName'].'.cm_id',[$data['cm_id'],0]);
            else:
                if(!empty($this->CMID)): $this->db->where_in($dArr['tableName'].'.cm_id',[$this->CMID,0]); endif;
            endif;
            $checkDupli = $this->getData($dArr,'numRows');
            unset($data['checkDuplicate']);
        endif;

        if($checkDupli > 0):
            return ['status'=>0,'message'=>[$firstKey => $msg." is Duplicate."]];
            exit;
        endif;

        $id = $data['id'];
        unset($data['id']);
        if(empty($id)):
            $data['created_by'] = (isset($data['created_by']))?$data['created_by']:$this->loginId;
            $data['created_at'] = date("Y-m-d H:i:s");

            $this->db->insert($tableName,$data);
            $insert_id = $this->db->insert_id();
            $result = ['status'=>1,'message'=>$msg." saved Successfully.",'insert_id'=>$insert_id,'id'=>$insert_id];
        else:
            unset($data['created_by']);
            $data['updated_by'] = $this->loginId;
            $data['updated_at'] = date("Y-m-d H:i:s");
            
            $this->db->where('id',$id);
            $this->db->update($tableName,$data);
            $result = ['status'=>1,'message'=>$msg." updated Successfully.",'insert_id'=>-1,'id'=>$id];
        endif;

        return $result;
    }

    /* Update Row */
    public function edit($tableName,$where,$data,$msg = "Record"){
        $data['updated_by'] = $this->loginId;
        $data['updated_at'] = date("Y-m-d H:i:s");

        if(!empty($where)):
            foreach($where as $key=>$value):
                $this->db->where($key,$value);
            endforeach;
        endif;
        $this->db->update($tableName,$data);
        return ['status'=>1,'message'=>$msg." updated Successfully.",'insert_id'=>-1];
    }

    /* Update Row */
    public function editCustom($tableName,$customWhere,$data,$where=Array()){
        $data['updated_by'] = $this->loginId;
        $data['updated_at'] = date("Y-m-d H:i:s");

        if(!empty($where)):
            foreach($where as $key=>$value):
                $this->db->where($key,$value);
            endforeach;
        endif;

		if(isset($customWhere)):
            if(!empty($customWhere)):
                foreach($customWhere as $value):
                    $this->db->where($value);
                endforeach;
            endif;
        endif;
        $this->db->update($tableName,$data);
        return ['status'=>1,'message'=>"Record updated Successfully.",'insert_id'=>-1];
    }

    /* Set Deleted Flage */
    public function trash($tableName,$where,$msg = "Record"){
        $data['updated_by'] = $this->loginId;
        $data['updated_at'] = date("Y-m-d H:i:s");
        $data['is_delete'] = 1;

        if(!empty($where)):
            foreach($where as $key=>$value):
                $this->db->where($key,$value);
            endforeach;
        endif;
        $this->db->update($tableName,$data);
        return ['status'=>1,'message'=>$msg." deleted Successfully."];
    }

    /* Delete Recored Permanent */
    public function remove($tableName,$where,$msg = ""){
        if(!empty($where)):
            foreach($where as $key=>$value):
                $this->db->where($key,$value);
            endforeach;
        endif;
        $this->db->delete($tableName);
        return ['status'=>1,'message'=>$msg." deleted Successfully."];
    }  
    
    /* Custom Set OR Update Row */
    public function setValue($data){
		if(!empty($data['where']) || !empty($data['where_in']) || !empty($data['where_not_in'])):
			if(isset($data['where'])):
				if(!empty($data['where'])):
					foreach($data['where'] as $key=>$value):
						$this->db->where($key,$value);
					endforeach;
				endif;            
			endif;

            if(isset($data['where_in'])):
                if(!empty($data['where_in'])):
                    foreach($data['where_in'] as $key=>$value):
                        $this->db->where_in($key,$value,false);
                    endforeach;
                endif;
            endif;

            if(isset($data['where_not_in'])):
                if(!empty($data['where_not_in'])):
                    foreach($data['where_not_in'] as $key=>$value):
                        $this->db->where_not_in($key,$value,false);
                    endforeach;
                endif;
            endif;

            if(isset($data['order_by'])):
                if(!empty($data['order_by'])):
                    foreach($data['order_by'] as $key=>$value):
                        $this->db->order_by($key,$value);
                    endforeach;
                endif;
            endif;
			
			if(isset($data['set'])):
				if(!empty($data['set'])):
					foreach($data['set'] as $key=>$value):
						$v = explode(',',$value);
						$setVal = "`".$v[0]."` ".$v[1];
						$this->db->set($key, $setVal, FALSE);
					endforeach;
				endif;            
			endif;

            if(isset($data['set_value'])):
				if(!empty($data['set_value'])):
					foreach($data['set_value'] as $key=>$value):
						$this->db->set($key, $value, FALSE);
					endforeach;
				endif;            
			endif;

            if(isset($data['update'])):
				if(!empty($data['update'])):
					foreach($data['update'] as $key=>$value):
						$this->db->set($key, $value, FALSE);
					endforeach;
				endif;            
			endif;
            
            $this->db->update($data['tableName']);
            return ['status'=>1,'message'=>"Record updated Successfully.",'qry'=>$this->db->last_query()];
        endif;
		return ['status'=>0,'message'=>"Record updated Successfully.",'qry'=>"Query not fired"];
    }

	/* Print Executed Query */
    public function printQuery(){ print_r($this->db->last_query());exit; }	

    /* Company List */
    public function getCompanyList($cm_ids=array()){
        $data['tableName'] = 'company_info';
        $data['select'] = "company_info.id,company_info.company_code,company_info.company_name,company_info.company_gst_no,company_info.company_pincode,bstate.gst_statecode as company_state_code";

        $data['leftJoin']['states as bstate'] = "company_info.company_state_id = bstate.id";

        $data['where_in']['company_info.id'] = (!empty($cm_ids))?$cm_ids:$this->cm_ids;

        return $this->rows($data);
    }

	/* Company Information */
	public function getCompanyInfo($id = 1){
		$data['tableName'] = 'company_info';
        $data['select'] = "company_info.*,address_master.country, address_master.state, address_master.state_code, address_master.district, address_master.city";

        $data['leftJoin']['address_master'] = "company_info.address_id = address_master.id";

		$data['where']['company_info.id'] = $id;
		return $this->getData($data,'row');
	}

    /* Save Comapny Information */
    public function saveCompanyInfo($postData){
        try{
            $this->db->trans_begin();

            $result = $this->store('company_info',$postData,'Company Info');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    /* Accounting Settings */
    public function getAccountSettings($id = 1){
        $data['tableName'] = 'account_setting';
        $data['where']['account_setting.id'] = $id;
		return $this->row($data);
    }

    /* Save Comapny Settings */
    public function saveSettings($postData){
        try{
            $this->db->trans_begin();

            $result = $this->store('account_setting',$postData['account_setting'],'Settings');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    /* 
    *   Created BY : Milan Chauhan
    *   Created AT : 05-05-2023
    *   Required Param : columnName (array)
    *   Not : if check any other condition on particular table and column then post data like this $data['table_condition']['{TABLE NAME}']['{CONDITION TYPE}']['{COLUMN NAME}'] = '{COLUMN VALUE}';
    *       CONDITION TYPE includs where,where_in and where_not_in
    */
    public function checkUsage($postData){
        if(!empty($postData['columnName'])):
            $columnName = implode("','",$postData['columnName']);
            $result = $this->db->query("SELECT DISTINCT TABLE_NAME,COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME IN ('$columnName') AND TABLE_SCHEMA='".MASTER_DB."'")->result();
            //print_r($result);exit;
            $res = 0;
            foreach($result as $row):
                $queryData = array();
                $queryData['tableName'] = $row->TABLE_NAME;

                if(empty($postData['notCheckCol'])):
                    $queryData['where'][$row->COLUMN_NAME] = $postData['value'];
                else:
                    if(!in_array($row->COLUMN_NAME,$postData['notCheckCol'])):
                        $queryData['where'][$row->COLUMN_NAME] = $postData['value'];
                    endif;
                endif;

                if(isset($postData['table_condition']) && !empty($postData['table_condition'])):
                    if(array_key_exists($row->TABLE_NAME, $postData['table_condition'])):

                        if(!empty($postData['table_condition'][$row->TABLE_NAME]['where']) && array_key_exists($row->COLUMN_NAME, $data['table_condition'][$row->TABLE_NAME]['where'])):
                            foreach($postData['table_condition'][$row->TABLE_NAME]['where'][$row->COLUMN_NAME] as $key=>$value):
                                $queryData['where'][$key] = $value;
                            endforeach;
                        endif;

                        if(!empty($postData['table_condition'][$row->TABLE_NAME]['where_in']) && array_key_exists($row->COLUMN_NAME, $postData['table_condition'][$row->TABLE_NAME]['where_in'])):
                            foreach($postData['table_condition'][$row->TABLE_NAME]['where_in'][$row->COLUMN_NAME] as $key=>$value):
                                $queryData['where_in'][$key] = $value;
                            endforeach;
                        endif;

                        if(!empty($postData['table_condition'][$row->TABLE_NAME]['where_not_in']) && array_key_exists($row->COLUMN_NAME, $postData['table_condition'][$row->TABLE_NAME]['where_not_in'])):
                            foreach($postData['table_condition'][$row->TABLE_NAME]['where_not_in'][$row->COLUMN_NAME] as $key=>$value):
                                $queryData['where_not_in'][$key] = $value;
                            endforeach;
                        endif;

                        if(!empty($postData['table_condition'][$row->TABLE_NAME]['customWhere']) && array_key_exists($row->COLUMN_NAME, $postData['table_condition'][$row->TABLE_NAME]['customWhere'])):
                            foreach($postData['table_condition'][$row->TABLE_NAME]['customWhere'][$row->COLUMN_NAME] as $key=>$value):
                                $queryData['customWhere'][] = $value;
                            endforeach;
                        endif;
                    endif;
                endif;

                //$queryData['resultType'] = "numRows";
                //$res = $this->specificRow($queryData);
                $res = $this->getData($queryData,"numRows");

                if($res > 0): /* print_r($row->TABLE_NAME); */ break; endif;
            endforeach;
            //print_r($res);exit;
            if($res > 0): return true; endif;
        endif;
        return false;
    }

    /* 
    * Created BY : Milan Chauhan
    * Created AT : 27-10-2023
    * Required Param : tableName => columnName (array) and id any special conditions
    */
    public function checkEntryReference($postData){
        $queryData = array();
        $queryData['tableName'] = $postData['table_name'];
        $queryData['select'] = "COUNT(id) as count,GROUP_CONCAT(DISTINCT(vou_name_l) SEPARATOR ', ') as entry_ref";

        foreach($postData["where"] as $row):
            $queryData['where'][$row['column_name']] = $row['column_value'];
        endforeach;

        foreach($postData["find"] as $row):
            $queryData['customWhere'][] = "FIND_IN_SET(".$row['column_value'].",".$row['column_name'].") > 0";
        endforeach;

        $result = $this->row($queryData);

        if(!empty($result->count)):
            return ['status'=>0,'message' => 'Entry Ref. Found. You can not delete it. Vou Name : '.$result->entry_ref];
        endif;

        return ['status'=>1,'message' => 'Entry Ref. not found.'];
    }

    public function notify($data){
        $result = array();
        if(!empty($data['controller'])): // if modual permission then get fcm token from database and send notification
            $this->db->select("emp.web_push_token,emp.app_push_token");

            $this->db->join("sub_menu_master as sm","sm.id = smp.sub_menu_id AND sm.is_delete = 0","left");
            $this->db->join("employee_master as emp","emp.id = smp.emp_id AND emp.is_active = 1 AND emp.is_delete = 0","left");

            $this->db->where_in("sm.sub_controller_name",$data['controller']);
            $this->db->where('smp.is_read',1);

            $this->db->group_start();
                $this->db->where('emp.web_push_token !=',"");
                $this->db->or_where('emp.app_push_token !=',"");
            $this->db->group_end();

            $this->db->where('emp.id != ',$this->loginId);
            $this->db->where('smp.is_delete',0);
            $result = $this->db->get('sub_menu_permission as smp')->result();
        elseif(!empty($data['emp_ids'])): // send notification to any specific user's (user id array)
            $this->db->select("web_push_token,app_push_token");
        
            $this->db->group_start();
                $this->db->where('web_push_token !=',"");
                $this->db->or_where('app_push_token !=',"");
            $this->db->group_end();
            
            //$this->db->where('id != ',$this->loginId);
            $this->db->where_in('id',$data['emp_ids']);
            $this->db->where('is_delete',0);
            $this->db->where('is_active',1);
            $result = $this->db->get('employee_master')->result();
        endif;
        
        $token = array();
        foreach($result as $row):
            if(!empty($row->web_push_token)):
                $token[] = $row->web_push_token;
            endif;
            
            if(!empty($row->app_push_token)):
                $token[] = $row->app_push_token;
            endif;
        endforeach;

        $result = array();
        if(!empty($token)):
            $data['pushToken'] = $token;
            $result = $this->notification->sendMultipalNotification($data);
        endif;

        $logData = [
            'log_date' => date("Y-m-d H:i:s"),
            'notification_data' => json_encode($data),
            'notification_response' => json_encode($result),
            'created_by' => (isset($this->loginId))?$this->loginId:0,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_by' => (isset($this->loginId))?$this->loginId:0,
            'updated_at' => date("Y-m-d H:i:s")
        ];
        $this->db->insert('notification_log',$logData);

        return $result;
    }

    /* Save Location Log in */
    public function saveLocationLog($postData){
        try{
            $this->db->trans_begin();
            $postData['id'] = '';
            $postData['created_by'] = $this->loginId;
            $postData['created_at'] = date("Y-m-d H:i:s");
            
            $result = $this->store('location_log',$postData,'Location Log');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

}
?>