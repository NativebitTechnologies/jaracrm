var itemCount = 0;

$(document).ready(function(){
    $(document).on('keyup change',function(){
        calculateExpense(($(this).data('row_id') || ""));
    })
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
		/* if(parseFloat(postData.disc_amount) > 0){
			postData.org_price = parseFloat( parseFloat(postData.org_price) - parseFloat(postData.disc_amount) ).toFixed(3);
		} */
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
    $(row).attr('data-row_data',data);

    //Add index cell
	var countRow = (data.row_index == "") ? ($('#' + tblName + ' tbody tr:last').index() + 1) : (parseInt(data.row_index) + 1);
	var cell = $(row.insertCell(-1));
	cell.html(countRow);
	cell.attr("style", "width:5%;");

    //Add Visible Columns Cell
    var cellInput = "";var hiddenInputs = "";
    $.each(data,function(input_key, input_value){
        cellInput = ""; hiddenInputs = "";
        if($.inArray(input_key,visibleColumns) >= 0){
            cellInput = $("<input/>",{ type : "hidden", name : "itemData["+itemCount+"]["+input_key+"]", class : input_key, value : input_value});
            var position = parseInt($.inArray(input_key,visibleColumns)) + 1;
            cell = $(row.insertCell(position));
            cell.html(input_value);
            cell.append(cellInput);
        }else{
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
}

function calculateExpense(id = ""){
    if(id != ""){
        var per = $("#per"+id).val() || 0;

        var taxableAmtArray = $(".taxable_amount").map(function () { return $(this).val(); }).get();
        var taxableAmtSum = 0;
        $.each(taxableAmtArray, function () { taxableAmtSum += parseFloat(this) || 0; });
        
        taxableAmtSum = taxableAmtSum.toFixed(2);

        var amount = parseFloat(((parseFloat(per) * parseFloat(taxableAmtSum)) / 100)).toFixed(2) || 0;
        $("#amount"+id).val(amount);
    }
}