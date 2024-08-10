<form  enctype="multipart/form-data">
    <div class="col-md-12">
        <div class="hiddenInputs">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
            <input type="hidden" name="entry_type" id="entry_type" value="<?=(!empty($dataRow->entry_type))?$dataRow->entry_type:"1"?>">
            <input type="hidden" name="from_entry_type" id="from_entry_type" value="<?=(!empty($dataRow->from_entry_type))?$dataRow->from_entry_type:""?>">
            <input type="hidden" name="from_ref_id" id="from_ref_id" value="<?=(!empty($dataRow->from_ref_id))?$dataRow->from_ref_id:""?>">
            <input type="hidden" name="trans_no" id="trans_no" value="<?=(!empty($dataRow->trans_no))?$dataRow->trans_no:$trans_no?>">
            <input type="hidden" name="trans_prefix" id="trans_prefix" value="<?=(!empty($dataRow->trans_prefix))?$dataRow->trans_prefix:$trans_prefix?>">
        </div>

        <div class="row">

            <div class="col-md-3 form-group">
                <label for="trans_number">SO. No.</label>
                <input type="text" name="trans_number" id="trans_number" class="form-control req" value="<?=(!empty($dataRow->trans_number))?$dataRow->trans_number:$trans_number?>" readonly>
            </div>

            <div class="col-md-3 form-group">
                <label for="trans_date">SO. Date</label>
                <input type="date" name="trans_date" id="trans_date" class="form-control req" value="<?=(!empty($dataRow->trans_date))?$dataRow->trans_date:date("Y-m-d")?>">
            </div>

            <div class="col-md-6 form-group">
                <label for="party_id">Customer Name</label>
                <select name="party_id" id="party_id" class="form-control selectBox partyDetails1 req">
                    <option value="">Select Customer</option>
                    <?=getPartyListOption($partyList,( (!empty($dataRow->party_id))?$dataRow->party_id:"" ))?>
                </select>
            </div>

            <div class="col-md-3 form-group">
                <label for="doc_no">Referance</label>
                <input type="text" name="doc_no" id="doc_no" class="form-control" value="<?=(!empty($dataRow->doc_no))?$dataRow->doc_no:""?>">
            </div>

            <div class="col-md-3 form-group">
                <label for="order_file">Attachment</label>
                <div class="input-group">
                    <input type="file" name="order_file" id="order_file" <?=(!empty($dataRow->order_file)?'style="width:70%"':'')?> class="form-control">
                    <div class="input-group-append">
                        <?php if(!empty($dataRow->order_file)): ?>
                            <a href="<?=base_url('assets/uploads/sales_order/'.$dataRow->order_file)?>" class="btn btn-outline-primary" download><?=getIcon('download_cloud')?></a>
                        <?php endif; ?>
                    </div>                                        
                </div>                           
            </div>

        </div>

        <hr>

        <div class="row" id="itemForm">
            <input type="hidden" id="trans_id" class="itemInput" value="">
            <input type="hidden" id="row_index" class="itemInput" value="">
			<input type="hidden" id="ref_id" class="itemInput" value="">
			<input type="hidden" id="item_code" class="itemInput" value="">
			<input type="hidden" id="item_name" class="itemInput" value="">
			<input type="hidden" id="gst_per" class="itemInput" value="">
			<input type="hidden" id="hsn_code" class="itemInput" value="">

            <div class="col-md-6 form-group">
                <label for="item_id">Product Name</label>
                <select id="item_id" class="form-control selectBox itemDetails req itemInput" data-res_function="resItemDetail">
                    <option value="">Select Product Name</option>
                    <?=getItemListOption($itemList)?>
                </select>
            </div>

            <div class="col-md-2 form-group">
                <label for="unit_name">Order Unit</label>
                <select id="unit_name" class="form-control selectBox itemInput">
                    <option value="">Select Order Unit</option>
                </select>
            </div>

            <div class="col-md-2 form-group">
                <label for="qty">Quantity</label>
                <input type="text" id="qty" class="form-control floatOnly req itemInput" value="0">
            </div>

            <div class="col-md-2 form-group">
                <label for="price">Price</label>
                <input type="text" id="price" class="form-control floatOnly req itemInput" value="0" readonly/>
                <input type="hidden" id="org_price" value="">
            </div>

            <div class="col-md-2 form-group">
                <label for="disc_per">Disc. (%)</label>
                <input type="text" id="disc_per" class="form-control floatOnly req discCalculate itemInput" value="0" />
            </div>

            <div class="col-md-2 form-group">
                <label for="disc_amount">Disc. Amt.</label>
                <input type="text" id="disc_amount" class="form-control floatOnly req discCalculate itemInput" value="0" />
            </div>

            <div class="col-md-6 form-group">
                <label for="item_remark">Remark</label>
                <input type="text" id="item_remark" class="form-control itemInput" value="" />
            </div>

            <div class="col-md-2">
                <label for="">&nbsp;</label>
				<button type="button" class="btn btn-info btn-block waves-effect float-right addOrderItem"><i class="fa fa-plus"></i> Add </button>
			</div>
        </div>

        <hr>

        <div class="row">
            <div class="error itemData"></div>
            <div class="table-responsive">
                <table id="salesOrderItems" class="table table-striped table-borderless">
                    <thead class="thead-info">
                        <tr>
                            <th style="width:5%;">#</th>
                            <th style="width:25%;">Item Name</th>
                            <th style="width:15%;">Qty.</th>
                            <th style="width:15%;">Price</th>
                            <th style="width:15%;">Disc. Amount</th>
                            <th style="width:15%;">Taxable Amount</th>
                            <th style="width:25%;">Remark</th>
                            <th class="text-center" style="width:15%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tempItem">
                        <tr id="noData">
                            <td colspan="8" class="text-center">No data available in table</td>
                        </tr>
                    </tbody>
                    <tfoot class="thead-info">
                        <tr>
                            <th colspan="2">Total</th>
                            <th id="totalQty">0</th>
                            <th></th>
                            <th id="totalDiscAmt">0</th>
                            <th id="totalTaxableAmt">0</th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <hr>

        <div class="row" id="expenseList">
            <?php $this->load->view("includes/sales_expense_trans",['expenseList'=>$expenseList,'salesExpenseData'=>((!empty($dataRow->expenseData))?$dataRow->expenseData:[])])?>
        </div>

        <div class="row">
            <div class="col-md-12 form-group">
                <label for="remark">Remark</label>
                <input type="text" name="remark" id="remark" class="form-control" value="<?=(!empty($dataRow->remark))?$dataRow->remark:""?>">
            </div>
        </div>
    </div>
</form>

<script src="<?=base_url();?>assets/src/sales-order-form.js"></script>
<script src="<?=base_url();?>assets/src/calcualte.js"></script>

<?php
if(!empty($dataRow->itemList)):
    foreach($dataRow->itemList as $row):
        $row->row_index = "";
        $row->gst_per = floatVal($row->gst_per);
        $row->item_name = $row->item_name.' '.$row->category_name;
        echo '<script>MasterAddRow("salesOrderItems",'.json_encode($row).',{editBtn:1,deleteBtn:1});</script>';
    endforeach;
endif;
?>