	<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
            <input type="hidden" name="type" id="type" value="<?=(!empty($dataRow->id))?$dataRow->id:1?>">

			<div class="col-md-12 form-group">
				<label for="zone_name">Zone Name</label>
				<input type="text" name="zone_name" id="zone_name" class="form-control req" value="<?=(!empty($dataRow->zone_name)) ? $dataRow->zone_name : ""?>" />
			</div>
			<div class="col-md-12 form-group">
				<label for="remark">Remark</label>
				<textarea name="remark" id="remark" class="form-control"><?=(!empty($dataRow->remark)) ? $dataRow->remark : ""?></textarea>
			</div>
        </div>
	</div>
</form>