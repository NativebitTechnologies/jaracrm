<form id="addSelectOption">
	<div class="row">
		<input type="hidden" name="id" id="id" value="" />
		<input type="hidden" name="udf_id" id="udf_id" value="<?=(!empty($udf_id))?$udf_id:""; ?>" />
		
		<div class="col-md-9 form-group">
			<label for="title">Options</label>
			<input type="text" name="title" id="title" class="form-control req" value="">
		</div>
		<div class="col-md-3 form-group">
			<button type="button" class="btn btn-success btn-save save-form btn-block mt-30" onclick="saveOptions({'formId':'addSelectOption','fnsave':'saveSelectOption'});">Save</button>
		</div>
	</div>
	<hr>
	<div class="middle-content1 container-xxl p-0 config-box">
		<div class="row layout-top-spacing">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
				<div class="widget widget-table-one dynamic_opt p-0">
					<div class="widget-heading rounded-tp-2 mb-0 gradient-theme  text-white">
						<h5 class="text-white"><?=$dataRow->field_name?></h5>
					</div>
					<div class="widget-content do_wrapper optionRows pad-15"><?=$optionRows?></div>
				</div>
			<div>
		<div>
	</div>
</form>
<script>
	function saveOptions(postData){
		setPlaceHolder();

		var formId = postData.formId;
		var form = $('#'+formId)[0];
		var fd = new FormData(form);
		$.ajax({
			url: base_url + controller + '/saveSelectOption',
			data:fd,
			type: "POST",
			processData:false,
			contentType:false,
			dataType:"json",
		}).done(function(data){
			if(data.status==1){
				$('#title').val('');
				$(".optionRows").html(data.optionRows);
				//Swal.fire({ icon: 'success', title: data.message});
			}else{
				if(typeof data.message === "object"){
					$(".error").html("");
					$.each( data.message, function( key, value ) {$("."+key).html(value);});
				}			
			}				
		});
	}

	function removeOptions(data){
		var msg = "Record";
		var send_data = data.postData;
		
		$.ajax({
			url: base_url + controller + '/deleteSelectOption',
			data: send_data,
			type: "POST",
			dataType:"json",
		}).done(function(response){
			if(response.status==0){
				//Swal.fire( 'Sorry...!', response.message, 'error' );
			}else{
				$(".optionRows").html(response.optionRows);
			}
		});
	}
</script>