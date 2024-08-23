<?php
class ConfigurationModel extends MasterModel{
    private $lead_stages = "lead_stages";
	private $business_type = "business_type";
	private $terms = "terms";
    private $udf = "udf";
    private $udf_select = "udf_select";
	private $select_master = "select_master";

    /********** Lead Stages **********/
    public function getLeadStagesList($param=[]){
        $queryData['tableName'] = $this->lead_stages;
        
        if(!empty($param['stage_type'])) { $queryData['where']['stage_type'] = $param['stage_type']; }

        if(!empty($param['lead_stage'])) { $queryData['where']['lead_stage'] = $param['lead_stage']; }
        
        if(!empty($param['not_in'])) { $queryData['where_not_in']['id'] = $param['not_in']; }
        
        if(!empty($param['id'])) { $queryData['where']['id'] = $param['id']; }
        
        $queryData['order_by']['sequence'] ='ASC';
        
        if(!empty($param['result_type'])):
            return $this->getData($queryData,$param['result_type']);
        elseif(!empty($param['id'])):
            return $this->getData($queryData,"row");
        else:
            return $this->getData($queryData,"rows");
        endif;
    }

    public function getMaxStageSequence(){
        $queryData['tableName'] = $this->lead_stages;
        $queryData['select'] = "MAX(sequence) as next_seq_no";
        //$queryData['customWhere'][] = "sequence > 1 AND sequence < 8";
        return $this->getData($queryData,"row");
    }
    
    public function getNextStage(){
        $queryData['tableName'] = $this->lead_stages;
        $queryData['select'] = "MAX(lead_stage) as max_lead_stage";
        $queryData['where']['is_system'] = 0;
        $leadCount = $this->getData($queryData,"row");
        return (!empty($leadCount->max_lead_stage)?$leadCount->max_lead_stage+1:21);
    }

