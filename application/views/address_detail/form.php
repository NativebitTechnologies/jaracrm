	<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">


			<div class="col-md-12 form-group">
				<label for="country">Country</label>
				<input type="text" name="country" id="country" class="form-control countryList req" value="<?=(!empty($dataRow->country)) ? $dataRow->country : ""?>" />
			</div>
			<div class="col-md-12 form-group">
				<div class="input-group">
					<label for="state" style="width:75%">State</label>
					<label for="state_code" style="width:25%">State Code</label>
				</div>
				<div class="input-group">
					<input type="text" name="state" id="state" class="form-control stateList req" value="<?=(!empty($dataRow->state)) ? $dataRow->state : ""?>" style="width:75%" />
					<input type="text" name="state_code" id="state_code" class="form-control req" value="<?=(!empty($dataRow->state_code)) ? $dataRow->state_code : ""?>" style="width:25%" />
				</div>
			</div>
			<div class="col-md-12 form-group">
				<label for="district">District</label>
				<input type="text" name="district" id="district" class="form-control districtList req" value="<?=(!empty($dataRow->district)) ? $dataRow->district : ""?>" />
			</div>
			<div class="col-md-12 form-group">
				<label for="city">City</label>
				<input type="text" name="city" id="city" class="form-control cityList req" value="<?=(!empty($dataRow->city)) ? $dataRow->city : ""?>" />
			</div>
        </div>
	</div>
</form>
<script src="<?=base_url()?>assets/src/typehead.js?v=<?=time()?>"></script>
<script>
$(document).ready(function(){
	$('.countryList').typeahead({
		source: function(query, result)
		{
			$.ajax({
				url:base_url + controller +'/getAddressSearch',
				method:"POST",
				global:false,
				data:{query:query,country_list:1},
				dataType:"json",
				success:function(data){
					result($.map(data, function(row){ return row.country; }));
				}
			});
		}
	});
	
	$('.stateList').typeahead({
		source: function(query, result)
		{
			$.ajax({
				url:base_url + controller +'/getAddressSearch',
				method:"POST",
				global:false,
				data:{query:query,state_list:1},
				dataType:"json",
				success:function(data){
					result($.map(data, function(row){ return row.state; }));
				}
			});
		}
	});
	
	$('.districtList').typeahead({
		source: function(query, result)
		{
			$.ajax({
				url:base_url + controller +'/getAddressSearch',
				method:"POST",
				global:false,
				data:{query:query,district_list:1},
				dataType:"json",
				success:function(data){
					result($.map(data, function(row){ return row.district; }));
				}
			});
		}
	});
	
	$('.cityList').typeahead({
		source: function(query, result)
		{
			$.ajax({
				url:base_url + controller +'/getAddressSearch',
				method:"POST",
				global:false,
				data:{query:query,city_list:1},
				dataType:"json",
				success:function(data){
					result($.map(data, function(row){ return row.city; }));
				}
			});
		}
	});
});
</script>