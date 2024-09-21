
<form>
    <div class="col-md-12">
        <div class="row">            
			<input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id) ? $dataRow->id : "")?>" />

			<?php if(!empty($next_seq_no) && $next_seq_no > $MAX_LEAD_STAGE ){ ?> 
            
			<h5 class="text-danger"> You are reached your Maximum Stage Limit (<?=$MAX_LEAD_STAGE?>)</h5>
            
			<?php }else{ ?>
                <div class="col-md-12 form-group">
                    <h5 class="text-danger font-bold fs-14"> Your Maximum Stage Limit is : <?=$MAX_LEAD_STAGE?></h5>
                    <label for="stage_type">Stage Name</label>
                    <input type="text" name="stage_type" id="stage_type" class="form-control req" value="<?=(!empty($dataRow->stage_type) ? $dataRow->stage_type : "")?>" />
                </div>
                <div class="col-md-6 form-group">
                    <label for="crate">Conversion Rate (%)	</label>
                    <input type="text" name="crate" id="stage_type" class="form-control req numericOnly" value="<?=(!empty($dataRow->crate) ? $dataRow->crate : "")?>" />
                </div>
                <div class="col-md-6 form-group">
                    <div class="customColorInput">
                      <label for="stage_color">Select Color	</label>
                        <input type="text" id="colorCodePreview" name="stage_color" class="customColorInput__text-input jsColorValue" value="<?=(!empty($dataRow->stage_color) ? $dataRow->stage_color : "#FF7B00")?>">
                        <input type="color" id="colorCodeSelection" class="customColorInput__select-input" value="<?=(!empty($dataRow->stage_color) ? $dataRow->stage_color : "#FF7B00")?>">
                    </div>
                </div>
            
			<?php } ?>
        </div>
    </div>
</form>
<script >
// Get the elements
var colorCodePreviewInput = document.getElementById("colorCodePreview");
var colorCodeSelectionInput = document.getElementById("colorCodeSelection");

// Function to update preview body color input and store value in localStorage
function updateColor() {
  const colorValue = this.value;
  if (this === colorCodeSelectionInput) {
    colorCodePreviewInput.value = colorValue;
  } else if (this === colorCodePreviewInput) {
    colorCodeSelectionInput.value = colorValue;
  }
}

// Event listener for input event on color inputs
colorCodeSelectionInput.addEventListener("input", updateColor);
colorCodePreviewInput.addEventListener("input", updateColor);

</script>