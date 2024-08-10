var zindex = "9999";var selectBox = "";
$(document).ready(function(){
	
	var lastActivityTime = new Date();

	// Update last activity time on user interaction events //mousemove
	$(document).on('click change keydown', function() {
		var idleTime = 7200; //Session Time
		var currentDateTime = new Date();

		// Calculate the time difference in milliseconds
		var idleThreshold = currentDateTime - lastActivityTime;

		// Convert the time difference to seconds
        var secondsDifference = Math.floor(idleThreshold / 1000);

		if (secondsDifference > idleTime) {
			// Idle time exceeded threshold, perform actions or redirect user
			//console.log('User is idle');
			window.location.reload();
			// Perform any necessary actions or redirect the user
		} else {
			// User is active, perform any necessary actions
			lastActivityTime = new Date();
		}		
	});

	// Check last activity time every second
	setInterval(function() {
		var idleTime = 7200; //Session Time
		var currentDateTime = new Date();

		// Calculate the time difference in milliseconds
		var idleThreshold = currentDateTime - lastActivityTime;

		// Convert the time difference to seconds
        var secondsDifference = Math.floor(idleThreshold / 1000);

		if (secondsDifference > idleTime) {
			// Idle time exceeded threshold, perform actions or redirect user
			//console.log('User is idle');
			window.location.reload();
			// Perform any necessary actions or redirect the user
		} else {
			// User is active, perform any necessary actions
			//console.log('User is active, Seconds : '+ secondsDifference);
		}
	}, 1000); // Check every second (adjust interval as needed)

	setPlaceHolder();
	
    $(document).on("keypress",".numericOnly",function (e) {
		if (String.fromCharCode(e.keyCode).match(/[^0-9]/g)) return false;
	});	

	$(document).on("keypress",'.floatOnly',function(event) {
		if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {event.preventDefault();}
	});
		
	/*** Keep Selected Tab after page loading ***/
	var selectedTab = localStorage.getItem('selected_tab');
	if (selectedTab != null) { $("#"+selectedTab).trigger('click'); }
	$(document).on('click','.nav-tab',function(){
		var id = $(this).attr('id');
    	localStorage.setItem('selected_tab', id);
    });
	
	$(document).ajaxStart(function(){
		$('.ajaxModal').show();$('.centerImg').show();$(".error").html("");
		$('.btn-save').attr('disabled','disabled');
	});
	
	$(document).ajaxComplete(function(){
		$('.ajaxModal').hide();$('.centerImg').hide();
		$('.btn-save').removeAttr('disabled');
		checkPermission();
	});
	
	$('select').each(function () {
		if($(this).hasClass('selectList'))
		{
			initSelectBox($(this).attr('id'));
		}
	});

	$(document).on('change',".partyDetails",function(){
		var party_id = $(this).val();
		var resFunctionName = $(this).data('res_function') || "";

		if(party_id){
			$.ajax({
				url : base_url + controller  + '/getPartyDetails',
				type:'post',
				data: {id:party_id},
				dataType : 'json',
			}).done(function(response){
				window[resFunctionName](response);
			});
		}else{
			window[resFunctionName]();
		}
	});

	$(document).on('change click',".itemDetails",function(){
		var item_id = $(this).val();
		var resFunctionName = $(this).data('res_function') || "";
		var party_id = $("#party_id").val() || "";
		var party_name = $("#party_name").val() || "";

		$(".party_id").html("");
		if($(this).hasClass("partyReq")){			
			if(party_id == "" && party_name == ""){ $(".party_id").html("Party Name is required."); return false; } 
		}
		
		if(item_id){
			$.ajax({
				url : base_url + controller + '/getItemDetails',
				type:'post',
				data: {id : item_id, party_id : party_id},
				dataType : 'json',
			}).done(function(response){
				window[resFunctionName](response);
			});
		}else{
			window[resFunctionName]();
		}
	});

});

$(window).on('pageshow', function() {
	$('form').off();
	checkPermission();setMinMaxDate();
});

function initSelectBox(type="id",ele=""){
	if(type == "id"){  
		vanillaBox("#"+ele);
	}else{ 
		$("."+ele).each(function(){
			vanillaBox("#"+$(this).attr("id"));
		});		
	}	
}

