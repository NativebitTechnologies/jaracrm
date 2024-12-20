var itemCount = 0;

$(document).ready(function(){
    $(document).on('click','.createEntry',function(){
        var data = $(this).data();
        var party_id = $('#'+data.party_input).val();
		var party_name = $('#'+data.party_input+' :selected').text();
        var controllerName = data.controller || controller;
        var call_function = data.call_function;
		$('.party_id').html("");

        if (party_id != "" || party_id != 0) {
			$.ajax({
				url: base_url + controllerName + '/' + call_function,
				type: 'post',
				data: { party_id: party_id }
			}).done(function(response){
                $("#create-voucher-modal").modal("show");
                $("#create-voucher-modal").css({'z-index':(zindex + 1),'overflow':'auto'});
                $('#create-voucher-modal .modal-body').html('');
                $('#create-voucher-modal .modal-title').html("Carete Voucher [ Party Name : "+party_name+" ]");
                $('#create-voucher-modal .modal-body').html(response);
                $('#create-voucher-modal .modal-body form').attr('id',"createVoucherForm");
                $('#create-voucher-modal .modal-footer .btn-save').html('Create');
                $("#create-voucher-modal .modal-footer .btn-save").attr('onclick',"createVoucher();");
            });
		} else {
			$('.party_id').html("Party is required.");
		}	
    });
    
    $(document).on('click','.createItem',function(){        
		var main_id = $(this).data('main_id') || 0;
		if(main_id && $(this).prop('checked') == true){
            $(".create"+main_id).prop('checked',true);
		}
	});

    $(document).on('keyup change','.discCalculate',function(){
        var inputVal = $(this).val();        

        if($(this).attr('id') == "disc_per" && parseFloat(inputVal) > 0){
            $("#itemForm #disc_amount").val("").prop('readonly',true);
            return false;
        }else if($(this).attr('id') == "disc_amount" && parseFloat(inputVal) > 0){
            $("#itemForm #disc_per").val("").prop('readonly',true);
            return false;
        }else if($("#itemForm #disc_per").val() == "" && $("#itemForm #disc_amount").val() == ""){
            $("#itemForm #disc_per, #itemForm #disc_amount").prop('readonly',false);
        }
    });

    $(document).on('keyup change','.calculateExpense',function(){
        calculateExpense(($(this).data('row_id') || ""));
    });
	
	var numberOfChecked = $('.termCheck:checkbox:checked').length;
	$("#termsCounter").html(numberOfChecked);
	$(document).on("click", ".termCheck", function () {
		var id = $(this).data('rowid');
		var numberOfChecked = $('.termCheck:checkbox:checked').length;
		$("#termsCounter").html(numberOfChecked);
		if ($("#md_checkbox" + id).attr('check') == "checked") {
			$("#md_checkbox" + id).attr('check', '');
			$("#md_checkbox" + id).removeAttr('checked');
			$("#term_id" + id).attr('disabled', 'disabled');
			$("#term_title" + id).attr('disabled', 'disabled');
			$("#condition" + id).attr('disabled', 'disabled');
		} else {
			$("#md_checkbox" + id).attr('check', 'checked');
			$("#term_id" + id).removeAttr('disabled');
			$("#term_title" + id).removeAttr('disabled');
			$("#condition" + id).removeAttr('disabled');
		}
	});
});

function calculatePrice(postData,returnType = "price"){
	if(returnType == "price" && parseFloat(postData.org_price) > 0){
		/* Use if enter discount per. */
		var disc_amount = 0;
		if(parseFloat(postData.disc_per) > 0){
			disc_amount = parseFloat( (parseFloat(postData.org_price) * parseFloat(postData.disc_per) ) / 100 ).toFixed(3);
			postData.org_price = parseFloat( parseFloat(postData.org_price) - parseFloat(disc_amount) ).toFixed(3);
		}

		/* Use if enter discount amount */
		else if(parseFloat(postData.disc_amount) > 0){
			postData.org_price = parseFloat( parseFloat(postData.org_price) - parseFloat(postData.disc_amount) ).toFixed(3);
		}

		var new_price = postData.org_price;

		if(parseFloat(postData.gst_per) > 0){
			var gstReverse = parseFloat(( ( parseFloat(postData.gst_per) + 100 ) / 100 )).toFixed(3);
			new_price = parseFloat( parseFloat(postData.org_price) / parseFloat(gstReverse) ).toFixed(3);
			disc_amount = parseFloat( parseFloat(disc_amount) / parseFloat(gstReverse) ).toFixed(3);
			new_price = parseFloat( parseFloat(new_price) + parseFloat(disc_amount) ).toFixed(3);
		}
		return new_price;
	}

	return 0;
}

