<?php

/* Get Party List Options */
function getPartyListOption($partyList,$partyId = 0){
	$options = '';
	foreach($partyList as $row):
		$selected = (!empty($partyId) && $partyId == $row->id)?"selected":"";
		$partyName = $row->party_name;
		$options .= '<option value="'.$row->id.'" '.$selected.'>'.$partyName.'</option>';
	endforeach;

	return $options;
}

/* Item Category List Options */
function getItemCategoryListOption($categoryList,$categoryId = 0,$categoryGroup = 0){
	if(!empty($categoryGroup)):
		$groupedCategory = array_reduce($categoryList, function($itemData, $row) {
			$itemData[$row->parent_category][] = $row;
			return $itemData;
		}, []);

		$options = '';
		foreach ($groupedCategory as $parentCategory => $item):
			$options .= '<optgroup label="' . $parentCategory . '">';
			foreach ($item as $row):
				$selected = (!empty($categoryId) && $categoryId == $row->id)?"selected":"";
				$options .= '<option value="'.$row->id.'" '.$selected.'>'.$row->category_name.'</option>';
			endforeach;
			$options .= '</optgroup>';
		endforeach;
	else:
		$options = '';
		foreach($categoryList as $row):
			$selected = (!empty($categoryId) && $categoryId == $row->id)?"selected":"";
			$options .= '<option value="'.$row->id.'" '.$selected.'>'.$row->category_name.'</option>';
		endforeach;
	endif;

	return $options;
}

/* Get Item List Options */
function getItemListOption($itemList,$itemId = 0,$categoryGroup = 0){
	if(!empty($categoryGroup)):
		$groupedCategory = array_reduce($itemList, function($itemData, $row) {
			$itemData[$row->category_name][] = $row;
			return $itemData;
		}, []);

		$options = '';
		foreach ($groupedCategory as $category => $item):
			$options .= '<optgroup label="' . $category . '">';
			foreach ($item as $row):
				$selected = (!empty($itemId) && $itemId == $row->id)?"selected":"";
				$itemName = (!empty($row->item_code))?"[ ".$row->item_code." ] ".$row->item_name : $row->item_name;
				$options .= '<option value="'.$row->id.'" '.$selected.'>'.$itemName.'</option>';
			endforeach;
			$options .= '</optgroup>';
		endforeach;
	else:
		$options = '';
		foreach($itemList as $row):
			$selected = (!empty($itemId) && $itemId == $row->id)?"selected":"";
			$itemName = (!empty($row->item_code))?"[ ".$row->item_code." ] ".$row->item_name : $row->item_name;
			//if($row->item_type == 1):
				$itemName .= (!empty($row->category_name))?" ".$row->category_name : "";
			//endif;
			$options .= '<option value="'.$row->id.'" '.$selected.'>'.$itemName.'</option>';
		endforeach;
	endif;

	return $options;
}

/* Get Item Unit List Options */
function getItemUnitListOption($unitList,$unit_name = ""){
	$options = '';
	foreach($unitList as $row):
		$selected = (!empty($unit_name) && $unit_name == $row->unit_name)?"selected":"";
		$options .= '<option value="'.$row->unit_name.'" data-id="'.$row->id.'" data-description="'.$row->description.'" '.$selected.'>[' . $row->unit_name . '] ' . $row->description . '</option>';
	endforeach;

	return $options;
}

/* Get HSN Code List Options */
function getHsnCodeListOption($hsnCodeList,$hsn = ""){
	$options = '';
	foreach($hsnCodeList as $row):
		$selected = (!empty($hsn) && $hsn == $row->hsn)?"selected":"";
		$options .= '<option value="'.$row->hsn.'" data-gst_per="'.floatVal($row->gst_per).'" '.$selected.'>'.$row->hsn.'</option>';
	endforeach;

	return $options;
}

