<form>
<div class="col-md-12">
        <div class="hiddenInputs">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
            <input type="hidden" name="trans_no" id="trans_no" value="<?=(!empty($dataRow->trans_no))?$dataRow->trans_no:$trans_no?>">
            <input type="hidden" name="trans_prefix" id="trans_prefix" value="<?=(!empty($dataRow->trans_prefix))?$dataRow->trans_prefix:$trans_prefix?>">
        </div>

        <div class="row">
            <div class="col-md-3 form-group">
                <label for="trans_number">Entry No.</label>
                <input type="text" name="trans_number" id="trans_number" class="form-control req" value="<?=(!empty($dataRow->trans_number))?$dataRow->trans_number:$trans_number?>" readonly>
            </div>

            <div class="col-md-3 form-group">
                <label for="trans_date">Entry Date</label>
                <input type="date" name="trans_date" id="trans_date" class="form-control req" value="<?=(!empty($dataRow->trans_date))?$dataRow->trans_date:date("Y-m-d")?>">
            </div>

            <div class="col-md-6 form-group">
                <label for="party_id">Customer Name</label>
                <select name="party_id" id="party_id" class="form-control selectBox partyDetails1 req">
                    <option value="">Select Customer</option>
                    <?=getPartyListOption($partyList,( (!empty($dataRow->party_id))?$dataRow->party_id:"" ))?>
                </select>
            </div>            
        </div>

        <hr>

        <div class="row" id="itemForm">
            <input type="hidden" id="trans_id" class="itemInput" value="">
            <input type="hidden" id="row_index" class="itemInput" value="">

            <div class="col-md-2 form-group">
                <label for="is_temp_item">Product Type</label>
                <select id="is_temp_item" class="form-control itemInput">
                    <option value="0">Existing</option>
                    <option value="1">New</option>
                </select>
            </div>

            <div class="col-md-6 form-group selectItem">
                <label for="item_id">Product Name</label>
                <select id="item_id" class="form-control itemDetails req itemInput" data-res_function="resItemDetail">
                    <option value="">Select Product Name</option>
                    <?=getItemListOption($itemList)?>
                </select>
            </div>

            <div class="col-md-6 form-group itemInputBox">
                <label for="item_name">Product Name</label>
                <input type="text" id="item_name" class="form-control req itemInput" value="">
            </div>

            <div class="col-md-2 form-group">
                <label for="uom">Order Unit</label>
                <select id="uom" class="form-control itemInput">
                    <option value="">Select Order Unit</option>
                </select>
            </div>

            <div class="col-md-2 form-group">
                <label for="qty">Quantity</label>
                <input type="text" id="qty" class="form-control floatOnly req itemInput" value="0">
            </div>

            <div class="col-md-10 form-group">
                <label for="item_remark">Remark</label>
                <input type="text" id="item_remark" class="form-control itemInput" value="" />
            </div>

            <div class="col-md-2">
                <label for="">&nbsp;</label>
				<button type="button" class="btn btn-info btn-block waves-effect float-right addItem"><i class="fa fa-plus"></i> Add </button>
			</div>
        </div>

        <hr>

        <div class="row">
            <div class="error itemData"></div>
            <div class="table-responsive">
                <table id="salesItems" class="table table-striped dataTable dt-table-hover border border-grey">
                    <thead class="thead-info">
                        <tr>
                            <th style="width:5%;">#</th>
                            <th style="width:25%;">Item Name</th>
                            <th style="width:15%;">Qty.</th>
                            <th style="width:15%;">UOM</th>
                            <th style="width:25%;">Remark</th>
                            <th class="text-center" style="width:15%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tempItem">
                        <tr id="noData">
                            <td colspan="6" class="text-center">No data available in table</td>
                        </tr>
                    </tbody>
                    <tfoot class="thead-info">
                        <tr>
                            <th colspan="2">Total</th>
                            <th id="totalQty">0</th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
</form>

<script src="<?=base_url();?>assets/src/sales-enquiry-form.js?v<?=time()?>"></script>
<script src="<?=base_url();?>assets/src/calcualte.js?v<?=time()?>"></script>

<?php
if(!empty($dataRow->itemList)):
    foreach($dataRow->itemList as $row):
        $row->row_index = "";
        $row->item_name = $row->item_name.' '.$row->category_name;
        echo '<script>MasterAddRow("salesItems",'.json_encode($row).',{editBtn:1,deleteBtn:1});</script>'; 
    endforeach;
endif;
?>