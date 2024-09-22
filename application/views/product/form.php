	<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">

			<div class="col-md-6 form-group">
				<div class="input-group">
					<label for="item_code" style="width:25%">Item Code</label>
					<label for="item_name" style="width:75%">Item Name</label>
				</div>
				<div class="input-group">
					<input type="text" name="item_code" id="item_code" class="form-control req" value="<?=(!empty($dataRow->item_code)) ? $dataRow->item_code : ""?>" style="width:25%"/>
					<input type="text" name="item_name" id="item_name" class="form-control req" value="<?=(!empty($dataRow->item_name)) ? htmlentities($dataRow->item_name) : ""?>"  style="width:75%" />
				</div>
			</div>
			<div class="col-md-3 form-group">
				<label for="category_id">Item Category</label>
				<select name="category_id" id="category_id" class="form-control selectBox req">
					<?=getItemCategoryListOption($categoryList,((!empty($dataRow->category_id))?$dataRow->category_id:0))?>
                </select>
			</div>
			<div class="col-md-3 form-group">
				<label for="unit_name">Unit</label>
				<select name="unit_name" id="unit_name" class="form-control selectBox req">
					<?=getItemUnitListOption($unitList,((!empty($dataRow->unit_name))?$dataRow->unit_name:""))?>
                </select>
			</div>

			<div class="col-md-3 form-group">
				<label for="hsn_code">HSN Code</label>
				<input type="text" name="hsn_code" id="hsn_code" class="form-control" value="<?=(!empty($dataRow->hsn_code)) ? $dataRow->hsn_code : ""?>" />
			</div>
			<div class="col-md-3 form-group">
				<label for="gst_per">GST(%)</label>
				<select name="gst_per" id="gst_per" class="form-control selectBox calMRP">
                    <?php
						foreach ($gstPer as $rate=>$val) :
							$selected = (!empty($dataRow->gst_per) && $dataRow->gst_per == $rate) ? "selected" : "";
							echo '<option value="' . $rate . '" ' . $selected . '>' . $val . '</option>';
						endforeach;
                    ?>
                </select>
			</div>
			<div class="col-md-2 form-group">
				<label for="price">Price<small>(Exc. Tax)</small></label>
				<input type="text" name="price" id="price" class="form-control calMRP" value="<?=(!empty($dataRow->price)) ? $dataRow->price : ""?>" />
			</div>
			<div class="col-md-2 form-group">
				<label for="mrp">MRP<small>(Inc. Tax)</small></label>
				<input type="text" name="mrp" id="mrp" class="form-control calMRP" value="<?=(!empty($dataRow->mrp)) ? $dataRow->mrp : ""?>" />
			</div>
			<div class="col-md-2 form-group">
				<label for="wt_pcs">Weight Per Pcs.</label>
				<input type="text" name="wt_pcs" id="wt_pcs" class="form-control" value="<?=(!empty($dataRow->wt_pcs)) ? $dataRow->wt_pcs : ""?>" />
			</div>

			<div class="col-md-12 form-group">
				<label for="remark">Description</label>
				<textarea name="remark" id="remark" class="form-control"><?=(!empty($dataRow->remark)) ? $dataRow->remark : ""?></textarea>
			</div>
        </div>
	</div>
</form>
<script>
$(document).ready(function(){
    $(document).on('change','.calMRP',function(){
        var gst_per = $("#gst_per").val() || 0;
        var price = $("#price").val() || 0;
        var mrp = $("#mrp").val() || 0;
        if(gst_per > 0){
            if(($(this).attr('id') == "price" || $(this).attr('id') == "gst_per") && parseFloat(price) > 0){
                var tax_amt = parseFloat( (parseFloat(price) * parseFloat(gst_per) ) / 100 ).toFixed(2);
                var new_mrp = parseFloat( parseFloat(price) + parseFloat(tax_amt) ).toFixed(2);
                $("#mrp").val(new_mrp);
                return true;
            }

            if($(this).attr('id') == "mrp"  && parseFloat(mrp) > 0){
                var gstReverse = parseFloat(( ( parseFloat(gst_per) + 100 ) / 100 )).toFixed(2);
                var new_price = parseFloat( parseFloat(mrp) / parseFloat(gstReverse) ).toFixed(2);
    		    $("#price").val(new_price);
                return true;
            }
        }else{
            if(($(this).attr('id') == "price" || $(this).attr('id') == "gst_per") && price > 0){
                $("#mrp").val(price);
                return true;
            }

            if(mrp > 0){
                $("#price").val(mrp);
                return true;
            }
        }
    });
});
</script>