<?php
class ConfigurationModel extends MasterModel{
    private $lead_stages = "lead_stages";
	private $business_type = "business_type";
	private $terms = "terms";
	private $select_master = "select_master";

    /********** Lead Stages **********/
    public function getLeadStagesList($param=[]){
        $queryData['tableName'] = $this->lead_stages;
        
        if(!empty($param['stage_type'])) { $queryData['where']['stage_type'] = $param['stage_type']; }
        
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

            $lostStagePosition = $this->getLeadStagesList(['stage_type'=>'Lost','result_type'=>'row']);
            $param['sequence'] = (!empty($lostStagePosition) ? $lostStagePosition->sequence : 1);
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
        
        if(!empty($data['single_row'])):
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
    public function getSelectOption($data=[]){ 
        $queryData['tableName'] = $this->select_master;
        
        if(!empty($data['id'])){
            $queryData['where']['id'] = $data['id'];
        }
        
        if(!empty($data['type'])){
            $queryData['where']['type'] = $data['type'];
        }
        
        if(!empty($data['id']) || !empty($data['single_row'])):
            return $this->getData($queryData,"row");
        else:
            return $this->getData($queryData,"rows");
        endif;
    }

    public function saveSelectOption($data){
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

    public function deleteSelectOption($data){
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
}
?>