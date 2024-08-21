<html>
    <body>
        <div class="row">
            <div class="col-12">
				<table class="table" style="border-bottom:1px solid #036aae;">
					<tr>
						<td class="org_title text-uppercase text-center" style="font-size:1.3rem;width:100%"><?= $companyData->company_name?></td>
					</tr>
				</table>
				<table class="table" style="border-bottom:1px solid #036aae;margin-bottom:2px;">
					<tr>
						<td class="org-address text-center" style="font-size:13px;"><?= $companyData->company_address?></td>
					</tr>
				</table>

				<table class="table bg-light-grey">
					<tr class="" style="letter-spacing: 2px;font-weight:bold;padding:2px !important; border-bottom:1px solid #000000;">
						<td style="width:33%;" class="fs-18 text-left"></td>
						<td style="width:33%;" class="fs-18 text-center">Sales Order</td>
						<td style="width:33%;" class="fs-18 text-right"></td>
					</tr>
				</table>               
                
                <table class="table item-list-bb fs-22" style="margin-top:5px;">
                    <tr>
                        <td rowspan="4" style="width:65%;vertical-align:top;">
                            <b>M/S. <?=(!empty($partyData->party_name)) ? $partyData->party_name : "";?></b><br>
                            <?=(!empty($dataRow->ship_address) ? $dataRow->ship_address ." - ".$dataRow->ship_pincode : '')?><br>
                            <b>Kind. Attn. : <?=(!empty($partyData->contact_person)) ? $partyData->contact_person : '';?></b> <br>
                            Contact No. : <?= (!empty($partyData->contact_phone)) ? $partyData->contact_phone : ''?><br>
                            Email : <?= (!empty($partyData->party_email)) ? $partyData->party_email : '';?><br><br>
                            GSTIN : <?=$dataRow->gstin?>
                        </td>
                        <td>
                            <b>SO. No.</b>
                        </td>
                        <td>
                            <?=$dataRow->trans_number?>
                        </td>
                    </tr>
                    <tr>
				        <th class="text-left">SO Date</th>
                        <td><?=formatDate($dataRow->trans_date)?></td>
                    </tr>
                    <tr>
						<td colspan="2">
                            <b>LUT NO. :</b><br>
                            <b>Transport :</b><br>
                            <b>L.R. No. :</b><br>
                            <b>L.R. Date :</b><br>
                            <b>Vehicle No. :</b><br>
                            <b>Cases :</b>
                        </td>
                    </tr>
                </table>
                
                <table class="table item-list-bb" style="margin-top:10px;">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th class="text-left" style="width:30%">Item Description</th>
							<th style="width:8%;">Box / Master</th>
							<th style="width:10%;">Loose</th>
							<th style="width:8%;">Qty</th>
							<th style="width:10%;">Rate</th>
                            <th style="width:10%;">Discount Amount</th>
                            <th style="width:7%;">Rate </th>
							<th style="width:12%;">Taxable Amount</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$i=1;$totalQty = 0;$taxable_amount=0;$sub_total=0;$gstAmt=0;$loose=0;$boxMaster =0;$totalBox =0;$reduceGst = 0;$addGst = 0;
							if(!empty($dataRow->itemList)):
								foreach($dataRow->itemList as $row):
                                    $boxMaster = 0;$loose = 0;
									/* if($row->order_unit == 1){
										$loose = $row->qty;
										$boxMaster = 0;
									}else{
										$boxMaster = round($row->qty / $row->order_unit);
										$loose = 0;
									} */
									
									$price = $row->price - $row->disc_amount;

									$item_remark=(!empty($row->item_remark))?'<br><small>Remark:.'.$row->item_remark.'</small>':'';
									
									echo '<tr>';
										echo '<td class="text-center">'.$i++.'</td>';
										echo '<td>'.$row->item_name.'</td>';
										echo '<td class="text-right">'.$boxMaster.' </td>';
										echo '<td class="text-right">'.floatval($loose).' </td>';
										echo '<td class="text-right">'.floatval($row->qty).' </td>';
										echo '<td class="text-center">'.$row->price.' <small>'.$row->uom.'</small></td>';
										echo '<td class="text-center">'.$row->disc_amount.'</td>';
										echo '<td class="text-center">'.$price.'</td>';
										echo '<td class="text-right">'.floatval($row->taxable_amount).'</td>';
									echo '</tr>';
									$totalBox += $boxMaster;
									$totalQty += $row->qty;
									$sub_total += $row->taxable_amount;
								endforeach;
							endif;

                            $rowspan = 1;$totalExpAmount = 0;$expRow = $expHtml = '';
                            if(!empty($expenseList)):                       
                                $salesExpenseData = $dataRow->expenseData;
                                foreach($expenseList as $row):
        
                                    $expPer = $expAmount = $expGstPer = 0;
                                    $expGstAmt = $expNetAmt = 0;
                                    if (!empty($salesExpenseData)) :
                                        $expPerKey = 'per'.$row->map_ind;
                                        $expAmountKey = 'amount'.$row->map_ind;
                                        $expGstPerKey = 'gst_per'.$row->map_ind;

                                        $expPer = $salesExpenseData->{$expPerKey};
                                        $expAmount = $salesExpenseData->{$expAmountKey};
                                        $expGstPer = $salesExpenseData->{$expGstPerKey};

                                        if(floatval($expGstPer) > 0 && floatval($expAmount) > 0):
                                            $expGstAmt = round(((floatval($expAmount) * floatval($expGstPer)) / 100),2);
                                            $dataRow->gst_amount += ($expGstAmt * $row->p_or_m);
                                        endif;

                                        $totalExpAmount += $expAmount;
                                    endif;

									if($expAmount <> 0):
										if($rowspan == 1):
											$expRow = '<td class="text-right" colspan="3">'.$row->exp_name.'</td>
												<td class="text-right">'.sprintf('%.2f',$expAmount).'</td>';
										else:
											$expHtml .= '<tr>
												<td class="text-right" colspan="3">'.$row->exp_name.'</td>
												<td class="text-right">'.sprintf('%.2f',$expAmount).'</td>
											</tr>';
										endif;
										$rowspan++;
									endif;

                                endforeach;
                            endif;							
							
							$grand_total = round( ( $sub_total + $totalExpAmount + $dataRow->gst_amount), 2);
						?>
						<tr>
							
							<th colspan="2" class="text-right">Total Box.</th>
							<th class="text-right"><?=floatval($totalBox)?></th>
							<th  class="text-right">Total Qty.</th>
							<th class="text-right"><?=floatval($totalQty)?></th>
							<th colspan="3" class="text-right">Total Amount</th>
							<th class="text-right"><?=floatval($sub_total)?></th>
						</tr>
						<tr>
							<th class="text-left" colspan="5" rowspan="<?=$rowspan?>"> Amount In Words : <?=numToWordEnglish($grand_total)?> </th>
							<?php
                                if(!empty($expRow)):
                                    echo $expRow;
                                else:
                                    echo '<th class="text-right" colspan="3"> GST Amount </th>
							            <th class="text-right">'.sprintf('%.2f',$dataRow->gst_amount).'</th>';
                                endif;
                            ?>
						</tr>
						<?=$expHtml?>
                        <?php
                            if(!empty($expRow)):
                        ?>
						<tr>
							<th class="text-right" colspan="3"> GST Amount </th>
							<th class="text-right"> <?=sprintf('%.2f',$dataRow->gst_amount)?> </th>
						</tr>
                        <?php
                            endif;
                        ?>
						<tr>
						    <th class="text-left" colspan="5">  </th>
						    <th class="text-right" colspan="3"> Grand Total </th>
							<th class="text-right"> <?=sprintf('%.2f',$grand_total)?> </th>
						</tr>
					</tbody>
                </table>
				<!-- <table class="table top-table" style="margin-top:10px;">
                    <tr>
                        <th class="text-left">Terms & Conditions :-</th>
                    </tr>
                   <tr>
						<td>
							1: The price quoted above is for Ex-Factory <br>
							2: Our offer is valid for 3 days from the date of hereof. Subsequent, <br>
							3: This quotation will be valid if payment is made in valid offer time. <br>
							4: This quotation is also subject to our general conditions of sale herewith.
						</td>
				   </tr>
                </table> -->
				<htmlpagefooter name="lastpage">
					<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
						<tr>
							<td style="width:50%;" rowspan="4"></td>
							<th colspan="2">For, <?=$companyData->company_name?></th>
						</tr>
						<tr>
							<td style="width:25%;" class="text-center"><?=$dataRow->prepareBy?></td>
							<td style="width:25%;" class="text-center"><?=$dataRow->approveBy?>'</td>
						</tr>
						<tr>
							<td style="width:25%;" class="text-center"><b>Prepared By</b></td>
							<td style="width:25%;" class="text-center"><b>Authorised By</b></td>
						</tr>
					</table>
					<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
						<tr>
							<td style="width:25%;">SO No. & Date : <?=$dataRow->trans_number.' ['.formatDate($dataRow->trans_date).']'?></td>
							<td style="width:25%;"></td>
							<td style="width:25%;text-align:right;">Page No. {PAGENO}/{nbpg}</td>
						</tr>
					</table>
                </htmlpagefooter>
				<sethtmlpagefooter name="lastpage" value="on" />
            </div>
        </div>        
    </body>
</html>