<?php
class ProductModel extends MasterModel{
    private $item_master = "item_master";
	private $item_category = "item_category";
	private $unit_master = "unit_master";
	private $item_udf = "item_udf";
	
	/********** Finish Goods **********/
	public function getProductList($data=[]){
		$queryData['tableName'] = $this->item_master;
		$queryData['select'] = 'item_master.*,item_category.category_name';
		$queryData['leftJoin']['item_category'] = 'item_category.id = item_master.category_id';

		if(!empty($data['id'])) { 
			$queryData['where']['item_master.id'] = $data['id'];
		}
 
		if(!empty($data['limit'])) { 
			$queryData['limit'] = $data['limit'];
		}

		if(!empty($data['search'])):
            $queryData['like']['item_master.item_code'] = $data['search'];
            $queryData['like']['item_master.item_name'] = $data['search'];
            $queryData['like']['item_category.category_name'] = $data['search'];
            $queryData['like']['item_master.gst_per'] = $data['search'];
            $queryData['like']['item_master.price'] = $data['search'];
            $queryData['like']['item_master.mrp'] = $data['search'];
        endif;

		if(isset($data['start']) && isset($data['length'])):
			$queryData['start'] = $data['start'];
			$queryData['length'] = $data['length'];
		endif;
		
		if(!empty($data['id']) || !empty($data['single_row'])):
			return $this->getData($queryData,"row");
		else:
			return $this->getData($queryData,"rows");
		endif;
	}

	public function getUnitList(){
		$queryData['tableName'] = $this->unit_master;
		return $this->getData($queryData,"rows");
	}
	/********** Finish Goods **********/

	/********** Item Category **********/
	public function getCategoryList($data = []){
		$queryData['tableName'] = $this->item_category;
		$queryData['select'] = "item_category.*, IFNULL(parent.category_name,'NA') as parent_category"; 
		$queryData['leftJoin']['item_category parent'] = 'parent.id = item_category.ref_id'; 

		if(!empty($data['id'])){
			$queryData['where']['item_category.id'] = $data['id'];
		}
		
		if(!empty($data['ref_id'])){
			$queryData['where']['item_category.ref_id'] = $data['ref_id'];
		}

		if(!empty($data['category_type'])){
			$queryData['where']['item_category.category_type'] = $data['category_type'];
		}

		if(isset($data['final_category'])){
			$queryData['where']['item_category.final_category'] = $data['final_category'];
		}

		if(!empty($data['customWhere'])){
			$datqueryDataa['customWhere'][]=$data['customWhere'];
		}
		
		if(!empty($data['limit'])) { 
			$queryData['limit'] = $data['limit']; 
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

	public function saveProduct($data){
		try{
			$this->db->trans_begin();
			
			$customField = !empty($data['customField'])?$data['customField']:[]; unset($data['customField']);
			
			$result = $this->store($this->item_master, $data, "Item");    

			/* save custom fields */
			$itemUdfData = $this->getItemUdfData(['item_id'=>$result['id']]); 
			$customField['item_id'] =$result['id'];       
			$customField['id'] = !empty($itemUdfData->id)?$itemUdfData->id :'';
			$this->store($this->item_udf,$customField);
			
			if ($this->db->trans_status() !== FALSE):
				$this->db->trans_commit();
				return $result;
			endif;
		}catch(\Exception $e){
			$this->db->trans_rollback();
			return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
		}
	}

	public function getItemUdfData($data = []){
		$queryData['tableName'] = $this->item_udf;
		if(!empty($data['item_id'])):
			$queryData['where']['item_udf.item_id'] = $data['item_id'];
		endif;
		return $this->getData($queryData,"row");
	}
	/********** Item Category **********/
}
?>