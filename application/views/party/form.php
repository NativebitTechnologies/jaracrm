<form>
	<div class="row">
		<input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""; ?>" />
		<input type="hidden" name="party_type" id="party_type" value="<?=(!empty($dataRow->party_type))?$dataRow->party_type:$party_type; ?>" />

		<div class="col-md-9 form-group">
			<div class="input-group">
				<label for="party_code">Company Code</label>
				<label for="party_name">Company/Trade Name</label>
			</div>
		 </div>
		<div class="col-md-9 form-group">
			<div class="input-group">
				<input type="text" name="party_code" id="party_code" class="form-control req" value="<?= (!empty($dataRow->party_code)) ? $dataRow->party_code : "" ?>">
				<input type="text" name="party_name" id="party_name" class="form-control req" value="<?= (!empty($dataRow->party_name)) ? $dataRow->party_name : "" ?>">
			</div>
		</div>
		<!--
		<div class="col-md-9 form-group">
		   <label for="party_code">Company Code</label>
		   <input type="text" name="party_code" id="party_code" class="form-control req" value="<?= (!empty($dataRow->party_code)) ? $dataRow->party_code : "" ?>">
		</div>
		<div class="col-md-8 form-group">
		   <label for="party_name">Company/Trade Name</label>
		   <input type="text" name="party_name" id="party_name" class="form-control req" value="<?= (!empty($dataRow->party_name)) ? $dataRow->party_name : "" ?>">
		</div>
		-->
		<div class="col-md-3 form-group">
			<label for="source">Source</label>
			<select name="source" id="source" class="form-control selectBox">
				<option value="">Select Source</option>
				<?=getSourceListOptions($sourceList,((!empty($dataRow->source))?$dataRow->source:""))?>
			</select>
		</div>

		<div class="col-md-3 form-group">
			<label for="executive_id">Sales Executives</label>
			<select name="executive_id" id="executive_id" class="form-control selectBox">
				<option value="">Select Sales Executive</option>
				<?=getEmployeeListOption($executiveList,((!empty($dataRow->executive_id))?$dataRow->executive_id:""))?>
			</select>
		</div>

		<div class="col-md-3 form-group">
			<label for="sales_zone_id">Sales Zone</label>
			<select name="sales_zone_id" id="sales_zone_id" class="form-control selectBox req">
				<option value="">Sales Zone</option>
				<?=getSalesZoneListOptions($salesZoneList,((!empty($dataRow->sales_zone_id))?$dataRow->sales_zone_id:""))?>
			</select>
		</div>

		<div class="col-md-3 form-group">
			<label for="business_type">Business Type</label>
			<select name="business_type" id="business_type" class="form-control selectBox">
				<option value="">Select Type</option>
				<?=getBusinessTypeList($businessTypeList,((!empty($dataRow->business_type))?$dataRow->business_type:""))?>
			</select>
		</div>

		<div class="col-md-3 form-group">
			<label for="parent_id">Parent Type</label>
			<select name="parent_id" id="parent_id" class="form-control selectBox">
				<option value="">Select</option>
			</select>
		</div>

		<div class="col-md-3 form-group">
			<label for="contact_person">Contact Person</label>
			<input type="text" name="party_detail[contact_person]" id="contact_person" class="form-control" value="<?=(!empty($dataRow->contact_person))?$dataRow->contact_person:""?>">
		</div>

		<div class="col-md-3 form-group">
			<label for="contact_no">Contact No.</label>
			<input type="text" name="contact_no" id="contact_no" class="form-control numericOnly" value="<?=(!empty($dataRow->contact_no))?$dataRow->contact_no:""?>">
		</div>

		<div class="col-md-3 form-group">
			<label for="whatsapp_no">Whatsapp No.</label>
			<input type="text" name="whatsapp_no" id="whatsapp_no" class="form-control numericOnly" value="<?=(!empty($dataRow->whatsapp_no))?$dataRow->whatsapp_no:""?>">
		</div>

		<div class="col-md-4 form-group">
			<label for="email_id">Email</label>
			<input type="text" name="party_detail[email_id]" id="email_id" class="form-control" value="<?=(!empty($dataRow->email_id))?$dataRow->email_id:""?>">
		</div>

		<div class="col-md-4 form-group">
			<label for="gst_type">Registration Type</label>
			<select name="party_detail[gst_type]" id="gst_type" class="form-control modal-select2">
				<?php
					foreach($this->gstRegistrationTypes as $key=>$value):
						$selected = (!empty($dataRow->gst_type) && $dataRow->gst_type == $key)?"selected":"";
						echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
					endforeach;
				?>
			</select>
		</div>

		<div class="col-md-4 form-group">
			<label for="gstin">Party GSTIN</label>
			<span class="float-end">
				<a class="text-primary font-bold" id="getGstinDetail" href="javascript:void(0)">Verify</a>
			</span>
			<input type="text" name="party_detail[gstin]" id="gstin" class="form-control text-uppercase req" value="<?=(!empty($dataRow->gstin))?$dataRow->gstin:""; ?>" />
		</div>

		<div class="col-md-3 form-group">
			<label for="city">City</label>
			<input type="text" id="city" class="form-control cityList req" value="<?=(!empty($dataRow->city))?$dataRow->city:""?>">
			<input type="hidden" name="address_id" id="address_id" value="<?=(!empty($dataRow->address_id))?$dataRow->address_id:""?>">
		</div>

		<div class="col-md-3 form-group">
			<label for="district">District</label>
			<input type="text" id="district" class="form-control cityList req" value="<?=(!empty($dataRow->district))?$dataRow->district:""?>">
		</div>

		<div class="col-md-3 form-group">
			<label for="state">State</label>
			<input type="text" id="state" class="form-control cityList req" value="<?=(!empty($dataRow->state))?$dataRow->state:""?>">
		</div>

		<div class="col-md-3 form-group">
			<label for="country">Country</label>
			<input type="text" id="country" class="form-control cityList req" value="<?=(!empty($dataRow->country))?$dataRow->country:""?>">
		</div>

		<div class="col-md-8 form-group">
			<label for="address">Address</label>
			<input type="text" name="party_detail[address]" id="address" class="form-control" value="<?=(!empty($dataRow->address))?$dataRow->address:""?>">
		</div>

		<div class="col-md-4 form-group">
			<label for="pincode">Pincode</label>
			<input type="text" name="party_detail[pincode]" id="pincode" class="form-control numericOnly" value="<?=(!empty($dataRow->pincode))?$dataRow->pincode:""?>">
		</div>
	</div>
</form>

<script src="<?=base_url()?>assets/src/typehead.js?v=<?=time()?>"></script>

<script>
$(document).ready(function(){

$('.cityList').typeahead({
	source: function(query, result){
		$.ajax({
			url:base_url + controller +'/getCityList',
			method:"POST",
			global:false,
			data:{query:query},
			dataType:"json",
			success:function(data){
				result($.map(data, function(row){ 
					return {
						name:row.city + ' | ' + row.district + ' | ' + row.state + ' | ' + row.country,
						id:row.id,
						country:row.country,
						state:row.state,
						district:row.district,
						city:row.city
					}; 
				}));
			}
		});
	},
	updater: function(item) {
		if(item.name != ""){                
			$("#address_id").val(item.id);
			setTimeout(function(){
				$("#city").val(item.city);
				$("#district").val(item.district);
				$("#state").val(item.state);
				$("#country").val(item.country);
			},200);
		}         
		return item;
	}
});

});
</script>