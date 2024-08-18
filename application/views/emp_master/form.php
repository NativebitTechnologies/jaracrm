<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">

			<div class="col-md-12 form-group">
                <label for="emp_name">Employee Name</label>
                <input type="text" name="emp_name" id="emp_name" class="form-control req" value="<?=(!empty($dataRow->emp_name))?$dataRow->emp_name:""?>">
            </div>
			
            <div class="col-md-6 form-group">
                <label for="emp_code">Employee Code</label>
                <input type="text" name="emp_code" id="emp_code" class="form-control req" value="<?=(!empty($dataRow->emp_code))?$dataRow->emp_code:""?>">
            </div>

			<div class="col-md-6 form-group">
                <label for="designation">Designation</label>
                <input type="text" name="designation" id="designation" class="form-control designation" value="<?=(!empty($dataRow->designation))?$dataRow->designation:""?>">
            </div>

            <div class="col-md-6 form-group">
                <label for="contact_no">Mobile No.</label>
                <input type="text" name="contact_no" id="contact_no" class="form-control req numericOnly" value="<?=(!empty($dataRow->contact_no))?$dataRow->contact_no:""?>">
            </div>
			
			<div class="col-md-6 form-group">
                <label for="gender">Gender</label>
                <select name="gender" id="gender" class="form-control req selectBox">
					<option value="Male" <?=(!empty($dataRow->gender) && $dataRow->gender == "Male")?"selected":""?>>Male</option>
					<option value="Female" <?=(!empty($dataRow->gender) && $dataRow->gender == "Female")?"selected":""?>>Female</option>
				</select>
            </div>
        </div>
    </div>
</form>
<script src="<?=base_url()?>assets/src/typehead.js?v=<?=time()?>"></script>
<script>
$(document).ready(function(){
	$('.designation').typeahead({
		source: function(query, result)
		{
			$.ajax({
				url:base_url + controller +'/getDesignation',
				method:"POST",
				global:false,
				data:{query:query,designation:1},
				dataType:"json",
				success:function(data){
					result($.map(data, function(row){ return row.designation; }));
				}
			});
		}
	});
});