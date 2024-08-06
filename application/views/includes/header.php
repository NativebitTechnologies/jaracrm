<!DOCTYPE html>
<html dir="ltr" lang="en">
<?php
    $this->load->view('includes/headerfiles');
?>
	<body class="layout-boxed alt-menu" layout="full-width">
		
		<!-- BEGIN LOADER -->
		<div id="load_screen">
			<div class="loader">
				<div class="loader-content">
					<div class="spinner-grow align-self-center"></div>
				</div>
			</div>
		</div>
		<!--  END LOADER -->
		
		<!--  BEGIN NAVBAR  -->
		<?php $this->load->view('includes/topbar'); ?>
		<!--  END NAVBAR  -->
		
		<!--  BEGIN MAIN CONTAINER  -->
		<div class="main-container sidebar-closed sidebar-closed" id="container">
			<div class="overlay"></div>
			<div class="search-overlay"></div>
			
			<!--  BEGIN SIDEBAR  -->
			<?php $this->load->view('includes/sidebar'); ?>
			<!--  END SIDEBAR  -->
			