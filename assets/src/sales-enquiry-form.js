var itemCount = 0;
var visibleColumns = ['item_name','qty','uom','item_remark'];
var itemHiddenInputs = ['id','is_temp_item','item_id','item_name','uom','qty','item_remark'];
var soItemBox = null;
$(document).ready(function(){
    initSoItemBox();soItemBox.setValue("");
    $(".itemInputBox").hide();

    $(document).on('change','#is_temp_item',function(){
        if($(this).val() == 0){
            $(".itemInputBox").hide();
            $(".selectItem").show();
        }else{
            $(".itemInputBox").show();
            $(".selectItem").hide();
        }
    });

    $(document).on('click', '.addItem', function (e) {
        e.stopImmediatePropagation();
        e.preventDefault();

		var formData = {};
        $.each($(".itemInput"),function(i, v) {
            formData[$(this).attr("id")] = $(this).val();
        }); 
		
        $("#itemForm .error").html("");

        if (formData.item_id == "" && formData.is_temp_item == 0) {
			$(".item_id").html("Item Name is required.");
		}
        if (formData.item_name == "" && formData.is_temp_item == 1) {
			$(".item_name").html("Item Name is required.");
		}
        if (formData.qty == "" || parseFloat(formData.qty) == 0) {
            $(".qty").html("Qty is required.");
        }

        var errorCount = $('#itemForm .error:not(:empty)').length;

		if (errorCount == 0) {
			formData.id = formData.trans_id;

            MasterAddRow('salesItems',formData,{editBtn:1,deleteBtn:1});

			$.each($('.itemInput'),function(){ $(this).val(""); });

            $("#itemForm input:hidden").val('');
            $('#itemForm #row_index').val("");
            $('#itemForm #uom').val("");
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
        $("#itemForm #uom").val(data.uom);
    });

    if(data.is_temp_item == 0){
        $(".itemInputBox").hide();
        $(".selectItem").show();
    }else{
        $(".itemInputBox").show();
        $(".selectItem").hide();
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
		$("#tempItem").html('<tr id="noData"><td colspan="6" align="center">No data available in table</td></tr>');
	}

	claculateColumn();
}

function resItemDetail(response = ""){
    if(response != ""){
        var itemDetail = response.data.itemDetail;
        $("#itemForm #item_name").val(itemDetail.item_name+' '+itemDetail.category_name);
        $("#itemForm #uom").val(itemDetail.unit_name);
		$("#itemForm #qty").val(0);

        $("#itemForm #uom").html(response.data.orderUnitList);
    }else{
        $("#itemForm #item_name").val("");
        $("#itemForm #uom").val('<option value="">Select Order Unit</option>');
		$("#itemForm #qty").val(0);
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