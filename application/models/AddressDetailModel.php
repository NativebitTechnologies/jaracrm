<?php
class AddressDetailModel extends MasterModel{
	private $address_master = 'address_master';

    public function getAddressList($data=array()){
        $queryData = array();
        $queryData['tableName']  = $this->address_master;
		
		if(!empty($data['id'])){
            $queryData['where']['id'] = $data['id'];
		}

		if(!empty($data['country_list'])){
			$queryData['select'] = "DISTINCT(country) as country";
			$queryData['like']['country'] = $data['query']; 
		}
		if(!empty($data['state_list'])){
			$queryData['select'] = "DISTINCT(state) as state";
			$queryData['like']['state'] = $data['query']; 
		}
		if(!empty($data['district_list'])){
			$queryData['select'] = "DISTINCT(district) as district";
			$queryData['like']['district'] = $data['query']; 
		}
		if(!empty($data['city_list'])){
			$queryData['select'] = "DISTINCT(city) as city";
			$queryData['like']['city'] = $data['query']; 
		}

        if(!empty($data['search'])){
            $queryData['like']['country'] = $data['search'];
            $queryData['like']['state'] = $data['search'];
            $queryData['like']['district'] = $data['search'];
            $queryData['like']['city'] = $data['search'];
		}
		
        if(!empty($data['limit'])){
            $queryData['limit'] = $data['limit']; 
            $queryData['order_by']['created_at'] = "DESC"; 
        }

        if(isset($data['start']) && isset($data['length'])){
            $queryData['start'] = $data['start'];
            $queryData['length'] = $data['length'];
        }
		
		if(!empty($data['result_type'])){
            return $this->getData($queryData,$data['result_type']);
        }elseif(!empty($data['id'])){
            return $this->getData($queryData,"row");
        }else{
            return $this->getData($queryData,"rows");
        }
    }

    public function saveAddress($data){
        try {
            $this->db->trans_begin();

			$data['checkDuplicate'] = ['country','state','district','city'];
            $result = $this->store($this->address_master, $data, 'Address');
            
            if ($this->db->trans_status() !== FALSE) :
                $this->db->trans_commit();
                return $result;
            endif;
        } catch (\Exception $e) {
            $this->db->trans_rollback();
            return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
        }
    }

    public function deleteAddress($data){
        try {
            $this->db->trans_begin();

            $checkData['columnName'] = ['address_id'];
            $checkData['value'] = $data['id'];
            $checkUsed = $this->checkUsage($checkData);

            if($checkUsed == true):
                $this->db->trans_rollback();
                return ['status'=>0,'message'=>'The Party is currently in use. you cannot delete it.'];
            endif;

            $result = $this->trash($this->address_master, ['id' => $data['id']], 'Party');

            if ($this->db->trans_status() !== FALSE) :
                $this->db->trans_commit();
                return $result;
            endif;
        } catch (\Exception $e) {
            $this->db->trans_rollback();
            return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
        }
    }
}
?>