<?php $this->load->view('includes/header'); ?>
<link href="<?=base_url();?>assets/src/assets/css/light/components/accordions.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url();?>assets/src/assets/css/dark/components/accordions.css" rel="stylesheet" type="text/css" />

<div id="content" class="main-content">
	<div class="layout-px-spacing">
		<div class="middle-content p-0">
			
			<div class="container">
				<form id="empPermission" data-res_function="resPermission">
					<div class="row layout-top-spacing">
						<div class="col-lg-9">
							<div class="btn-group">
								<a href="<?= base_url($headData->controller) ?>" class="btn waves-effect waves-light btn-outline-primary  permission-write "> General Permission</a>
								<a href="<?= base_url($headData->controller . "/empPermissionReport/") ?>" class="btn waves-effect waves-light btn-outline-warning permission-write"> Report Permission</a>
								<button type="button" class="btn waves-effect waves-light btn-outline-success float-center permission-write" onclick="edit({'modal_id' : 'right_modal', 'form_id' : 'copyPermission','fnedit':'copyPermission','fnsave':'copyPermission', 'title' : 'Copy Permission','js_store_fn':'confirmStore'});">Copy Permission</button>

								<a href="<?= base_url($headData->controller . "/appPermission/") ?>" class="btn waves-effect waves-light btn-outline-warning permission-write"> App Permission</a>
								<a href="<?= base_url($headData->controller . "/dashPermission/") ?>" class="btn waves-effect waves-light btn-outline-info permission-write active-status"> Dashboard Permission</a>
							</div>
						</div>
						
						<div class="col-lg-3 layout-spacing">
							<div class="page-title-box">
								<input type="hidden" id="menu_type" name="menu_type" value="<?=!empty($menu_type)?$menu_type:1;?>">						
								<select name="emp_id" id="emp_id" class="form-control selectBox">
									<option value="">Select User</option>
									<?php
										foreach ($userList as $row) :
											$user_name = (!empty($row->user_code))?'[' . $row->user_code . '] ' . $row->user_name:$row->user_name;
											echo '<option value="' . $row->id . '">' . $user_name . '</option>';
										endforeach;
									?>
								</select>
							</div>
						</div>
					</div>
					
					<div class="row">
                        <div class="col-lg-12 layout-spacing">
                            <div class="statbox widget box box-shadow">
								<div id="accordionIcons" class="widget-header">
                                    <div class="row"><div class="col-xl-12 col-md-12 col-sm-12 col-12">&nbsp;</div></div>
                                </div> 
                                <div class="widget-content widget-content-area">
                                    <div id="iconsAccordion" class="accordion-icons accordion">
					                    <div class="card sh-nice mb-10">
                                            <div class="card-header" id="pheadDash">
                                                <section class="mb-0 mt-0">
                                                    <div role="menu" class="collapsed text-dark fw-bold fs-16" data-bs-toggle="collapse" data-bs-target="#pmenuDash" aria-expanded="false" aria-controls="iconAccordionOne">
                                                        <div class="accordion-icon">
															<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
														</div>
														Dashboard
														<div class="icons">
															<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
														</div>
                                                    </div>
                                                </section>
                                            </div>
										    <div id="pmenuDash" class="collapse" aria-labelledby="pheadDash" data-bs-parent="#iconsAccordion">
                                                <div class="card-body">
													<table id='reportTable' class="table table-bordered table-striped table-hover text-center">
														<tr class="bg-thinfo">
															<th class="text-center">
																<div class="form-check form-check-primary">
																	<input type="checkbox" id="masterSelect" class="form-check-input checkAll" value="1">
																</div>
															</th>
															<th>
																Dashboard Widgets
															</th>
															<th>
																View
															</th>
														</tr>
														<?php
															if(!empty($dashPermisson)):
																$i=1;
																foreach($dashPermisson as $row):
																	echo '<tr>
																		<td class="text-center">
																			'.$i++.'
																		</td>
																		<td class="text-center">
																			'.$row->widget_name.'
																		</td>
																		<td class="text-center">
																			<div class="form-check form-check-primary">
																				<input type="checkbox" id="is_read'.$row->id.'" name="is_read_'.$row->id.'" class="form-check-input checkRead" value="1" ><label for="is_read'.$row->id.'" class="mr-3"></label>
																				<input type="hidden" name="widget_id[]" id="widget_id' . $row->id . '" value="' . $row->id . '">
																			</div>
																		</td>
																	</tr>';
																endforeach;
															endif;
														?>
													</table>
                                                </div>
                                            </div>
										</div>
									</div>
                                </div>
                            </div>
                        </div>
					</div>
				</form>
			</div>

		</div>
	</div>
</div>

<div class="bottomBtn bottom-15 permission-write">
<?php $postData = "{'formId':'empPermission','fnsave':'savePermission'}"; ?>
    <button type="button" class=" btn btn-primary btn-round btn-outline-dashed font-bold permission-write save-form" style="letter-spacing:1px;" onclick="customStore(<?=$postData?>);">SAVE PERMISSION</button>
</div>

<?php $this->load->view('includes/footer'); ?>
<script>
$(document).ready(function() {
   
    $(document).on('change',"#emp_id",function(){
        var emp_id = $(this).val();
        $("#empPermission")[0].reset();
        $(".error").html("");
        $(this).val(emp_id);
        $(".chk-col-success").removeAttr("checked");
        
        $.ajax({
            type: "POST",   
            url: base_url + controller + '/editDashPermission',   
            data: {emp_id:emp_id},
            dataType:"json"
        }).done(function(response){
            var permission = response.empPermission;
            if(permission.length > 0){
                $.each(response.empPermission,function(key, value) {
                    $("#"+value).attr("checked","checked");
                }); 
            }
        });
    });
    
    $(document).on('click', '.checkAll', function() {
        
        if ($(this).prop('checked') == true) {
            $(".checkRead").prop('checked', true);
        } else {
            $(".checkRead").prop('checked', false);
        }
    });
    

});
function resPermission(data,formId){
    if(data.status==1){
        $("#"+formId)[0].reset();
        $(".chk-col-success").removeAttr("checked");
		Swal.fire( 'Success', data.message, 'success' );
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) { $("."+key).html(value); });
        }else{
			Swal.fire( 'Sorry...!', data.message, 'error' );
        }			
    }
}
</script>