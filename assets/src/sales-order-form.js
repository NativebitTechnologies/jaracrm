var itemCount = 0;
var visibleColumns = ['item_name','qty','price','disc_amount','taxable_amount','item_remark'];
$(document).ready(function(){

    $(document).on('keyup change','.discCalculate',function(){
        var inputVal = $(this).val();
        $("#itemForm #disc_per, #itemForm #disc_amount").prop('readonly',false);

        if($(this).attr('id') == "disc_per" && inputVal != ""){
            $("#itemForm #disc_amount").val().prop('readonly',true);
            return false;
        }

        if($(this).attr('id') == "disc_amount" && inputVal != ""){
            $("#itemForm #disc_per").val().prop('readonly',true);
            return false;
        }
    });

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

            MasterAddRow('salesOrderItems',itemData,{editBtn:1,deleteBtn:1});

			var selectedItem = $('#itemForm #item_id option:selected');
			$.each($('.itemInput'),function(){ $(this).val(""); });

            $("#itemForm input:hidden").val('');
            $('#itemForm #row_index').val("");
            initSelectBox('cls','selectBox');
			
			setTimeout(function(){
				selectedItem.next().attr('selected', 'selected');
				initSelectBox('id','item_id');
				$('.itemDetails').trigger('change');
				setTimeout(function(){
					$("#itemForm #item_id").focus();
				},150);
			},100);			

			$("#itemForm #org_price").prop('readonly',true);
			$("#itemForm #price").prop('readonly',true);
        }
	});
});

function Edit(){

}

function Remove(){

}

function resItemDetail(response = ""){
    if(response != ""){
        var itemDetail = response.data.itemDetail;
        $("#itemForm #item_id").val(itemDetail.id);
        $("#itemForm #item_code").val(itemDetail.item_code);
        $("#itemForm #item_name").val(itemDetail.item_name);
        $("#itemForm #unit_name").val(itemDetail.unit_name);
		$("#itemForm #price").val(itemDetail.price);
		$("#itemForm #org_price").val(itemDetail.mrp);
		$("#itemForm #qty").val(0);
        $("#itemForm #hsn_code").val(itemDetail.hsn_code);
        $("#itemForm #gst_per").val(parseFloat(itemDetail.gst_per));
    }else{
		$("#itemForm #item_id").val("");
        $("#itemForm #item_code").val("");
        $("#itemForm #item_name").val("");
        $("#itemForm #unit_name").val("");
		$("#itemForm #price").val("");
		$("#itemForm #org_price").val("");
		$("#itemForm #qty").val(0);
        $("#itemForm #hsn_code").val("");
        $("#itemForm #gst_per").val(0);
    }
	initSelectBox('cls','selectBox');
}