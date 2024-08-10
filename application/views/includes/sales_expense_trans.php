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

                            $expPer = $expAmount = $expGstPer = "";
                            if (!empty($salesExpenseData)) :
                                $expPer = $salesExpenseData->{'per'.$row->map_ind};
                                $expAmount = $salesExpenseData->{'amount'.$row->map_ind};
                                $expGstPer = $salesExpenseData->{'gst_per'.$row->map_ind};
                            endif;

                            $options = '';
                            foreach ($this->gstPer as $key => $gstPer):
                                $selected = (!empty($expGstPer) && floatval($expGstPer) == $key)?"selected":((!empty($row->gst_per) && floatval($row->gst_per) == $key)?"selected":"");
                                $options .= "<option value='".$key."' ".$selected.">".$gstPer."</option>";
                            endforeach;

                            echo '<tr>
                                <td>'.$row->exp_name.'</td>
                                <td>
                                    <input type="hidden" name="expenseData['.$row->id.'][id'.$row->map_ind.']" id="id'.$row->id.'" data-row_id="'.$row->id.'" value="'.$row->id.'">
                                    <input type="hidden" id="p_or_m'.$row->id.'" data-row_id="'.$row->id.'" value="'.$row->p_or_m.'">
                                    <input type="text" name="expenseData['.$row->id.'][per'.$row->map_ind.']" id="per'.$row->id.'" class="form-control floatOnly calculateExpense" data-row_id="'.$row->id.'" value="'.$expPer.'">
                                </td>
                                <td>
                                    <input type="hidden" name="expenseData['.$row->id.'][amount'.$row->map_ind.']" id="amount'.$row->id.'" value="'.$expAmount.'">
                                    <input type="text" id="amt'.$row->id.'" class="form-control floatOnly calculateExpense" data-row_id="'.$row->id.'" value="'.((!empty($expAmount))?abs($expAmount):"").'">
                                </td>
                                <td>
                                    <select name="expenseData['.$row->id.'][gst_per'.$row->map_ind.']" id="gst_per'.$row->id.'" class="form-control selectBox">
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