function vanillaBox(ele){
	var selectBox = new vanillaSelectBox(ele, {
		"keepInlineStyles":true,
		"maxHeight": 200,
		"search": true,
		"placeHolder": "Select..."
	});
}

function setMinMaxDate(){
	$.each($('.fyDates'),function(){
		var minAttr = $(this).attr('min');
		var maxAttr = $(this).attr('max');	
		if(typeof minAttr === 'undefined' || minAttr === false){ $(this).attr('min',startYearDate); }
		if(typeof maxAttr === 'undefined' || maxAttr === false){ $(this).attr('max',endYearDate); }	
	});	
}

function setPlaceHolder(){
	var label="";
	$('input').each(function () {
		if(!$(this).hasClass('combo-input') && $(this).attr("type")!="hidden" )
		{
			label="";
			inputElement = $(this).parent();
			if($(this).parent().hasClass('input-group')){inputElement = $(this).parent().parent();}else{inputElement = $(this).parent();}
			label = inputElement.children("label").text();
			label = label.replace('*','');
			label = $.trim(label);
			if($(this).hasClass('req')){inputElement.children("label").html(label + ' <strong class="text-danger">*</strong>');}
			if(!$(this).attr("placeholder")){if(label){$(this).attr("placeholder", label);}}
			$(this).attr("autocomplete", 'off');
			var errorClass="";
			var nm = $(this).attr('name');
			if($(this).attr('id')){errorClass=$(this).attr('id');}else{errorClass=$(this).attr('name');if(errorClass){errorClass = errorClass.replace("[]", "");}}
			if(inputElement.find('.'+errorClass).length <= 0){inputElement.append('<div class="error '+ errorClass +'"></div>');}
		}
		else{$(this).attr("autocomplete", 'off');}
	});
	$('textarea').each(function () {
		label="";
		label = $(this).parent().children("label").text();
		label = label.replace('*','');
		label = $.trim(label);
		if($(this).hasClass('req')){$(this).parent().children("label").html(label + ' <strong class="text-danger">*</strong>');}
		if(label){$(this).attr("placeholder", label);}
		$(this).attr("autocomplete", 'off');
		var errorClass="";
		var nm = $(this).attr('name');
		if($(this).attr('name')){errorClass=$(this).attr('name');}else{errorClass=$(this).attr('id');}
		if($(this).parent().find('.'+errorClass).length <= 0){$(this).parent().append('<div class="error '+ errorClass +'"></div>');}
	});
	$('select').each(function () {
		let string =String($(this).attr('name'));
		if(string.indexOf('[]') === -1)
		{
			label="";
			var selectElement = $(this).parent();
			if($(this).hasClass('single-select')){selectElement = $(this).parent().parent();}
			label = selectElement.children("label").text();
			label = label.replace('*','');
			label = $.trim(label);
			if($(this).hasClass('req')){selectElement.children("label").html(label + ' <strong class="text-danger">*</strong>');}
			var errorClass="";
			var nm = $(this).attr('name');
			
			if($(this).attr('id')){errorClass=$(this).attr('id');}else{errorClass=$(this).attr('name');}
			if(selectElement.find('.'+errorClass).length <= 0){selectElement.append('<div class="error '+ errorClass +'"></div>');}
		}
	});
}

function changePsw(formId){
	var fd = $('#'+formId).serialize();
	$.ajax({
		url: base_url + 'hr/employees/changePassword',
		data:fd,
		type: "POST",
		dataType:"json",
	}).done(function(data){
		if(data.status===0){
			$(".error").html("");
			$.each( data.message, function( key, value ) {
				$("."+key).html(value);
			});
		}else if(data.status==1){
			initTable(); $("#change-psw").modal('hide');
			Swal.fire( 'Success', data.message, 'success' );
		}else{
			initTable(); $("#change-psw").modal('hide');
			Swal.fire( 'Sorry...!', data.message, 'error' );
		}		
	});
}

function isInteger(x) { return typeof x === "number" && isFinite(x) && Math.floor(x) === x; }