function calculateItem(formData){
	formData.disc_per = (parseFloat(formData.disc_per) > 0)?formData.disc_per:0;
	var qty = (formData.strip_qty > 0)?formData.strip_qty:formData.qty;
	var amount = 0; var taxable_amount = 0; var disc_amt = 0; var igst_amt = 0;
	var cgst_amt = 0; var sgst_amt = 0; var net_amount = 0; 
	var cgst_per = 0; var sgst_per = 0; var igst_per = 0;

	if (parseFloat(formData.disc_per) > 0) {
        amount = parseFloat(parseFloat(qty) * parseFloat(formData.price)).toFixed(2);
		disc_amt = parseFloat((amount * parseFloat(formData.disc_per)) / 100).toFixed(2);
		taxable_amount = parseFloat(amount - disc_amt).toFixed(2);
    }else if(parseFloat(formData.disc_amount) > 0){
        amount = parseFloat(parseFloat(qty) * parseFloat(formData.price)).toFixed(2);
        disc_amt = parseFloat(formData.disc_amount).toFixed(2);
        taxable_amount = parseFloat(amount - disc_amt).toFixed(2);
    }else{
        taxable_amount = amount = parseFloat(parseFloat(qty) * parseFloat(formData.price)).toFixed(3);
    }

	formData.gst_per = igst_per = parseFloat(formData.gst_per);
	formData.gst_amount = igst_amt = parseFloat((igst_per * taxable_amount) / 100).toFixed(3);

	cgst_per = parseFloat(parseFloat(igst_per) / 2).toFixed(2);
	sgst_per = parseFloat(parseFloat(igst_per) / 2).toFixed(2);

	cgst_amt = parseFloat((cgst_per * taxable_amount) / 100).toFixed(3);
	sgst_amt = parseFloat((sgst_per * taxable_amount) / 100).toFixed(3);

	net_amount = parseFloat(parseFloat(taxable_amount) + parseFloat(igst_amt)).toFixed(3);

	formData.qty = parseFloat(formData.qty).toFixed(2);
	formData.cgst_per = cgst_per;
	formData.cgst_amount = cgst_amt;
	formData.sgst_per = sgst_per;
	formData.sgst_amount = sgst_amt;
	formData.igst_per = igst_per;
	formData.igst_amount = igst_amt;
	formData.disc_amount = disc_amt;
	formData.amount = amount;
	formData.taxable_amount = taxable_amount;
	formData.net_amount = net_amount;

	return formData;
}


