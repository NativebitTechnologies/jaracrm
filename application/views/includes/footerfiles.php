<script>
	var base_url = '<?=base_url();?>'; 
	var controller = '<?=(isset($headData->controller)) ? $headData->controller : ''?>'; 
	var popupTitle = '<?=POPUP_TITLE;?>';
	var theads = '<?=(isset($tableHeader)) ? $tableHeader[0] : ''?>';
	var textAlign = '<?=(isset($tableHeader[1])) ? $tableHeader[1] : ''?>';
	var srnoPosition = '<?=(isset($tableHeader[2])) ? $tableHeader[2] : 1?>';
	var sortable = '<?=(isset($tableHeader[3])) ? $tableHeader[3] : ''?>';
	var tableHeaders = {'theads':theads,'textAlign':textAlign,'srnoPosition':srnoPosition,'sortable':sortable};

	var device_type = 'DESKTOP';
	var editBtnIcon = '<?=getIcon('edit')?>';
	var deleteBtnIcon = '<?=getIcon('delete')?>';
</script>

<!-- Permission Checking -->
<?php
	$script= "";
	if($permission = $this->session->userdata('emp_permission')):
		if(!empty($headData->pageUrl)):
			$empPermission = $permission[$headData->pageUrl];
			$script .= '<script>
				var permissionRead = "'.$empPermission['is_read'].'";
				var permissionWrite = "'.$empPermission['is_write'].'";
				var permissionModify = "'.$empPermission['is_modify'].'";
				var permissionRemove = "'.$empPermission['is_remove'].'";
				var permissionApprove = "'.$empPermission['is_approve'].'";
			</script>';
			echo $script;
		else:
			$script .= '<script>
				var permissionRead = "1";
				var permissionWrite = "1";
				var permissionModify = "1";
				var permissionRemove = "1";
				var permissionApprove = "1";
			</script>';
			echo $script;
		endif;
	else:
		$script .= '<script>
				var permissionRead = "";
				var permissionWrite = "";
				var permissionModify = "";
				var permissionRemove = "";
				var permissionApprove = "";
			</script>';
		echo $script;
	endif;
?>

<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<script src="<?=base_url()?>assets/src/jquery/dist/jquery.min.js"></script>
<script src="<?=base_url();?>assets/src/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?=base_url();?>assets/src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="<?=base_url();?>assets/src/plugins/src/mousetrap/mousetrap.min.js"></script>
<script src="<?=base_url();?>assets/layouts/collapsible-menu/app.js"></script>
<script src="<?=base_url();?>assets/src/plugins/src/sweetalerts2/sweetalerts2.min.js"></script>
<!--<script src="<?=base_url();?>assets/src/plugins/src/vanillaSelectBox/vanillaSelectBox.js"></script>-->
<script src="<?=base_url();?>assets/src/plugins/src/vanillaSelectBox/vanillaSelectBox1.03.js"></script>
<script src="<?=base_url();?>assets/src/comman-js.js?v<?=time()?>"></script>
<script src="<?=base_url();?>assets/src/lazy-load.js?v="<?=time()?>></script>
<!-- END GLOBAL MANDATORY SCRIPTS -->