function isFloat(x) { return !!(x % 1); }

function checkPermission(){
	$('.permission-read').show();
	$('.permission-write').show();
	$('.permission-modify').show();
	$('.permission-remove').show();
	$('.permission-approve').show();

	//view permission
	if(permissionRead == "1"){ 
		$('.permission-read').prop('disabled', false);
		$('.permission-read').show(); 
	}else{ 
		$('.permission-read').prop('disabled', true);
		$('.permission-read').hide(); 
		//window.location.href = base_url + 'error_403';
	}

	//write permission
	if(permissionWrite == "1"){ 
		$('.permission-write').prop('disabled', false);
		$('.permission-write').show(); 
	}else{ 
		$('.permission-write').prop('disabled', true);
		$('.permission-write').hide(); 
	}

	//update permission
	if(permissionModify == "1"){ 
		$('.permission-modify').prop('disabled', false);
		$('.permission-modify').show(); 
	}else{ 
		$('.permission-modify').prop('disabled', true);
		$('.permission-modify').hide(); 
	}

	//delete permission
	if(permissionRemove == "1"){ 
		$('.permission-remove').prop('disabled', false);
		$('.permission-remove').show(); 
	}else{ 
		$('.permission-remove').prop('disabled', true);
		$('.permission-remove').hide(); 
	}

	//Approve permission
	if(permissionApprove == "1"){ 
		$('.permission-approve').prop('disabled', false);
		$('.permission-approve').show(); 
	}else{ 
		$('.permission-approve').prop('disabled', true);
		$('.permission-approve').hide(); 
	}
}

function formatDate(date,format='Y-m-d') {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;
        
    var convertedDate = date;
    if(format == "Y-m-d"){return [year, month, day].join('-');}
    if(format == "y-m-d"){year = year.toString().substr(-2); convertedDate = [year, month, day].join('-');}
    if(format == "d-m-Y"){return [day, month, year].join('-');}
    if(format == "d-m-y"){year = year.toString().substr(-2); convertedDate = [day, month, year].join('-');}
    
    return convertedDate;
}

function calcTimeDiffInHrs(start_time,end_time,type="H"){
    var time1 = start_time.split(':'), time2 = end_time.split(':');
    var hours1 = parseInt(time1[0], 10), 
    hours2 = parseInt(time2[0], 10),
    mins1 = parseInt(time1[1], 10),
    mins2 = parseInt(time2[1], 10);
    var hours = hours2 - hours1, mins = 0;
    
    if(hours < 0) hours = 24 + hours;
    
    if(mins2 >= mins1) {mins = mins2 - mins1;}
    else {mins = (mins2 + 60) - mins1;hours--;}
    
    var minute = (hours * 60) + mins;
    mins = mins / 60; 
    
    hours += mins;
    hours = hours.toFixed(2);
    if(type=="H"){return hours;}
    else{return minute;}
}

function inrFormat(no){
    if(no){
        no=no.toString();
        var afterPoint = '';
        if(no.indexOf('.') > 0)
           afterPoint = no.substring(no.indexOf('.'),no.length);
        no = Math.floor(no);
        no=no.toString();
        var lastThree = no.substring(no.length-3);
        var otherNumbers = no.substring(0,no.length-3);
        if(otherNumbers != ''){lastThree = ',' + lastThree;}
            
        var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree + afterPoint;
    	return res;
    }else{return no;}        
}

function closeModal(formId){
	zindex--;
	
	var modal_id = $("."+formId+"Modal").attr('id');
	$("#"+modal_id).removeClass(formId+"Modal");
	$("#"+modal_id+' .modal-body').html("");
	$("#"+modal_id).modal('hide');	
	$(".modal").css({'overflow':'auto'});
	$("#"+modal_id).removeClass('modal-i-'+zindex);	
	$('.modal-i-'+(zindex-1)).addClass('show');

	$("#"+modal_id+" .modal-header .btn-close").attr('data-modal_id',"");
	$("#"+modal_id+" .modal-header .btn-close").attr('data-modal_class',"");
	$("#"+modal_id+" .modal-footer .btn-close-modal").attr('data-modal_id',"");
	$("#"+modal_id+" .modal-footer .btn-close-modal").attr('data-modal_class',"");
}

