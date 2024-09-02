<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="party_id" id="party_id" value="<?=(!empty($dataRow->id))?$dataRow->id:$party_id?>">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->pd_id))?$dataRow->pd_id:""?>">

            <div class="col-md-6 form-group">
                <label for="gstin">GSTIN</label>
                <input type="text" name="gstin" id="gstin" class="form-control" value="<?=(!empty($dataRow->gstin))?$dataRow->gstin:""?>">
            </div>

            <div class="col-md-6 form-group">
                <label for="currency">Currency</label>
                <select name="currency" id="currency" class="form-control selectBox">
                    <option value="">Select Currency</option>
                    <?php
                        foreach($currencyList as $row):
                            $selected = (!empty($dataRow->currency) && $dataRow->currency == $row->currency)?"selected":((empty($dataRow->currency) && $row->currency == "INR")?"selected":"");
                            echo '<option value="'.$row->currency.'" '.$selected.'>['.$row->currency.'] '.$row->currency_name.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>

            <div class="col-md-6 form-group">
                <label for="business_capacity">Business Capacity (Amt.)</label>
                <input type="text" name="business_capacity" id="business_capacity" class="form-control floatOnly" value="<?=(!empty($dataRow->business_capacity))?$dataRow->business_capacity:""?>">
            </div>

            <div class="col-md-6 form-group">
				<label for="product_used">Product Used</label>
				<select name="product_used[]" id="product_used" class="form-control selectBox" multiple>
                    <?php
						foreach ($categoryList as $row):
							$selected = (!empty($dataRow->product_used) && in_array($row->id,explode(",",$dataRow->product_used))) ? "selected" : "";
							echo '<option value="' . $row->id . '" ' . $selected . '>' . $row->category_name . '</option>';
						endforeach;
                    ?>
                </select>
			</div>

            <?php
                if(!empty($customFieldList)):
                    foreach($customFieldList as $field):
                        
                        echo '<div class="col-md-6 form-group">
                            <label for="wt_pcs">'.$field->field_name.'</label>';
                            
                            if($field->field_type == 'SELECT'):
                                
                                echo '<select name="f'.$field->field_idx.'" id="f'.$field->field_idx.'" class="form-control selectBox">
                                    <option value="">Select</option>';
                                
                                foreach($masterDetailList as $row):
                                    if($row->udf_id == $field->id):
                                        $selected = (!empty($dataRow) && !empty(htmlentities($dataRow->{'f'.$field->field_idx}) && htmlentities($dataRow->{'f'.$field->field_idx}) == htmlentities($row->title)))?'selected':'';
                                                                                
                                        echo '<option value="'.htmlentities($row->title).'" '.$selected.'>'.$row->title.'</option>';                                        
                                    endif;
                                endforeach;
                                echo '</select>';
                            elseif($field->field_type == 'TEXT'):                                
                                echo '<input type="text" name="f'.$field->field_idx.'" id="f'.$field->field_idx.'" class="form-control" value="'.((!empty($dataRow) && !empty($dataRow->{'f'.$field->field_idx}))?$dataRow->{'f'.$field->field_idx}:'').'">';
                            elseif($field->field_type == 'NUM'):                                
                                echo '<input type="text" name="f'.$field->field_idx.'" id="f'.$field->field_idx.'" class="form-control floatOnly" value="'.((!empty($dataRow) && !empty($dataRow->{'f'.$field->field_idx}))?$dataRow->{'f'.$field->field_idx}:'').'">';                                
                            endif;
                            
                        echo '</div>';                        
                    endforeach;
                endif;
            ?>
        </div>
    </div>
</form>