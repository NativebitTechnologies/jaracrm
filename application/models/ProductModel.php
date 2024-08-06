<?php
class ProductModel extends MasterModel{
    private $item_master = "item_master";
	private $item_category = "item_category";
	
	public function getProductList($data=[]){
		$queryData['tableName'] = $this->item_master;
		$queryData['select'] = 'item_master.*,item_category.category_name';
		$queryData['leftJoin']['item_category'] = 'item_category.id = item_master.category_id';

		if(!empty($data['id'])) { 
			$queryData['where']['item_master.id'] = $data['id'];
		}

		if(!empty($data['limit'])) { 
			$queryData['limit'] = $data['limit']; 
			$queryData['order_by']['item_master.created_at'] = "DESC"; 
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
}
?>