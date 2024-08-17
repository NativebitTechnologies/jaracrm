<form>
    <div class="col-md-12">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>SQ. No.</th>
                            <th>SQ. Date</th>
                            <th>Item Name</th>
                            <th>Pending Qty.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i=1;
                            foreach($itemList as $row):
                                $row->from_vou_name = $row->vou_name;
                                $row->ref_id = $row->id;
                                
                                unset($row->id,$row->vou_name);
                                $row->id = $row->row_index = "";

                                $qtyDiscAmt = (floatval($row->disc_amount) > 0)?round((floatval($row->disc_amount) / floatval($row->qty)),2):0;
                                $row->qty = $row->pending_qty;
                                $row->taxable_amount = $row->amount = ($row->qty * $row->price);
                                $row->disc_amount = round(($row->qty * $qtyDiscAmt),2);
                                $row->taxable_amount -= $row->disc_amount;
                                $row->gst_amount = $row->igst_amount = (floatval($row->gst_per) > 0)?round((($row->taxable_amount * $row->gst_per) / 100),2):0;
                                $row->cgst_amount = $row->sgst_amount = (floatval($row->gst_amount) > 0)?round(($row->gst_amount / 2),2):0;
                                $row->net_amount = round(($row->taxable_amount + $row->gst_amount),2);

                                $jsonData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                                echo "<tr>
                                    <td class='text-center'>
                                        <input type='checkbox' id='md_checkbox_" . $i . "' class='filled-in chk-col-success createItem create".$row->trans_main_id."' data-main_id='".$row->trans_main_id."' data-row='".$jsonData."' ><label for='md_checkbox_" . $i . "' class='mr-3 check" . $row->ref_id . "'></label>
                                    </td>
                                    <td>".$row->trans_number."</td>
                                    <td>".formatDate($row->trans_date)."</td>
                                    <td>".$row->item_name."</td>
                                    <td>".floatval($row->qty)."</td>
                                </tr>";
                                $i++;
                            endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>