<?php
class TransactionMainModel extends MasterModel{

    public function getVouNumber($data){
        $voucherSeries = $this->getVouPerfix($data);
        $autoStartNo = (!empty($voucherSeries->auto_start))?$voucherSeries->auto_start:1;
        $vouPrefix = (!empty($voucherSeries->vou_prefix))?$voucherSeries->vou_prefix:"";

        $nextNo = $this->getNextNo(['tableName'=>$data['tableName'],'currentNo'=>1,'condition'=>$data['condition'],'numberColumn'=>$data['numberColumn']]);
        $nextNo = (!empty($nextNo))?($nextNo + 1):$autoStartNo;

        $vouNumber = $vouPrefix.$nextNo;

        return ['vou_prefix'=>$vouPrefix,'vou_no'=>$nextNo,'vou_number'=>$vouNumber];
    }

    public function getVouPerfix($data){
        $queryData = [];
        $queryData['tableName'] = "voucher_prefix";
        $queryData['select'] = "vou_prefix,auto_start";
        $queryData['where']['vou_name_s'] = $data['vou_name_s'];
        $result = $this->getData($queryData,'row');
        return $result;
    }

    public function getNextNo($data=[]){
        $queryData = [];
        $queryData['tableName'] = $data['tableName'];

        if(empty($data['currentNo'])):
            $queryData['select'] = "IFNULL((MAX(".$data['numberColumn'].") + 1),1) as next_no";
        else:
            $queryData['select'] = "MAX(".$data['numberColumn'].") as next_no";
        endif;

        $queryData['customWhere'][] = $data['condition'];
        $result = $this->getData($queryData,'row')->next_no;

		return $result;
    }
}
?>