<style>

.customColorInput input::-webkit-color-swatch {
  border: 0;
}
.customColorInput input::-webkit-color-swatch-wrapper {
  padding: 0;
}

.customColorInput {
    display: flex;
    align-items: center;
    gap: 0px;
    flex-wrap: wrap;
    border: 1px solid #bfc9d4;
    border-radius: 6px;
    overflow: hidden;
    padding: 0.5rem 0.75rem !important;
}
/*
.customColorInput.isReadOnly {
  cursor: not-allowed;
}
.customColorInput.isReadOnly > input[type=text] {
  cursor: inherit;
  filter: contrast(0);
}
.customColorInput.isReadOnly > input[type=color] {
  pointer-events: none;
  cursor: inherit;
}*/
.customColorInput__text-input {
  max-width: calc(100% - 24px);
  flex-grow: 1;
  border: 0;
  line-height: 1;
}
.customColorInput__text-input:focus {
  outline: none;
  
}

.customColorInput:focus-within {border-color:#4d5eea!important;}
/*
.customColorInput .invalid-feedback {
  order: 3;
}*/
.customColorInput__select-input {
  flex-shrink: 0;
  order: 2;
  width: 20px;
  height: 20px;
  padding: 0;
  border: 0;
  border-radius: 100%;
  overflow: hidden;
  cursor: pointer;
}/*
.customColorInput::-webkit-color-swatch-wrapper {
  padding: 0;
}*/
</style>
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
                <div class="col-md-12 form-group">
                    <div class="customColorInput">
                        <input type="text" id="colorCodePreview" name="stage_color" class="customColorInput__text-input jsColorValue" value="#FF7B00">
                        <input type="color" id="colorCodeSelection" class="customColorInput__select-input" value="#FF7B00">
                    </div>
                    
                </div>
            
			<?php } ?>
        </div>
    </div>
</form>
<script id="rendered-js" >
// Get the elements
const colorCodePreviewInput = document.getElementById("colorCodePreview");
const colorCodeSelectionInput = document.getElementById("colorCodeSelection");

// Function to update preview body color input and store value in localStorage
function updateColor() {
  const colorValue = this.value;
  if (this === colorCodeSelectionInput) {
    colorCodePreviewInput.value = colorValue;
  } else if (this === colorCodePreviewInput) {
    colorCodeSelectionInput.value = colorValue;
  }
  localStorage.setItem("bodyColor", colorValue);
}

// Event listener for input event on color inputs
colorCodeSelectionInput.addEventListener("input", updateColor);
colorCodePreviewInput.addEventListener("input", updateColor);
//# sourceURL=pen.js
    </script>