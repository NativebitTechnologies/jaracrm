<?php
class SalesEnquiryModel extends MasterModel{
    private $enquiryMaster = "se_master";
    private $enquiryTrans = "se_trans";

    public function getSalesEnquiryListing($data){
        $queryData = [];
        $queryData['tableName'] = $this->enquiryTrans;
        $queryData['select'] = "se_trans.*, se_master.trans_number, se_master.trans_date, party_master.party_name,executive_master.emp_name as executive_name, item_master.item_code, item_master.item_name";

        $queryData['leftJoin']['se_master'] = "se_master.id = se_trans.se_id";
        $queryData['leftJoin']['party_master'] = "party_master.id = se_master.party_id";
        $queryData['leftJoin']['employee_master as executive_master'] = "executive_master.id = se_master.sales_executive";
        $queryData['leftJoin']['item_master'] = "item_master.id = se_trans.item_id";

        $queryData['where']['se_trans.trans_status'] = $data['status'];

        if(!empty($data['search'])):
            $queryData['like']['se_master.trans_number'] = $data['search'];
            $queryData['like']['DATE_FORMAT(se_master.trans_date,"%d-%m-%Y")'] = $data['search'];
            $queryData['like']['party_master.party_name'] = $data['search'];
            $queryData['like']['executive_master.emp_name'] = $data['search'];
            $queryData['like']['item_master.item_name'] = $data['search'];
            $queryData['like']['se_trans.qty'] = $data['search'];
            $queryData['like']['se_trans.uom'] = $data['search'];
            $queryData['like']['se_trans.item_remark'] = $data['search'];
        endif;

        $queryData['order_by']["se_master.trans_date"] = "DESC";
        $queryData['order_by']["se_master.trans_no"] = "DESC";

        if(isset($data['start']) && isset($data['length'])):
            $queryData['start'] = $data['start'];
            $queryData['length'] = $data['length'];
        endif;

        $result =  $this->getData($queryData,"rows");
        return $result;
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            if(!empty($data['id'])):
                $this->trash($this->enquiryTrans,['se_id'=>$data['id']]);
            endif;

            $itemData = $data['itemData']; unset($data['itemData']);

            $result = $this->store($this->enquiryMaster,$data,'Sales Enquiry');

            foreach($itemData as $row):
                if($row['is_temp_item'] == 1):
                    if(empty($row['item_id'])):
                        $productDetail = $this->product->getProductList(['item_name'=>$row['item_name'],'result_type'=>'row']);

                        if(!empty($productDetail)):
                            $row['item_id'] = $productDetail->id;
                        else:
                            $item = [
                                'id' => '',
                                'item_name' => $row['item_name'],
                                'unit_name' => $row['uom'],
                                'is_temp_item' => $row['is_temp_item']
                            ];

                            $itemResult = $this->product->saveProduct($item);
                            $row['item_id'] = $itemResult['id'];
                        endif;
                    else:
                        $item = [
                            'id' => $row['item_id'],
                            'item_name' => $row['item_name'],
                            'unit_name' => $row['uom'],
                            'is_temp_item' => $row['is_temp_item']
                        ];

                        $itemResult = $this->product->saveProduct($item);
                    endif;
                endif;
                
                unset($row['is_temp_item'],$row['item_name']);

                $row['se_id'] = $result['id'];
                $row['is_delete'] = 0;
                
                $this->store($this->enquiryTrans,$row);
            endforeach;

            if(empty($data['id'])):
                $this->party->savePartyActivity(['party_id'=>$data['party_id'],'lead_stage'=>4,'ref_id'=>$result['id'],'ref_date'=>$data['trans_date']." ".date("H:i:s"),'ref_no'=>$data['trans_number']]);
            endif;

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }    
    }

    public function getSalesEnquiry($data){
        $queryData = [];
        $queryData['tableName'] = $this->enquiryMaster;
        $queryData['select'] = "se_master.*, party_master.party_name, executive_master.emp_name as executive_name";

        $queryData['leftJoin']['party_master'] = "party_master.id = se_master.party_id";
        $queryData['leftJoin']['employee_master as executive_master'] = "executive_master.id = se_master.sales_executive";

        $queryData['where']['se_master.id'] = $data['id'];

        $result = $this->getData($queryData,'row');

        if(!empty($data['itemList'])):
            $result->itemList = $this->getSalesEnquiryItems(['se_id'=>$data['id'],'only_pending_items'=>((isset($data['only_pending_items']))?1:0)]);
        endif;

        return $result;
    }

    public function getSalesEnquiryItems($data){
        $queryData = [];
        $queryData['tableName'] = $this->enquiryTrans;
        $queryData['select'] = "se_trans.*,item_master.item_code,item_master.item_name,item_master.is_temp_item,item_category.category_name,item_master.gst_per,item_master.price";

        $queryData['leftJoin']['item_master'] = 'item_master.id = se_trans.item_id';
        $queryData['leftJoin']['item_category'] = 'item_category.id = item_master.category_id';

        $queryData['where']['se_trans.se_id'] = $data['se_id'];

        if(!empty($data['only_pending_items'])):
            $queryData['where']['se_trans.trans_status'] = 0;
        endif;

        $result = $this->getData($queryData,'rows');
        return $result;
    }

    public function delete($data){
        try{
            $this->db->trans_begin();

            $this->trash($this->enquiryTrans,['se_id'=>$data['id']]);
            $result = $this->trash($this->enquiryMaster,['id'=>$data['id']],'Sales Enquiry');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }
}
?>