    public function saveLeadStages($param){
        try{
            $this->db->trans_begin();
            
            $param['checkDuplicate'] = ['stage_type'];    
            if(empty($param['id'])){ $param['lead_stage'] = $this->getNextStage(); }
            
            if(empty($param['id'])){
                $lostStagePosition = $this->getLeadStagesList(['stage_type'=>'Lost','result_type'=>'row']);
                $param['sequence'] = (!empty($lostStagePosition) ? $lostStagePosition->sequence : 1);
            }
            $result = $this->store($this->lead_stages, $param, 'Lead Stage');

            if(empty($param['id'])){
                $this->edit($this->lead_stages, ['stage_type'=>'Lost'], ['sequence'=>($param['sequence']+1)]);
            }
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function deleteLeadStages($id){
        try{
            $this->db->trans_begin();

            $stageData = $this->getLeadStagesList(['id'=>$id]);
            $result = $this->trash($this->lead_stages, ['id'=>$id], 'Lead Stage');

            $setData = array();
            $setData['tableName'] = $this->lead_stages;
            $setData['where']['sequence > '] = $stageData->sequence;
            $setData['set']['sequence'] = 'sequence, -1';
            $this->setValue($setData);
            
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
            
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }
	/********** End Lead Stages **********/
	
	/********** Business Type **********/
    public function getBusinessTypeList($data=[]){
        $queryData['tableName'] = $this->business_type;
        $queryData['select'] = "business_type.*,(case when business_type.parent_id = '-1' then 'N/A' else bType.type_name end) as parentType";
        $queryData['leftJoin']['business_type bType'] = "bType.id  = business_type.parent_id";

        if(!empty($data['id'])) { 
            $queryData['where']['business_type.id'] = $data['id'];
        }

        if(!empty($data['limit'])) { 
            $queryData['limit'] = $data['limit']; 
            $queryData['order_by']['business_type.created_at'] = "DESC"; 
        }

        if(isset($data['start']) && isset($data['length'])):
            $queryData['start'] = $data['start'];
            $queryData['length'] = $data['length'];
        endif;
        
        if(!empty($data['result_type'])):
            return $this->getData($queryData,$data['result_type']);
        else:
            return $this->getData($queryData,"rows");
        endif;
    }

    public function saveBusinessType($data){
        try{
            $this->db->trans_begin();
                
            $result = $this->store($this->business_type,$data,'Business Type');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function deleteBusinessType($data){
        try{
            $this->db->trans_begin();

            $result = $this->trash($this->business_type,['id'=>$data['id']]);
            
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }
	/********** End Business Type **********/
	
	/********** Terms **********/
    public function getTermsList($data){
        $queryData['tableName'] = $this->terms;
        
        if(!empty($data['id'])):
            $queryData['where']['id'] = $data['id'];
        endif;
        
        if(!empty($data['type'])):
            $queryData['where']['find_in_set("'.$data['type'].'",type) > '] = 0;
        endif;
        
        if(!empty($data['result_type'])):
            return $this->getData($queryData,$data['result_type']);
        elseif(!empty($data['id'])):
            return $this->getData($queryData,"row");
        else:
            return $this->getData($queryData,"rows");
        endif;
    }

    public function saveTerms($data){
        try{
            $this->db->trans_begin();

            $result = $this->store($this->terms,$data,'Terms');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function deleteTerms($data){
        try{
            $this->db->trans_begin();

            $result = $this->trash($this->terms,['id'=>$data['id']]);
            
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }
	/********** End Terms **********/

	/********** Select Option **********/
    public function getMasterOption($data=[]){ 
        $queryData['tableName'] = $this->select_master;
        
        if(!empty($data['id'])){
            $queryData['where']['id'] = $data['id'];
        }
        
        if(!empty($data['type'])){
            $queryData['where']['type'] = $data['type'];
        }else{ 
			$queryData['where']['type <='] = 3;
		}
        
        if(!empty($data['result_type'])):
            return $this->getData($queryData,$data['result_type']);
        elseif(!empty($data['id'])):
            return $this->getData($queryData,"row");
        else:
            return $this->getData($queryData,"rows");
        endif;
    }

    public function saveMasterOption($data){
        try{
            $this->db->trans_begin();

            $data['checkDuplicate'] = ['label','type'];  
            $result = $this->store($this->select_master,$data,'Select Option');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function deleteMasterOption($data){
        try{
            $this->db->trans_begin();

            $result = $this->trash($this->select_master,['id'=>$data['id']]);
            
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }
	/********** End Select Option **********/

	/********** Select Option **********/
	public function getCustomFieldList($postData=[]){
		$queryData['tableName'] = $this->udf;
		
		if(!empty($postData['type'])){
			$queryData['where']['type'] = $postData['type'];
		}
		
		if(!empty($postData['id'])) { 
            $queryData['where']['udf.id'] = $postData['id'];
        }

        if(!empty($postData['limit'])) { 
            $queryData['limit'] = $postData['limit']; 
            $queryData['order_by']['udf.created_at'] = "DESC"; 
        }

        if(isset($postData['start']) && isset($postData['length'])):
            $queryData['start'] = $postData['start'];
            $queryData['length'] = $postData['length'];
        endif;
        
        if(!empty($postData['result_type'])):
            return $this->getData($queryData,$postData['result_type']);
        else:
            return $this->getData($queryData,"rows");
        endif;
	}
	
	public function getNextFieldIndex($postData=[]){
		$queryData['tableName'] = $this->udf;
        $queryData['select'] = "IFNULL(MAX(field_idx + 1),1) as field_idx";
		$queryData['where']['type'] = $postData['type'];
		return $this->getData($queryData,'row')->field_idx;
	}
	
	public function saveCustomField($data){
		try{
			$this->db->trans_begin();
			
			$data['checkDuplicate'] = ['field_name'];                     
			$result = $this->store($this->udf,$data,'Field');

			if ($this->db->trans_status() !== FALSE):
				$this->db->trans_commit();
				return $result;
			endif;
		}catch(\Exception $e){
			$this->db->trans_rollback();
			return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
		}
	}
		
	public function deleteCustomField($data){
        try{
            $this->db->trans_begin();

            $result = $this->trash($this->udf,['id'=>$data['id']]);
            
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }	
	
	public function getSelectOptionList(){
		$data['tableName'] = $this->udf_select;
		if(!empty($param['type'])){
			$data['where']['type'] = $param['type'];
		}
		return $this->getData($data,"rows");
	}
	
	public function saveSelectOption($data){
		try{
			$this->db->trans_begin();
			
			$data['checkDuplicate'] = ['title','udf_id'];
			$result = $this->store($this->udf_select,$data,'Title');          

			if ($this->db->trans_status() !== FALSE):
				$this->db->trans_commit();
				return $result;
			endif;
		}catch(\Exception $e){
			$this->db->trans_rollback();
			return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
		}
	}
	
	public function deleteSelectOption($data){
		try{
            $this->db->trans_begin();

            $result = $this->trash($this->udf_select,['id'=>$data['id']]);
            
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
	}
	/********** End Select Option **********/

    /********** Start Sales Zone  ****************/
    public function getSalesZoneList($param=array()){
        $queryData['tableName'] = "sales_zone";
        $queryData['select'] = "sales_zone.*";
        
		if(!empty($param['executive_id'])){
            $queryData['join']['employee_master'] = "find_in_set(sales_zone.id,employee_master.zone_id) > 0";
            $queryData['where']['employee_master.id'] = $param['executive_id'];
        }
		if(!empty($param['id'])){
            $queryData['where']['sales_zone.id'] = $param['id'];
		}
		
		if(!empty($param['search'])):
            $queryData['like']['sales_zone.zone_name'] = $param['search'];
            $queryData['like']['sales_zone.remark'] = $param['search'];
        endif;
		
        if(!empty($param['result_type'])):
            $result = $this->getData($queryData,$param['result_type']);
        elseif(!empty($param['id'])):
            $result = $this->getData($queryData,"row");
        else:
            $result = $this->getData($queryData,"rows");
        endif;
        return $result;
    }
    
	public function saveZone($data){
		try{
			$this->db->trans_begin();
			
			$data['checkDuplicate'] = ['zone_name'];                     
			$result = $this->store('sales_zone',$data,'Sales Zone');

			if ($this->db->trans_status() !== FALSE):
				$this->db->trans_commit();
				return $result;
			endif;
		}catch(\Exception $e){
			$this->db->trans_rollback();
			return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
		}
	}
	
	public function deleteZone($data){
        try{
            $this->db->trans_begin();

			$checkData['columnName'] = ['zone_id'];
			$checkData['value'] = $data['id'];
			$checkUsed = false;

			if($checkUsed == true):
				$this->db->trans_rollback();
				return ['status'=>0,'message'=>'The Zone is currently in use. you cannot delete it.'];
			endif;
			
            $result = $this->trash('sales_zone',['id'=>$data['id']]);
            
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }	
	/********** End Sales Zone  ****************/
}
?>