
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
								<a href="<?= base_url($headData->controller) ?>" class="btn waves-effect waves-light btn-outline-primary  permission-write"> General Permission</a>
								<a href="<?= base_url($headData->controller . "/empPermissionReport/") ?>" class="btn waves-effect waves-light btn-outline-warning permission-write active"> Report Permission</a>
								<button type="button" class="btn waves-effect waves-light btn-outline-success float-center permission-write" onclick="edit({'modal_id' : 'right_modal', 'form_id' : 'copyPermission','fnedit':'copyPermission','fnsave':'copyPermission', 'title' : 'Copy Permission','js_store_fn':'confirmStore'});">Copy Permission</button>

								<a href="<?= base_url($headData->controller . "/appPermission/") ?>" class="btn waves-effect waves-light btn-outline-warning permission-write"> App Permission</a>
								<a href="<?= base_url($headData->controller . "/dashPermission/") ?>" class="btn waves-effect waves-light btn-outline-info permission-write"> Dashboard Permission</a>
							</div>
						</div>
						<div class="col-lg-3 layout-spacing">
							<div class="page-title-box">
								<input type="hidden" id="menu_type" name="menu_type" value="<?=!empty($menu_type)?$menu_type:1;?>">
								<select name="emp_id" id="emp_id" class="form-control selectList ">
									<option value="">Select Employee</option>
									<?php
										foreach ($empList as $row) :
											$empName = (!empty($row->emp_code))?'[' . $row->emp_code . '] ' . $row->emp_name:$row->emp_name;
											echo '<option value="' . $row->id . '">' . $empName . '</option>';
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
										<?php foreach ($permission as $row) : ?>
                                        <div class="card sh-nice mb-10">
                                            <div class="card-header" id="phead<?=$row->id?>">
                                                <section class="mb-0 mt-0">
                                                    <div role="menu" class="collapsed text-dark fw-bold fs-16" data-bs-toggle="collapse" data-bs-target="#pmenu<?=$row->id?>" aria-expanded="false" aria-controls="iconAccordionOne">
                                                        <div class="accordion-icon"><?=$row->menu_icon?></div>
														<?= $row->menu_name ?>
														<input type="hidden" name="menu_id[]" value="<?= $row->id ?>">
														<input type="hidden" name="is_master[]" value="<?= $row->is_master ?>">
														<?php
														if (empty($row->is_master)) :
															echo '<input type="hidden" name="main_id[]" value="' . $row->id . '">';
														endif;
														?>
														<div class="icons">
															<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
														</div>
                                                    </div>
                                                </section>
                                            </div>

                                            <div id="pmenu<?=$row->id?>" class="collapse" aria-labelledby="phead<?=$row->id?>" data-bs-parent="#iconsAccordion">
                                                <div class="card-body">
													<table id='reportTable<?= $row->id ?>' class="table table-bordered table-striped table-hover">
														<tr class="bg-thinfo">
															<th class="text-center">
																<div class="form-check form-check-primary">
																	<input type="checkbox" id="masterSelect_<?=$row->id?>" class="form-check-input checkAll" value="<?=$row->id?>">
																</div>
															</th>
															<th>
																Menu/Page Name
																
															</th>
															<th class="text-center">Read</th>
															<th class="text-center">Write</th>
															<th class="text-center">Modify</th>
															<th class="text-center">Delete</th>
															<th class="text-center">Approve</th>
														</tr>
														<?php
														$j = 1;
														foreach ($row->subMenus as $subRow) :
															if (empty($subRow->menu_id)) :
																$inputReadName = "menu_read_" . $row->id;
																$inputWriteName = "menu_write_" . $row->id;
																$inputModifyName = "menu_modify_" . $row->id;
																$inputDeleteName = "menu_delete_" . $row->id;
															else :
																$inputReadName = "sub_menu_read_" . $subRow->id . "_" . $row->id;
																$inputWriteName = "sub_menu_write_" . $subRow->id . "_" . $row->id;
																$inputModifyName = "sub_menu_modify_" . $subRow->id . "_" . $row->id;
																$inputDeleteName = "sub_menu_delete_" . $subRow->id . "_" . $row->id;
																$inputApproveName = "sub_menu_approve_" . $subRow->id . "_" . $row->id;
															endif;
														?>
															<tr>
																<td class="text-center"><?= $j++ ?></td>
																<td>
																	<?= $subRow->sub_menu_name ?>
																	<?php
																		if (!empty($subRow->menu_id)) :
																			echo '<input type="hidden" name="sub_menu_id_' . $row->id . '[]" value="' . $subRow->id . '">';
																		endif;
																	?>
																</td>
																<td class="text-center">
																	<div class="form-check form-check-primary">
																		<input type="checkbox" id="<?= $inputReadName ?>" name="<?= $inputReadName ?>[]" class="form-check-input check_<?=$row->id?>" value="1">
																	</div>
																</td>
																<td class="text-center">
																	<?php if ($subRow->is_report == 0) : ?>
																		<div class="form-check form-check-primary">
																			<input type="checkbox" id="<?= $inputWriteName ?>" name="<?= $inputWriteName ?>[]" class="form-check-input check_<?=$row->id?>" value="1">
																		</div>
																	<?php endif; ?>
																</td>
																<td class="text-center">
																	<?php if ($subRow->is_report == 0) : ?>
																		<div class="form-check form-check-primary">
																			<input type="checkbox" id="<?= $inputModifyName ?>" name="<?= $inputModifyName ?>[]" class="form-check-input check_<?=$row->id?>" value="1">
																		</div>
																	<?php endif; ?>
																</td>
																<td class="text-center">
																	<?php if ($subRow->is_report == 0) : ?>
																		<div class="form-check form-check-primary">
																			<input type="checkbox" id="<?= $inputDeleteName ?>" name="<?= $inputDeleteName ?>[]" class="form-check-input check_<?=$row->id?>" value="1">
																		</div>
																	<?php endif; ?>
																</td>
																<td class="text-center">
																	<?php if ($subRow->is_approve_req == 1) : ?>
																		<div class="form-check form-check-primary">
																			<input type="checkbox" id="<?= $inputApproveName ?>" name="<?= $inputApproveName ?>[]" class="form-check-input check_<?=$row->id?>" value="1">
																		</div>
																	<?php endif; ?>
																</td>
															</tr>

														<?php endforeach; ?>
													</table>
                                                </div>
                                            </div>
										</div>
										<?php endforeach; ?>
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
<script src="<?php echo base_url(); ?>assets/src/emp-permission.js?v=<?= time() ?>"></script>
