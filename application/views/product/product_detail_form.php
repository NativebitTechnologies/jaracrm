<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="item_id" id="item_id" value="<?=(!empty($dataRow->item_id))?$dataRow->item_id:$item_id?>">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">

            <?php
                if(!empty($customFieldList)):
                    foreach($customFieldList as $field):
                        
                        echo '<div class="col-md-6 form-group">
                            <label for="wt_pcs">'.$field->field_name.'</label>';
                            
                            if($field->field_type == 'SELECT'):
                                
                                echo '<select name="f'.$field->field_idx.'" id="f'.$field->field_idx.'" class="form-control selectBox">
                                    <option value="">Select</option>';
                                
                                foreach($masterDetailList as $row):
                                    if($row->type == $field->id):
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