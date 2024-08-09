<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-borderless table-striped">
            <thead class="thead-info">
                <tr>
                    <th>Expense Name</th>
                    <th>Percentage</th>
                    <th>Amount</th>
                    <th>Gst Per(%)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(!empty($expenseList)):                       

                        foreach($expenseList as $row):

                            $options = '';
                            foreach ($this->gstPer as $key => $gstPer):
                                $selected = (!empty($row->gst_per) && floatval($row->gst_per) == $key)?"selected":"";
                                $options .= "<option value='".$key."' ".$selected.">".$gstPer."</option>";
                            endforeach;

                            echo '<tr>
                                <td>'.$row->exp_name.'</td>
                                <td>
                                    <input type="hidden" name="expenseData['.$row->id.'][id'.$row->id.']" id="id'.$row->id.'" data-row_id="'.$row->id.'" value="'.$row->id.'">
                                    <input type="hidden" id="p_or_m'.$row->id.'" data-row_id="'.$row->id.'" value="'.$row->p_or_m.'">
                                    <input type="text" name="expenseData['.$row->id.'][per'.$row->id.']" id="per'.$row->id.'" class="form-control floatOnly calculateExpense" data-row_id="'.$row->id.'" value="">
                                </td>
                                <td>
                                    <input type="hidden" name="expenseData['.$row->id.'][amount'.$row->id.']" id="amount'.$row->id.'" value="">
                                    <input type="text" id="amt'.$row->id.'" class="form-control floatOnly calculateExpense" data-row_id="'.$row->id.'" value="">
                                </td>
                                <td>
                                    <select name="expenseData['.$row->id.'][gst_per'.$row->id.']" id="gst_per'.$row->id.'" class="form-control selectBox">
                                        '.$options.'
                                    </select>
                                </td>
                            </tr>';
                        endforeach;
                    else:
                        echo '<tr><td colspan="4" class="text-center">No data available in table</td></tr>';
                    endif;
                ?>
            </tbody>
        </table>
    </div>
</div>