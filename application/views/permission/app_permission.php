<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
		<form id="empPermission" data-res_function="resPermission">
			<div class="row">
				<div class="col-9">
					<div class="page-title-box">
						<ul class="nav nav-pills">
							<a href="<?= base_url($headData->controller) ?>" class="btn waves-effect waves-light btn-outline-primary  permission-write active"> General Permission</a>
							<a href="<?= base_url($headData->controller . "/empPermissionReport/") ?>" class="btn waves-effect waves-light btn-outline-warning permission-write"> Report Permission</a>
							<button type="button" class="btn waves-effect waves-light btn-outline-success float-center permission-write" onclick="edit({'modal_id' : 'right_modal', 'form_id' : 'copyPermission','fnedit':'copyPermission','fnsave':'copyPermission', 'title' : 'Copy Permission','js_store_fn':'confirmStore'});">Copy Permission</button>

                            <a href="<?= base_url($headData->controller . "/empPermissionReport/") ?>" class="btn waves-effect waves-light btn-outline-warning permission-write"> Report Permission</a>

						</ul>
					</div>
				</div>
				<div class="col-3">
					<div class="page-title-box">
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-body reportDiv" style="min-height:75vh">
                            <div class="table-responsive">
                                <div class="accordion" id="bs-collapse">
                                    <div class="accordion-body">
                                        <table id='reportTable' class="table table-bordered table-striped">
                                            <tr class="bg-thinfo">
                                                <th class="text-center">#</th>
                                                <th>
                                                    Menu/Page Name
                                                    <input type="checkbox" id="masterSelect_<?=$row->id?>" class="filled-in chk-col-success checkAll" value="<?=$row->id?>"><label for="masterSelect_<?=$row->id?>">Select All</label>
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
                                                        <input type="checkbox" id="<?= $inputReadName ?>" name="<?= $inputReadName ?>[]" class="filled-in chk-col-success check_<?=$row->id?>" value="1">
                                                        <label for="<?= $inputReadName ?>"></label>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($subRow->is_report == 0) : ?>
                                                            <input type="checkbox" id="<?= $inputWriteName ?>" name="<?= $inputWriteName ?>[]" class="filled-in chk-col-success check_<?=$row->id?>" value="1">
                                                            <label for="<?= $inputWriteName ?>"></label>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($subRow->is_report == 0) : ?>
                                                            <input type="checkbox" id="<?= $inputModifyName ?>" name="<?= $inputModifyName ?>[]" class="filled-in chk-col-success check_<?=$row->id?>" value="1">
                                                            <label for="<?= $inputModifyName ?>"></label>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($subRow->is_report == 0) : ?>
                                                            <input type="checkbox" id="<?= $inputDeleteName ?>" name="<?= $inputDeleteName ?>[]" class="filled-in chk-col-success check_<?=$row->id?>" value="1">
                                                            <label for="<?= $inputDeleteName ?>"></label>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($subRow->is_approve_req == 1) : ?>
                                                            <input type="checkbox" id="<?= $inputApproveName ?>" name="<?= $inputApproveName ?>[]" class="filled-in chk-col-success check_<?=$row->id?>" value="1">
                                                            <label for="<?= $inputApproveName ?>"></label>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>

                                            <?php endforeach; ?>
                                        </table>
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



<div class="bottomBtn bottom-25 right-25 permission-write">
    <button type="button" class=" btn btn-primary btn-round btn-outline-dashed font-bold permission-write save-form" style="letter-spacing:1px;" onclick="customStore({'formId':'empPermission','fnsave':'saveAppPermission'});">SAVE PERMISSION</button>
</div>

<?php $this->load->view('includes/footer'); ?>
<script src="<?php echo base_url(); ?>assets/js/custom/emp-permission.js?v=<?= time() ?>"></script>