function store(postData){
	setPlaceHolder();
		
	if(postData.txt_editor !== "")
	{
    	var myContent = tinymce.get(postData.txt_editor).getContent();
    	$("#" + postData.txt_editor).val(myContent);
	}

	var formId = postData.formId;
	var fnsave = postData.fnsave || "save";
	var controllerName = postData.controller || controller;

	var form = $('#'+formId)[0];
	var fd = new FormData(form);
	$.ajax({
		url: base_url + controllerName + '/' + fnsave,
		data:fd,
		type: "POST",
		processData:false,
		contentType:false,
		dataType:"json",
	}).done(function(data){
		if(data.status==1){
			$('#'+formId)[0].reset(); closeModal(formId);
			console.log(data.responseEle + ' = ' + data.responseHtml);
			if(data.responseHtml != ""){
				//$(data.responseEle).html("");
				$(data.responseEle).html(data.responseHtml);
			}
			//Swal.fire({ icon: 'success', title: data.message});
			//Toast.fire({icon: 'success',title: data.message});
		}else{
			if(typeof data.message === "object"){
				$(".error").html("");
				$.each( data.message, function( key, value ) {$("."+key).html(value);});
			}else{
				Swal.fire({ icon: 'error', title: data.message });
				
			}			
		}				
	});
}

function customStore(postData){
	postData.txt_editor = postData.txt_editor || "";
	if(postData.txt_editor !== "")
	{
    	var myContent = tinymce.get(postData.txt_editor).getContent();
    	$("#" + postData.txt_editor).val(myContent);
	}
	
	var formId = postData.formId;
	var fnsave = postData.fnsave || "save";
	var controllerName = postData.controller || controller;
	var formClose = postData.form_close || "";

	var form = $('#'+formId)[0];
	var fd = new FormData(form);
	var resFunctionName = $("#"+formId).data('res_function') || "";
	
	$.ajax({
		url: base_url + controllerName + '/' + fnsave,
		data:fd,
		type: "POST",
		processData:false,
		contentType:false,
		dataType:"json",
	}).done(function(data){	
		if(resFunctionName != ""){
			if(formClose){ 
				$('#'+formId)[0].reset(); closeModal(formId);
				Toast.fire({icon: 'success',title: data.message});
			}
			window[resFunctionName](data,formId);
		}else{
			if(data.status==1){
				$('#'+formId)[0].reset(); closeModal(formId);
				Swal.fire({ icon: 'success', title: data.message});
			}else{
				if(typeof data.message === "object"){
					$(".error").html("");
					$.each( data.message, function(key, value) {$("."+key).html(value);});
				}else{
					Swal.fire({ icon: 'error', title: data.message });
				}			
			}	
		}			
	});
}

function confirmStore(data){
	

	var formId = data.formId || "";
	var fnsave = data.fnsave || "save";
	var controllerName = data.controller || controller;

	if(formId != ""){
		var form = $('#'+formId)[0];
		var fd = new FormData(form);
		var resFunctionName = $("#"+formId).data('res_function') || "";
		var msg = "Are you sure want to save this record ?";
		var ajaxParam = {
			url: base_url + controllerName + '/' + fnsave,
			data:fd,
			type: "POST",
			processData:false,
			contentType:false,
			dataType:"json"
		};
	}else{
		var fd = data.postData;
		var resFunctionName = data.res_function || "";
		var msg = data.message || "Are you sure want to save this change ?";
		var ajaxParam = {
			url: base_url + controllerName + '/' + fnsave,
			data:fd,
			type: "POST",
			dataType:"json"
		};
	}
	Swal.fire({
		title: 'Are you sure?',
		text: msg,
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, Do it!',
	}).then(function(result) {
		if (result.isConfirmed){
			$.ajax(ajaxParam).done(function(response){
				if(resFunctionName != ""){
					window[resFunctionName](response,formId);
				}else{
					if(response.status==1){
						if(formId != ""){$('#'+formId)[0].reset(); closeModal(formId);}
						Swal.fire( 'Success', response.message, 'success' );
					}else{
						if(typeof response.message === "object"){
							$(".error").html("");
							$.each( response.message, function( key, value ) {$("."+key).html(value);});
						}else{
							Swal.fire( 'Sorry...!', response.message, 'error' );
						}			
					}	
				}			
			});
		}
	});
}