/* Get Location List Options */
function getLocationListOption($locationList,$locationId = 0){
	$groupedStores = array_reduce($locationList, function($store, $location) {
		$store[$location->store_name][] = $location;
		return $store;
	}, []);
	
	$options = '';
	foreach ($groupedStores as $store => $location):
		$options .= '<optgroup label="' . $store . '">';
		foreach ($location as $row):
			$selected = (!empty($locationId) && $locationId == $row->id)?"selected":"";
			$options .= '<option value="' . $row->id . '" '.$selected.'>' . $row->location . '</option>';
		endforeach;
		$options .= '</optgroup>';
	endforeach;

	return $options;
}

/* Get Tax Class Options */
function getTaxClassListOption($taxClassList,$tax_class_id = 0){
	$options = '<option value="">Select Type</option>';
	foreach($taxClassList as $row):
		$selected = (!empty($tax_class_id) && $tax_class_id == $row->id)?"selected":(($row->is_defualt == 1)?"selected":"");
		$options .= '<option value="'.$row->id.'" data-gst_type="'.$row->gst_type.'" data-sp_acc_id="'.$row->sp_acc_id.'" data-tax_class="'.$row->tax_class.'" '.$selected.'>'.$row->tax_class_name.'</option>';
	endforeach;

	return $options;
}

/* Get Sales / Purchase Account Options */
function getSpAccListOption($accounts,$acc_id = 0){
	$options = '<option value="">Select Type</option>';
	foreach($accounts as $row):
		$selected = (!empty($acc_id) && $acc_id == $row->id)?"selected":"";
		$options .= '<option value="'.$row->id.'" data-tax_class="'.$row->system_code.'" '.$selected.'>'.$row->party_name.'</option>';
	endforeach;

	return $options;
}

/* Get Empoyee List Options */
function getEmployeeListOption($employeeList,$emp_id = 0){
	$options = '';
	foreach($employeeList as $row):
		$selected = (!empty($emp_id) && $emp_id == $row->id)?"selected":"";
		$options .= '<option value="'.$row->id.'" '.$selected.'>'.$row->emp_name.'</option>';
	endforeach;

	return $options;
}

/* Get Company List Options */
function getCompanyListOptions($companyList,$company_id = ""){
	$options = '';
	foreach($companyList as $row):
		$selected = (!empty($company_id) && in_array($row->id,explode(",",$company_id)))?"selected":"";
		$options .= '<option value="'.$row->id.'" data-state_code="'.$row->company_state_code.'" '.$selected.'>'.$row->company_code.'</option>';
	endforeach;

	return $options;
}

/* Get TDS Class List */
function getTDSClassListOptions($tdsClassList,$tds_class_id = 0){
	$options = '';
	foreach($tdsClassList as $row):
		$selected = (!empty($tds_class_id) && $tds_class_id == $row->id)?"selected":"";
		$options .= '<option value="'.$row->id.'" data-class_type="'.$row->class_type.'" '.$selected.'>'.$row->class_name.'</option>';
	endforeach;

	return $options;
}

/* Source List Options */
function getSourceListOptions($sourceList,$source = ""){
	$options = '';
	foreach($sourceList as $row):
		$selected = (!empty($source) && $source == $row->label)?"selected":"";
		$options .= '<option value="'.$row->label.'" '.$selected.'>'.$row->label.'</option>';
	endforeach;

	return $options;
}

/* Sales Zone List Options */
function getSalesZoneListOptions($salesZoneList,$salesZoneId = ""){
	$options = '';
	foreach($sourceList as $row):
		$selected = (!empty($source) && $source == $row->label)?"selected":"";
		$options .= '<option value="'.$row->label.'" '.$selected.'>'.$row->label.'</option>';
	endforeach;

	return $options;
}

/* Business Type List Options */
function getBusinessTypeList($businessTypeList,$businessType = ""){
	$options = '';
	foreach($businessTypeList as $row):
		$selected = (!empty($businessType) && $businessType == $row->type_name)?"selected":"";
		$options .= '<option value="'.$row->type_name.'" data-parent_type="'.$row->parent_id .'" '.$selected.'>'.$row->type_name.'</option>';
	endforeach;
	return $options;
}

?>