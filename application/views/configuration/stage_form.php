<form>
    <div class="col-md-12">
        <div class="row">            
			<input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id) ? $dataRow->id : "")?>" />

			<?php if(!empty($next_seq_no) && $next_seq_no > $MAX_LEAD_STAGE ){ ?> 
            
			<h5 class="text-danger"> Your Maximum Stage Limit is : <?=$MAX_LEAD_STAGE?></h5>
            
			<?php }else{ ?>
                <div class="col-md-12 form-group">
                    <label for="stage_type">Stage Name <?=$next_seq_no?></label>
                    <input type="text" name="stage_type" id="stage_type" class="form-control req" value="<?=(!empty($dataRow->stage_type) ? $dataRow->stage_type : "")?>" />
                </div>
            
			<?php } ?>
        </div>
    </div>
</form>