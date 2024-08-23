<form id="addSelectOption">
    <div class="col-md-12">
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
		<div class="row">
			<div class="table-responsive">
				<table id="selectOption" class="table dt-table-hover dataTable border">
					<thead class="thead-info">
						<tr>
							<th>#</th>
							<th>Option</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody class="optionRows"><?=$optionRows?></tbody>
				</table>
			</div>
		</div>
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
				Swal.fire({ icon: 'success', title: data.message});
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
				Swal.fire( 'Sorry...!', response.message, 'error' );
			}else{
				$(".optionRows").html(response.optionRows);
			}
		});
		
		/*Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!',
		}).then(function(result) {
			if (result.isConfirmed)
			{
				$.ajax({
					url: base_url + controller + '/delete',
					data: send_data,
					type: "POST",
					dataType:"json",
				}).done(function(response){
					if(response.status==0){
						Swal.fire( 'Sorry...!', response.message, 'error' );
					}else{
						$(".optionRows").html(response.optionRows);
					}
				});
				Swal.fire( 'Deleted!', 'Your Record has been deleted.', 'success' );
			}
		});*/
	}
</script>