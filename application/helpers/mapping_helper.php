<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function getSalesExpArrayMap($input){
    $result = array();
	$expAmount=0;
    if(!empty($input)):
        for($i=1; $i<=10 ; $i++):
            $result['exp'.$i.'_acc_id'] = (isset($input['exp'.$i.'_acc_id']))?$input['exp'.$i.'_acc_id']:0;
            $result['exp'.$i.'_per'] = (isset($input['exp'.$i.'_per']))?$input['exp'.$i.'_per']:0;
            $result['exp'.$i.'_amount'] = (isset($input['exp'.$i.'_amount']))?$input['exp'.$i.'_amount']:0;

            $result['id'.$i] = (isset($input['id'.$i]))?$input['id'.$i]:0;
            $result['per'.$i] = (isset($input['per'.$i]))?$input['per'.$i]:0;
            $result['amount'.$i] = (isset($input['amount'.$i]))?$input['amount'.$i]:0;
            $result['gst_per'.$i] = (isset($input['gst_per'.$i]))?$input['gst_per'.$i]:0;
            $expAmount += $result['amount'.$i];
        endfor;
    endif;
	$result['exp_amount'] = $expAmount;
	return $result;
}
?>