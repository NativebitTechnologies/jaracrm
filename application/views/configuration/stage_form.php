<form>
    <div class="col-md-12">
        <div class="row">            
			<input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id) ? $dataRow->id : "")?>" />

            <?php if(!empty($next_seq_no) && $next_seq_no > 7){ ?>
            
			<h5 class="text-danger"> Only 7 Stage you can added.</h5>
            
			<?php }else{ ?>
			
                <div class="col-md-3 form-group">
                    <label for="sequence">Sequence</label>
                    <input type="text" name="sequence" id="sequence" class="form-control req" value="<?=(!empty($dataRow->sequence)) ? $dataRow->sequence : (!empty($next_seq_no) ? $next_seq_no : "")?>" readOnly />
                </div>
                <div class="col-md-9 form-group">
                    <label for="stage_type">Stage Type</label>
                    <input type="text" name="stage_type" id="stage_type" class="form-control req" value="<?=(!empty($dataRow->stage_type) ? $dataRow->stage_type : "")?>" />
                </div>
            
			<?php } ?>
        </div>
    </div>
</form>