function initModal(postData,response){
	var button = postData.button;if(button == "" || button == null){button="both";};
	var fnedit = postData.fnedit;if(fnedit == "" || fnedit == null){fnedit="edit";}
	var fnsave = postData.fnsave;if(fnsave == "" || fnsave == null){fnsave="save";}
	var controllerName = postData.controller;if(controllerName == "" || controllerName == null){controllerName=controller;}
	var savebtn_text = postData.savebtn_text;
	var savebtn_icon = postData.savebtn_icon || "";
	if(savebtn_text != ""){savebtn_text = savebtn_text;}

	var resFunction = postData.res_function || "";
	var jsStoreFn = postData.js_store_fn || 'store';
	var txt_editor = postData.txt_editor || '';
	var form_close = postData.form_close || '';

	var fnJson = "{'formId':'"+postData.form_id+"','fnsave':'"+fnsave+"','controller':'"+controllerName+"','txt_editor':'"+txt_editor+"','form_close':'"+form_close+"'}";

	$("#"+postData.modal_id).modal('show');
	$("#"+postData.modal_id).addClass('modal-i-'+zindex);
	$('.modal-i-'+(zindex - 1)).removeClass('show');
	$("#"+postData.modal_id).css({'z-index':zindex,'overflow':'auto'});
	$("#"+postData.modal_id).addClass(postData.form_id+"Modal");
	$("#"+postData.modal_id+' .modal-title').html(postData.title);
	$("#"+postData.modal_id+' .modal-body').html('');
	$("#"+postData.modal_id+' .modal-body').html(response);
	$("#"+postData.modal_id+" .modal-body form").attr('id',postData.form_id);
	if(resFunction != ""){
		$("#"+postData.modal_id+" .modal-body form").attr('data-res_function',resFunction);
	}
	$("#"+postData.modal_id+" .modal-footer .btn-save").html(savebtn_text);
	$("#"+postData.modal_id+" .modal-footer .btn-save").attr('onclick',jsStoreFn+"("+fnJson+");");
	$("#"+postData.modal_id+" .btn-custom-save").attr('onclick',jsStoreFn+"("+fnJson+");");

	$("#"+postData.modal_id+" .modal-header .btn-close").attr('data-modal_id',postData.modal_id);
	$("#"+postData.modal_id+" .modal-header .btn-close").attr('data-modal_class',postData.form_id+"Modal");
	$("#"+postData.modal_id+" .modal-footer .btn-close-modal").attr('data-modal_id',postData.modal_id);
	$("#"+postData.modal_id+" .modal-footer .btn-close-modal").attr('data-modal_class',postData.form_id+"Modal");

	if(button == "close"){
		$("#"+postData.modal_id+" .modal-footer .btn-close-modal").show();
		$("#"+postData.modal_id+" .modal-footer .btn-save").hide();
	}else if(button == "save"){
		$("#"+postData.modal_id+" .modal-footer .btn-close-modal").hide();
		$("#"+postData.modal_id+" .modal-footer .btn-save").show();
	}else{
		$("#"+postData.modal_id+" .modal-footer .btn-close-modal").show();
		$("#"+postData.modal_id+" .modal-footer .btn-save").show();
	}
	
	setTimeout(function(){ 
		setMinMaxDate();setPlaceHolder();initSelectBox("cls","selectBox");
	}, 5);
	setTimeout(function(){
		$('#'+postData.modal_id+' :input:enabled:visible:first, select:first').focus();
	},500);
	zindex++;
}

function modalAction(data){
	var call_function = data.call_function;
	if(call_function == "" || call_function == null){call_function="edit";}

	var fnsave = data.fnsave;
	if(fnsave == "" || fnsave == null){fnsave="save";}

	var controllerName = data.controller;
	if(controllerName == "" || controllerName == null){controllerName=controller;}	

	$.ajax({ 
		type: "POST",   
		url: base_url + controllerName + '/' + call_function,   
		data: data.postData,
	}).done(function(response){
		initModal(data,response);
	});
}

