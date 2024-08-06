<?php
class ConfigurationModel extends MasterModel{
    private $lead_stages = "lead_stages";
	private $business_type = "business_type";
	private $terms = "terms";
	private $select_master = "select_master";

    /********** Lead Stages **********/
        public function getLeadStagesList($data=[]){
            $queryData['tableName'] = $this->lead_stages;
            if(!empty($data['stage_type'])) { $queryData['where']['stage_type'] = $data['stage_type']; }
            if(!empty($data['not_in'])) { $queryData['where_not_in']['id'] = $data['not_in']; }
            $queryData['order_by']['sequence'] ='ASC';
            return $this->getData($queryData,"rows");
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
			
            if(!empty($data['single_row'])):
                return $this->getData($queryData,"row");
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
			
			if(!empty($data['single_row'])):
                return $this->getData($queryData,"row");
            else:
                return $this->getData($queryData,"rows");
            endif;
        }
	/********** End Select Option **********/
}
?>