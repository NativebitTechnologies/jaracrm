
<?php $this->load->view('includes/header'); ?>
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
<!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->


        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="middle-content p-0">
                    <div class="row layout-top-spacing">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-header">
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            
                                        </div>                                                                        
                                    </div>
                                </div>
                                <div class="widget-content widget-content-area">
									<form class="row g-3" id="addCompanyInfo" data-res_function="companyInfoRes">
										<input type="hidden" name="id" value="<?= (!empty($dataRow->id)) ? $dataRow->id : ""; ?>" />
                                        <div class="col-md-8">
                                            <label for="company_name" class="form-label">Company Name</label>
                                            <input type="text" name="company_name" id="company_name" class="form-control req" value="<?=(!empty($dataRow->company_name))?$dataRow->company_name:""?>">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="company_email">Company Email</label>
                                            <input type="email" name="company_email" id="company_email" class="form-control" value="<?=(!empty($dataRow->company_email))?$dataRow->company_email:""?>">
                                        </div>
										<div class="col-md-3">
                                            <label for="company_contact">Company Contact</label>
                                            <input type="text" name="company_contact" id="company_contact" class="form-control" value="<?=(!empty($dataRow->company_contact))?$dataRow->company_contact:""?>">
                                        </div>
										<div class="col-md-3">
                                            <label for="company_phone">Company Phone No.</label>
                                            <input type="text" name="company_phone" id="company_phone" class="form-control" value="<?=(!empty($dataRow->company_phone))?$dataRow->company_phone:""?>">
                                        </div>
										<div class="col-md-3">
                                            <label for="company_gst_no">Company GST No.</label>
                                            <input type="text" name="company_gst_no" id="company_gst_no" class="form-control" value="<?=(!empty($dataRow->company_gst_no))?$dataRow->company_gst_no:""?>">
                                        </div>
										<div class="col-md-3">
                                            <label for="company_pan_no">Company Pan No.</label>
                                            <input type="text" name="company_pan_no" id="company_pan_no" class="form-control" value="<?=(!empty($dataRow->company_pan_no))?$dataRow->company_pan_no:""?>">
                                        </div>
                                        
                                        <div class="col-md-3 form-group">
                                            <label for="city">City</label>
                                            <input type="text" id="city" class="form-control cityList req" value="<?=(!empty($dataRow->city))?$dataRow->city:""?>">
                                            <input type="hidden" name="address_id" id="address_id" value="<?=(!empty($dataRow->address_id))?$dataRow->address_id:""?>">
                                        </div>

                                        <div class="col-md-3 form-group">
                                            <label for="district">District</label>
                                            <input type="text" id="district" class="form-control cityList req" value="<?=(!empty($dataRow->district))?$dataRow->district:""?>">
                                        </div>

                                        <div class="col-md-3 form-group">
                                            <label for="state">State</label>
                                            <input type="text" id="state" class="form-control cityList req" value="<?=(!empty($dataRow->state))?$dataRow->state:""?>">
                                        </div>

                                        <div class="col-md-3 form-group">
                                            <label for="country">Country</label>
                                            <input type="text" id="country" class="form-control cityList req" value="<?=(!empty($dataRow->country))?$dataRow->country:""?>">
                                        </div>

                                        <div class="col-8">
                                            <label for="company_address" class="form-label">Company Address</label>
                                            <input type="text" name="company_address" id="company_address" class="form-control" value="<?=(!empty($dataRow->company_address))?$dataRow->company_address:""?>">
                                        </div>

										<div class="col-md-4">
                                            <label for="company_pincode">Company Pincode</label>
                                            <input type="text" name="company_pincode" id="company_pincode" class="form-control" value="<?=(!empty($dataRow->company_pincode))?$dataRow->company_pincode:""?>">
                                        </div>

										<div class="col-md-6">
											<label for="company_logo">Company Logo</label>
											<div class="input-group">
												<input type="file" class="form-control file-upload-input" name="company_logo">
												<?php if (!empty($dataRow->company_logo)) : ?>
													<?='<a href="' . base_url('assets/images/' . $dataRow->company_logo) . '" target="_blank">  <span style="padding: 10px;" class="form-control bg-primary rounded-rt-1">'.getIcon('download_cloud').'</span></a>'; ?>
												<?php endif; ?>
											</div>
										</div>

										<div class="col-md-6">
											<label for="company_letterhead">Company Letterhead</label>
											<div class="input-group">
												<input type="file" class="form-control file-upload-input" name="company_letterhead">
												<?php if (!empty($dataRow->company_letterhead)) : ?>
													<?= '<a href="' . base_url('assets/images/' . $dataRow->company_letterhead) . '" target="_blank"><span style="padding: 10px;" class="form-control bg-primary rounded-rt-1">'.getIcon('download_cloud').'</span></a>'; ?>
												<?php endif; ?>
											</div>
										</div>
                                        <div class="col-12">
                                            <button type="button" class="btn btn-primary btn-save float-right save-form permission-write" onclick="customStore({'formId':'addCompanyInfo','fnsave':'save'});">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--  END CONTENT AREA  -->
    </div>
<?php $this->load->view('includes/footer'); ?>
<script src="<?=base_url()?>assets/src/typehead.js?v=<?=time()?>"></script>
<script>
$(document).ready(function(){

    $('.cityList').typeahead({
		source: function(query, result){
			$.ajax({
				url:base_url + controller +'/getCityList',
				method:"POST",
				global:false,
				data:{query:query},
				dataType:"json",
				success:function(data){
					result($.map(data, function(row){ 
                        return {
                            name:row.city + ' | ' + row.district + ' | ' + row.state + ' | ' + row.country,
                            id:row.id,
                            country:row.country,
                            state:row.state,
                            district:row.district,
                            city:row.city
                        }; 
                    }));
				}
			});
		},
		updater: function(item) {
            if(item.name != ""){                
                $("#address_id").val(item.id);
                setTimeout(function(){
                    $("#city").val(item.city);
                    $("#district").val(item.district);
                    $("#state").val(item.state);
                    $("#country").val(item.country);
                },200);
            }         
			return item;
        }
	});

});

function companyInfoRes(data,formId){
    if(data.status==1){
		Swal.fire({ icon: 'success', title: data.message});
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) {$("."+key).html(value);});
        }else{
			Swal.fire({ icon: 'success', title: data.message});
        }			
    }			
}
</script>