<form>
    <div class="col-md-12">
        <div class="row">

            <div class="col-md-3 form-group">
                <label for="trans_number">SO. No.</label>
                <input type="text" name="trans_number" id="trans_number" class="form-control req" value="<?=(!empty($dataRow->trans_number))?$dataRow->trans_number:$trans_number?>" readonly>
            </div>

            <div class="col-md-3 form-group">
                <label for="trans_date">SO. Date</label>
                <input type="text" name="trans_date" id="trans_date" class="form-control req" value="<?=(!empty($dataRow->trans_date))?$dataRow->trans_date:date("Y-m-d")?>">
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
            <input type="hidden" id="id" value="">
            <input type="hidden" id="row_index" value="">
			<input type="hidden" id="ref_id" value="">

            <div class="col-md-6 form-group">
                <label for="item_id">Product Name</label>
                <select id="item_id" class="form-control selectBox itemDetails req" data-res_function="resItemDetail">
                    <option value="">Select Product Name</option>
                    <?=getItemListOption($itemList)?>
                </select>
            </div>

            <div class="col-md-2 form-group">
                <label for="order_unit">Order Unit</label>
                <select id="order_unit" class="form-control selectBox">
                    <option value="">Select Order Unit</option>
                </select>
            </div>

            <div class="col-md-2 form-group">
                <label for="qty">Quantity</label>
                <input type="text" id="qty" class="form-control floatOnly req" value="0">
            </div>

            <div class="col-md-2 form-group">
                <label for="price">Price</label>
                <input type="text" id="price" class="form-control floatOnly req calculateRow" value="0" readonly/>
                <input type="hidden" id="org_price" value="">
            </div>

            <div class="col-md-2 form-group">
                <label for="regular_disc">Disc. (%)</label>
                <input type="text" id="regular_disc" class="form-control floatOnly req calculateRow" value="0" />
            </div>

            <div class="col-md-2 form-group">
                <label for="kg_price">KG Price</label>
                <input type="text" id="kg_price" class="form-control floatOnly req calculateRow" value="0" />
            </div>
        </div>
    </div>
</form>