var load_flag = 0; var ajax_call = false;
if ($(".table-scroll")[0]){
    var tblScroll = new PerfectScrollbar('.table-scroll');
}
$(document).ready(function(){
	loadTransaction();
    	
	const scrollEle = $('.table-scroll');
	var ScrollDebounce = true;
	$(scrollEle).scroll(function() {
		if($(this).scrollTop() + $(this).innerHeight() >= ($(this)[0].scrollHeight - 15)) {
		    if(ScrollDebounce){
    			ScrollDebounce = false;
    			loadTransaction();
    			setTimeout(function () { ScrollDebounce = true; }, 500);
		    }
		}
	});
	

    $(document).on('keyup','#commanSerach',function(e){
        e.stopImmediatePropagation();
        e.preventDefault();

		load_flag = 0;ajax_call = false;
        if($(this).val() != ""){
            $(".lazy-load-trans").addClass("filterList");
        }else{
            $(".lazy-load-trans").removeClass("filterList"); 
        }

        $(".lazy-load-trans").html('');
        tblScroll.update();
		loadTransaction();
	});

    $(document).on('click',"#clearSerach",function(){
        load_flag = 0;ajax_call = false;
        $(".lazy-load-trans").removeClass("filterList"); 
        $(".lazy-load-trans").html('');
        $("#commanSerach").val("");
        tblScroll.update();
		loadTransaction();
    });
    
    $(document).on('click','#applyFilter',function(){
        var page_name = $("#filter_form").data('page_name');
        var filterData = {};
        var form = $('#filter_form')[0];
        if(form){    
            var fd = $(form).serializeArray();    
            $.each(fd,function(key,row){ filterData[row.name] = row.value; });
        }
        var storageData = {
            filters: filterData
        };

        localStorage.setItem(page_name, JSON.stringify(storageData));
        $("#filter-btn").removeClass('text-dark').addClass('text-warning');
        reloadTransaction();
    });

    $(document).on('click','#clearFilters',function(){
        var page_name = $("#filter_form").data('page_name');
        $("#filter_form")[0].reset();
        $("#filter-modal .select2").select2();
        localStorage.removeItem(page_name);
        $("#filter-btn").removeClass('text-warning').addClass('text-dark');
        reloadTransaction();
    });
});

function actionBtnJson(jsonData){
	// Convert JSON to string using jQuery
    var jsonString = JSON.stringify(jsonData);

    // Replace double quotes with &quot; to avoid conflicts in HTML attribute
    var escapedJsonString = jsonString.replace(/"/g, "&quot;");

	return "\'" + escapedJsonString + "\'";
}

function loadTransaction(){
    var search = $('#commanSerach').val() || "";
    var length = $(".lazy-load-trans").data('length') || 20;
    var filter_page_name = $(".lazy-load-trans").data('filter_page_name') || "";
    var postData = $(".lazy-load-trans").data('post_data') || {};
    if(typeof postData === "string"){ postData = JSON.parse(postData); }
    
    var filterData = {};
    if(filter_page_name){
        var flData = localStorage.getItem(filter_page_name);
        if(flData){
            filterData = JSON.parse(flData);
            filterData = filterData.filters;
        } 
    }

    postData.start = load_flag;
    postData.length = length;
    postData.search = search;
    postData.filters = filterData;
    
	var url = $(".lazy-load-trans").attr('data-url');
	var dataset = {url:url,dataset:postData,resFunctionName:"dataListing"};
	loadMore(dataset);
}

function reloadTransaction(){
    $(".lazy-load-trans").html('');
    var search = $('#commanSerach').val() || "";
    var length = $(".lazy-load-trans").data('length') || 20;
    var postData = $(".lazy-load-trans").data('post_data') || {};
    if(typeof postData === "string"){ postData = JSON.parse(postData); }
    
    load_flag = 0;ajax_call = false;
    tblScroll.update();

    var filter_page_name = $(".lazy-load-trans").data('filter_page_name') || "";
    var filterData = {};
    if(filter_page_name){
        var flData = localStorage.getItem(filter_page_name);
        if(flData){
            filterData = JSON.parse(flData);
            filterData = filterData.filters;
        } 
    }

    postData.start = load_flag;
    postData.length = length;
    postData.search = search;
    postData.filters = filterData;
    
	var url = $(".lazy-load-trans").attr('data-url');
	var dataset = {url:url,dataset:postData,resFunctionName:"dataListing"};
	loadMore(dataset);
}

function tabLoading(tabId){
	load_flag = 0;ajax_call = false;
	$(".lazy-load-trans").removeAttr('data-url');
	$(".lazy-load-trans").attr('data-url',$("#"+tabId).data('url'));

    $(".lazy-load-trans").removeAttr('data-post_data');
    $(".lazy-load-trans").attr('data-post_data',($("#"+tabId).data('postData') || {}));

    $(".lazy-load-trans").removeAttr('data-length');
    $(".lazy-load-trans").attr('data-length',($("#"+tabId).data('length') || 20));

    $(".lazy-load-trans").html('');
    tblScroll.update();
	loadTransaction();
}

function loadMore(postData){
    var dataset = postData.dataset;
    var data_length = dataset.length;

    if(ajax_call == true){
        return false;
    }

    $.ajax({
        url : postData.url,
        type : 'post',
        data : dataset,
        dataType : 'json',
        async : false,
        global: false,
        beforeSend: function() {
            //$("#lazyLoader").show();
            ajax_call = true;
        },
    }).done(function(response){
        if(response.dataList != ""){
            $(".lazy-load-trans").append(response.dataList);
            load_flag += data_length;   
            ajax_call = false;
        }
    }).fail(function(xhr, err) { 
        loadingStatus(xhr); 
    });    
}

function loadingStatus(data=""){
    /* var status = navigator.onLine; 
    if(status == false){alert("no internet");} */

    if(data != ""){
        if(data.status == 401){
            setTimeout(function(){ 
                window.location.href = base_url + 'app/logout';
            }, 2000);
        }
    }    
}