function MasterAddRow(tableId,data,actionBtn = {editBtn:1,deleteBtn:1}){
    //Remove blank line.
	$('table#'+tableId+' tr#noData').remove();

	//Get the reference of the Table's TBODY element.
	var tBody = $("#" + tableId + " > TBODY")[0];

	//Add Row.
	if (data.row_index != "") {
		var trRow = data.row_index;
		$("#" + tableId + " tbody tr:eq(" + trRow + ")").remove();
	}

    var ind = (data.row_index == "") ? -1 : data.row_index;
	row = tBody.insertRow(ind);
	$(row).attr('id',itemCount);
    $(row).attr('data-row_data',JSON.stringify(data));

    //Add index cell
	var countRow = (data.row_index == "") ? ($('#' + tableId + ' tbody tr:last').index() + 1) : (parseInt(data.row_index) + 1);
	var cell = $(row.insertCell(-1));
	cell.html(countRow);
	cell.attr("style", "width:5%;");

    $.each(visibleColumns,function(){ 
        $(row.insertCell(-1));
    });

    //Add Visible Columns Cell
    var cellInput = "";var hiddenInputs = ""; var position = "";
    $.each(data,function(input_key, input_value){
        cellInput, hiddenInputs, position = "";
        if($.inArray(input_key,visibleColumns) >= 0){
            position = parseInt($.inArray(input_key,visibleColumns)) + 1;
            
            cell = $(row).find('td').eq(position);
            cell.html(input_value);
        }

        if($.inArray(input_key, itemHiddenInputs) >= 0){
            hiddenInputs += $("<input/>",{ type : "hidden", name : "itemData["+itemCount+"]["+input_key+"]", class : input_key, value : input_value}).prop('outerHTML');
        }
    });

    // Get the second cell of the row and append the hidden inputs
    var secondCell = $(row).find('td').eq(1);
    secondCell.append(hiddenInputs);

    //Add Action Button cell.
	cell = $(row.insertCell(-1));

    if(actionBtn.editBtn == 1){
        var btnEdit = $('<button>'+editBtnIcon+'</button>');
        btnEdit.attr("type", "button");
        btnEdit.attr("onclick", "Edit(" + JSON.stringify(data) + ",this);");
        btnEdit.attr("class", "btn btn-outline-warning btn-sm waves-effect waves-light");
        cell.append(btnEdit);
    }

    if(actionBtn.deleteBtn == 1){
        var btnRemove = $('<button>'+deleteBtnIcon+'</button>');
        btnRemove.attr("type", "button");
        btnRemove.attr("onclick", "Remove(this);");
        btnRemove.attr("style", "margin-left:4px;");
        btnRemove.attr("class", "btn btn-outline-danger btn-sm waves-effect waves-light");
        cell.append(btnRemove);
    }

	cell.attr("class", "text-center");
	cell.attr("style", "width:10%;");

    claculateColumn();
    itemCount++;
}

function claculateColumn(){
    var qtyArray = $(".qty").map(function () { return $(this).val(); }).get();
	var qtySum = 0;
	$.each(qtyArray, function () { qtySum += parseFloat(this) || 0; });
	$("#totalQty").html(qtySum.toFixed(2));

    var discAmtArray = $(".disc_amount").map(function () { return $(this).val(); }).get();
	var discAmtSum = 0;
	$.each(discAmtArray, function () { discAmtSum += parseFloat(this) || 0; });
	$("#totalDiscAmt").html(discAmtSum.toFixed(2));

    var taxableAmtArray = $(".taxable_amount").map(function () { return $(this).val(); }).get();
	var taxableAmtSum = 0;
	$.each(taxableAmtArray, function () { taxableAmtSum += parseFloat(this) || 0; });
	$("#totalTaxableAmt").html(taxableAmtSum.toFixed(2));

    $(".calculateExpense").trigger('change');
}

function calculateExpense(id = ""){
    
    if(id != ""){
        var per = $("#per"+id).val() || 0;
        var amt = $("#amt"+id).val() || 0;
        var p_or_m = $("#p_or_m"+id).val() || 0;

        if(parseFloat(per) > 0){

            var taxableAmtArray = $(".taxable_amount").map(function () { return $(this).val(); }).get();
            var taxableAmtSum = 0;
            $.each(taxableAmtArray, function () { taxableAmtSum += parseFloat(this) || 0; });
            
            taxableAmtSum = taxableAmtSum.toFixed(2);        
    
            var amount = parseFloat(((parseFloat(per) * parseFloat(taxableAmtSum)) / 100)).toFixed(2) || 0;
            $("#amt"+id).val(amount);
            amount = parseFloat(parseFloat(amount) * p_or_m).toFixed(2);
            $("#amount"+id).val(amount);

        }else if(parseFloat(amt) > 0){
            var amount = parseFloat(parseFloat(amt) * p_or_m).toFixed(2);
            $("#amount"+id).val(amount);
        }        
    }
}