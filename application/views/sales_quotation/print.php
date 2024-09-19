<html>
    <head>
        <title>Quotation</title>
        <!-- Favicon icon -->
        <link rel="icon" type="image/png" sizes="16x16" href="<?=base_url();?>assets/images/favicon.png">
    </head>
    <body>
        <div class="row">
            <div class="col-12">
                <table>
                    <tr>
                        <td>
                            <?php if(!empty($letter_head)): ?>
                                <img src="<?=$letter_head?>" class="img">
                            <?php endif;?>
                        </td>
                    </tr>
                </table>

                <table class="table bg-light-grey">
                    <tr class="" style="letter-spacing: 2px;font-weight:bold;padding:2px !important; border-bottom:1px solid #000000;">
                        <td style="width:33%;" class="fs-18 text-left">
                            GSTIN: <?=$companyData->company_gst_no?>
                        </td>
                        <td style="width:33%;" class="fs-18 text-center">Quotation</td>
                        <td style="width:33%;" class="fs-18 text-right"></td>
                    </tr>
                </table>
                
                <table class="table item-list-bb fs-22" style="margin-top:5px;">
                    <tr>
                        <td style="width:60%; vertical-align:top;" rowspan="2">
                            <b>M/S. <?=$dataRow->party_name?></b><br>
                            <?=(!empty($dataRow->address) ? $dataRow->address ." - ".$dataRow->pincode : '')?><br><br>
                            <b>GSTIN</b> : <?=(!empty($partyData->gstin)) ? $partyData->gstin : ""?>
                        </td>
                        <td>
                            <b>Qtn. No. : <?=$dataRow->trans_number?></b>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:40%;">
                            <b>Qtn. Date : <?=formatDate($dataRow->trans_date)?></b>
                        </td>
                    </tr>
                </table>
                
                <table class="table item-list-bb" style="margin-top:10px;">
                    <thead>
                        <tr>
                            <th style="width:40px;">No.</th>
                            <th class="text-left">Description of Goods</th>
                            <th style="width:10%;">HSN/SAC</th>
                            <th style="width:60px;">Qty</th>
                            <th style="width:60px;">Unit</th>
                            <th style="width:60px;">Rate<br></th>
                            <th style="width:60px;">Disc(%)</th>
                            <th style="width:60px;">GST(%)</th>
                            <th style="width:110px;">Amount<br></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i=1; $totalQty=0; $migst=0; $mcgst=0; $msgst=0;
							$expenceData='';
							
                            if(!empty($dataRow->itemList)):
                                foreach($dataRow->itemList as $row):						
                                    echo '<tr>';
                                        echo '<td class="text-center">'.$i++.'</td>';
                                        echo '<td>'.$row->item_name.'</td>';
                                        echo '<td class="text-center">'.$row->hsn_code.'</td>';
                                        echo '<td class="text-right">'.floatVal($row->qty).'</td>';
                                        echo '<td class="text-right">'.$row->unit_name.'</td>';
                                        echo '<td class="text-right">'.floatVal($row->price).'</td>';
                                        echo '<td class="text-right">'.floatVal($row->disc_per).'</td>';
                                        echo '<td class="text-center">'.$row->gst_per.'</td>';
                                        echo '<td class="text-right">'.$row->taxable_amount.'</td>';
                                    echo '</tr>';
                                    
                                    $totalQty += $row->qty;
                                    if($row->gst_per > $migst){ $migst=$row->gst_per; $mcgst=($row->gst_per/2); $msgst=($row->gst_per/2); }
                                endforeach;
                            endif;

                            $blankLines = (10 - $i);
                            if($blankLines > 0):
                                for($j=1;$j<=$blankLines;$j++):
                                    echo '<tr>
                                        <td style="border-top:none;border-bottom:none;">&nbsp;</td>
                                        <td style="border-top:none;border-bottom:none;"></td>
                                        <td style="border-top:none;border-bottom:none;"></td>
                                        <td style="border-top:none;border-bottom:none;"></td>
                                        <td style="border-top:none;border-bottom:none;"></td>
                                        <td style="border-top:none;border-bottom:none;"></td>
                                        <td style="border-top:none;border-bottom:none;"></td>
                                        <td style="border-top:none;border-bottom:none;"></td>
                                        <td style="border-top:none;border-bottom:none;"></td>
                                    </tr>';
                                endfor;
                            endif;

                            $gstAmount = $dataRow->gst_amount;$totalExpAmount = $cgstAmount = $sgstAmount = $igstAmount = 0;			
							$rwspan= 0;$bfExpHtml = $afExpHtml = '';
                            $salesExpenseData = $dataRow->expenseData;
							if(!empty($expenseList)):                       
								foreach($expenseList as $row):
									$expPer = $expAmount = $expGstPer = "";
									if(!empty($salesExpenseData)):
										$expPerKey = 'per'.$row->map_ind;
										$expGstPerKey = 'gst_per'.$row->map_ind;
										$expAmountKey = 'amount'.$row->map_ind;
										$expPer = $salesExpenseData->{$expPerKey};
										$expAmount = $salesExpenseData->{$expAmountKey};
										$expGstPer = $salesExpenseData->{$expGstPerKey};

                                        if(!empty($expAmount)):
                                            if($expGstPer > 0):
                                                if($rwspan == 0):
                                                    $bfExpHtml .= '<td colspan="2" class="text-right">'.$row->exp_name.'</td>
                                                        <td class="text-right">'.$expAmount.'</td>';
                                                else:
                                                    $bfExpHtml .= '<tr>
                                                        <td colspan="2" class="text-right">'.$row->exp_name.'</td>
                                                        <td class="text-right">'.$expAmount.'</td>
                                                    </tr>';
                                                endif;

                                                $gstAmount += round((($expAmount * $expGstPer) /100),2);
                                                $totalExpAmount += $expAmount; 
                                                $rwspan++;
                                            endif;
                                        endif;
									endif;
								endforeach;
							endif;

                            $taxHtml = '';                            
                            if($companyData->state_code == $partyData->state_code):
                                $cgstAmount = $sgstAmount = round(($gstAmount / 2),2);

                                if($rwspan == 0):
                                    $taxHtml .= '<td colspan="2" class="text-right">CGST Amount</td>
                                        <td class="text-right">'.$cgstAmount.'</td>';
                                    $taxHtml .= '<tr>
                                        <td colspan="2" class="text-right">SGST Amount</td>
                                        <td class="text-right">'.$cgstAmount.'</td>
                                    </tr>';
                                else:
                                    $taxHtml .= '<tr>
                                        <td colspan="2" class="text-right">CGST Amount</td>
                                        <td class="text-right">'.$cgstAmount.'</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-right">SGST Amount</td>
                                        <td class="text-right">'.$cgstAmount.'</td>
                                    </tr>';
                                endif;
                                $rwspan += 2;
                            elseif($companyData->state_code != $partyData->state_code):
                                $igstAmount = $gstAmount;
                                if($rwspan == 0):
                                    $taxHtml .= '<td colspan="2" class="text-right">IGST Amount</td>
                                    <td class="text-right">'.$igstAmount.'</td>';
                                else:
                                    $taxHtml .= '<tr>
                                        <td colspan="2" class="text-right">IGST Amount</td>
                                        <td class="text-right">'.$igstAmount.'</td>
                                    </tr>';
                                endif;

                                $rwspan ++;
                            endif;
                            

                            if(!empty($expenseList)):                       
								foreach($expenseList as $row):
									$expPer = $expAmount = $expGstPer = "";
									if(!empty($salesExpenseData)):
										$expPerKey = 'per'.$row->map_ind;
										$expGstPerKey = 'gst_per'.$row->map_ind;
										$expPer = $salesExpenseData->{$expPerKey};
										$expAmount = $salesExpenseData->{'amount'.$row->map_ind};
										$expGstPer = $salesExpenseData->{$expGstPerKey};

                                        if(!empty($expAmount)):
                                            if($expGstPer <= 0):
                                                if($rwspan == 0):
                                                    $afExpHtml .= '<td colspan="2" class="text-right">'.$row->exp_name.'</td>
                                                        <td class="text-right">'.$expAmount.'</td>';
                                                else:
                                                    $afExpHtml .= '<tr>
                                                        <td colspan="2" class="text-right">'.$row->exp_name.'</td>
                                                        <td class="text-right">'.$expAmount.'</td>
                                                    </tr>';
                                                endif;

                                                $totalExpAmount += $expAmount;
                                                $rwspan++;
                                            endif;
                                        endif;
									endif;
								endforeach;
							endif;

                            $netAmount = $dataRow->taxable_amount + $gstAmount + $totalExpAmount;

                            $roundOffAmount = round((round($netAmount) - $netAmount),2);
                            $netAmount = round($netAmount);
                            
                            $fixRwSpan = (!empty($rwspan))?3:0;
                        ?>
                        <tr>
                            <th colspan="3" class="text-right">Total Qty.</th>
                            <th class="text-right"><?=floatval($totalQty)?></th>
                            <th></th>
                            <th></th>
                            <th colspan="2" class="text-right">Sub Total</th>
                            <th class="text-right"><?=sprintf('%.2f',$dataRow->taxable_amount)?></th>
                        </tr>
						<tr>
							<th class="text-left" colspan="6" rowspan="<?=$rwspan?>">
                                Amount In Words : <br><?=numToWordEnglish(sprintf('%.2f',$netAmount))?>
                            </th>

                            <?php if(empty($rwspan)): ?>
                                <th colspan="2" class="text-right">Round Off</th>
                                <td class="text-right"><?=sprintf('%.2f',$roundOffAmount)?></td>
                            <?php endif; ?>
						</tr>
                        <?=$bfExpHtml.$taxHtml.$afExpHtml?>
                        <tr>
                            <td class="text-left" colspan="6" rowspan="<?=$fixRwSpan?>">
                                <b>Note</b> : <?=$dataRow->remark?>
                            </td>	
                            
                            <?php if(empty($rwspan)): ?>
                                <th colspan="2" class="text-right">Grand Total</th>
                                <th class="text-right"><?=sprintf('%.2f',$netAmount)?></th>
                            <?php else: ?>
                                <th colspan="2" class="text-right">Round Off</th>
                                <td class="text-right"><?=sprintf('%.2f',$roundOffAmount)?></td>
                            <?php endif; ?>
                        </tr>
						<?php if(!empty($rwspan)): ?>
                        <tr>
                            <th colspan="2" class="text-right">Grand Total</th>
                            <th class="text-right"><?=sprintf('%.2f',$netAmount)?></th>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <table class="table top-table" style="margin-top:10px;">
                    <tr>
                        <th class="text-left">Terms & Conditions :-</th>
                    </tr>
                    <?php
						if(!empty($dataRow->conditions)):
							$terms = json_decode($dataRow->conditions);
							foreach($terms as $row):
								echo '<tr>';
									echo '<th class="text-left fs-11" style="width:140px;">'.$row->term_title.'</th>';
									echo '<td class=" fs-11"> : '.$row->condition.'</td>';
								echo '</tr>';
							endforeach;
						endif;
                    ?>
                </table>
                
                <htmlpagefooter name="lastpage">
                    <table class="table top-table" style="margin-top:0px;border-top:1px solid #545454;">
                        <tr>
                            <td style="width:50%;"></td>
                            <td style="width:20%;"></td>
                            <th class="text-center">For, <?=$companyData->company_name?></th>
                        </tr>
                        <tr>
                            <td colspan="3" height="40"></td>
                        </tr>
                        <tr>
                            <td><br>This is a computer-generated quotation.</td>
                            <td class="text-center"><?=$dataRow->created_name?><br>Prepared By</td>
                            <td class="text-center"><?=$dataRow->approved_name?><br>Authorised By</td>
                        </tr>
                    </table>
                    <table class="table top-table" style="margin-top:0px;border-top:1px solid #545454;">
						<tr>
							<td style="width:25%;">Qtn No. & Date : <?=$dataRow->trans_number.' ['.formatDate($dataRow->trans_date).']'?></td>
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