function trash(data){
	var controllerName = data.controller || controller;
	var fnName = data.fndelete || "delete";
	var msg = data.message || "Record";
	var send_data = data.postData;
	var resFunctionName = data.res_function || "";
	
	Swal.fire({
		title: 'Are you sure?',
		text: "You won't be able to revert this!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, delete it!',
	}).then(function(result) {
		if (result.isConfirmed){
			$.ajax({
				url: base_url + controllerName + '/' + fnName,
				data: send_data,
				type: "POST",
				dataType:"json",
			}).done(function(response){
				if(resFunctionName != ""){
					window[resFunctionName](response);
				}else{
					if(response.status==0){
						Swal.fire( 'Sorry...!', response.message, 'error' );
					}else{
						Swal.fire( 'Deleted!', response.message, 'success' );
					}	
				}
			});
		}
	});
	
}

function getTransHtml(data){
	var postData = data.postData || {};
	var fnget = data.fnget || "";
	var controllerName = data.controller || controller;
	var resFunctionName = data.res_function || "";

	var table_id = data.table_id || "";
	var thead_id = data.thead_id || "";
	var tbody_id = data.tbody_id || "";
	var tfoot_id = data.tfoot_id || "";	

	if(thead_id != ""){
		$("#"+table_id+" #"+thead_id).html(data.thead);
	}
	
	$.ajax({
		url: base_url + controllerName + '/' + fnget,
		data:postData,
		type: "POST",
		dataType:"json",
		/* beforeSend: function() {
			if(table_id != ""){
				var columnCount = $('#'+table_id+' thead tr').first().children().length;
				$("#"+table_id+" #"+tbody_id).html('<tr><td colspan="'+columnCount+'" class="text-center">Loading...</td></tr>');
			}
		}, */
	}).done(function(res){
		if(resFunctionName != ""){
			window[resFunctionName](response);
		}else{
			$("#"+table_id+" #"+tbody_id).html('');
			$("#"+table_id+" #"+tbody_id).html(res.tbodyData);

			if(tfoot_id != ""){
				$("#"+table_id+" #"+tfoot_id).html('');
				$("#"+table_id+" #"+tfoot_id).html(res.tfootData);
			}
		}
	});
}

function changePsw(formId){
	var fd = $('#'+formId).serialize();
	$.ajax({
		url: base_url + 'hr/employees/changePassword',
		data:fd,
		type: "POST",
		dataType:"json",
	}).done(function(data){
		if(data.status===0){
			$(".error").html("");
			$.each( data.message, function( key, value ) {
				$("."+key).html(value);
			});
		}else if(data.status==1){
			$("#change-psw").modal('hide');
			Swal.fire({ icon: 'success', title: data.message});
		}else{
			$("#change-psw").modal('hide');
			Swal.fire({ icon: 'error', title: data.message });
		}		
	});
}

window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
}, false);

/* This MultiCheck Function is used in datatable */
function checkall(clickchk, relChkbox) {

    var checker = $('#' + clickchk);
    var multichk = $('.' + relChkbox);


    checker.click(function () {
        multichk.prop('checked', $(this).prop('checked'));
    });
}

function multiCheck(tb_var) {
    tb_var.on("change", ".chk-parent", function() {
        var e=$(this).closest("table").find("td:first-child .child-chk"), a=$(this).is(":checked");
        $(e).each(function() {
            a?($(this).prop("checked", !0), $(this).closest("tr").addClass("active")): ($(this).prop("checked", !1), $(this).closest("tr").removeClass("active"))
        })
    }),
    tb_var.on("change", "tbody tr .new-control", function() {
        $(this).parents("tr").toggleClass("active")
    })
}

function delay(callback, ms=500) {
	var timer = 0;
	return function() {
		var context = this, args = arguments;
		clearTimeout(timer);
		timer = setTimeout(function () { callback.apply(context, args); }, ms || 0);
	};
}