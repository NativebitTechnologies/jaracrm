var itemCount = 0;
var visibleColumns = ['item_name','qty','price','disc_amount','taxable_amount','item_remark'];
var itemHiddenInputs = ['id','ref_id','item_id','uom','qty','price','disc_per','disc_amount','amount','taxable_amount','gst_per','gst_amount','net_amount','item_remark'];
var soItemBox = null;

$(document).ready(function(){
    initSoItemBox();soItemBox.setValue("");

    $(document).on('click', '.addOrderItem', function () {

		var formData = {};
        $.each($(".itemInput"),function(i, v) {
            formData[$(this).attr("id")] = $(this).val();
        }); 
		
        $("#itemForm .error").html("");

        if (formData.item_id == "") {
			$(".item_id").html("Item Name is required.");
		}
        if (formData.qty == "" || parseFloat(formData.qty) == 0) {
            $(".qty").html("Qty is required.");
        }
        if (formData.price == "" || parseFloat(formData.price) == 0) {
            $(".price").html("Price is required.");
        }

        var errorCount = $('#itemForm .error:not(:empty)').length;

		if (errorCount == 0) {
			formData.id = formData.trans_id;
            var itemData = calculateItem(formData);

            MasterAddRow('salesItems',itemData,{editBtn:1,deleteBtn:1});

			var selectedItem = $('#itemForm #item_id option:selected');
			$.each($('.itemInput'),function(){ $(this).val(""); });

            $("#itemForm input:hidden").val('');
            $('#itemForm #row_index').val("");
            $('#itemForm #unit_name').val("");
            soItemBox.setValue("");
        }
	});
});

function Edit(data, button){
    var row_index = $(button).closest("tr").index();

	$.each(data, function (key, value) {$("#itemForm #" + key).val(value);});
	
    soItemBox.setValue(data.item_id);
	$("#itemForm #trans_id").val(data.id);
	$("#itemForm #row_index").val(row_index);

    $.ajax({
        url : base_url + controller + '/getItemOrderUnits',
        type : 'post',
        data : {item_id : data.item_id},
        dataType : 'json'
    }).done(function(res){
        $("#itemForm #uom").html(res.data.orderUnitList);
        $("#itemForm #uom").val(data.unit_name);
    });

    $("#itemForm #disc_per, #itemForm #disc_amount").prop('readonly',false);
    if(parseFloat(data.disc_per) > 0){
        $("#itemForm #disc_amount").val("").prop('readonly',true);
    }else if(parseFloat(data.disc_amount) > 0){
        $("#itemForm #disc_per").val("").prop('readonly',true);
    }
}

function Remove(button){
    var tableId = "salesItems";
	//Determine the reference of the Row using the Button.
	var row = $(button).closest("TR");
	var table = $("#"+tableId)[0];
	table.deleteRow(row[0].rowIndex);
	$('#'+tableId+' tbody tr td:nth-child(1)').each(function (idx, ele) {
		ele.textContent = idx + 1;
	});
	var countTR = $('#'+tableId+' tbody tr:last').index() + 1;
	if (countTR == 0) {
		$("#tempItem").html('<tr id="noData"><td colspan="8" align="center">No data available in table</td></tr>');
	}

	claculateColumn();
}

function resItemDetail(response = ""){
    if(response != ""){
        var itemDetail = response.data.itemDetail;
        /* $("#itemForm #item_id").val(itemDetail.id); */
        //soItemBox.setValue(itemDetail.id);
        $("#itemForm #item_code").val(itemDetail.item_code);
        $("#itemForm #item_name").val(itemDetail.item_name+' '+itemDetail.category_name);
        $("#itemForm #uom").val(itemDetail.unit_name);
		$("#itemForm #price").val(itemDetail.price);
		$("#itemForm #mrp").val(itemDetail.mrp);
		$("#itemForm #qty").val(0);
        $("#itemForm #hsn_code").val(itemDetail.hsn_code);
        $("#itemForm #gst_per").val(parseFloat(itemDetail.gst_per));

        $("#itemForm #uom").html(res.data.orderUnitList);
    }else{
		//$("#itemForm #item_id").val("");
        soItemBox.setValue('');
        $("#itemForm #item_code").val(""); 
        $("#itemForm #item_name").val("");
        $("#itemForm #uom").val('<option value="">Select Order Unit</option>');
		$("#itemForm #price").val("");
		$("#itemForm #mrp").val("");
		$("#itemForm #qty").val(0);
        $("#itemForm #hsn_code").val("");
        $("#itemForm #gst_per").val(0);
    }
}
function initSoItemBox(){
    soItemBox = new vanillaSelectBox("#item_id", {
        "keepInlineStyles":true,
        "maxHeight": 200,
        "search": true,
        "placeHolder": "Select..."
    });
}