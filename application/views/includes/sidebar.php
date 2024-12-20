<div class="sidebar-wrapper sidebar-theme">
	<nav id="sidebar">

		<div class="navbar-nav theme-brand flex-row  text-center">
			<div class="nav-logo">
				<div class="nav-item theme-logo">
					<a href="<?=base_url('dashboard')?>">
						<img src="<?=base_url();?>assets/images/icon.png" class="navbar-logo" alt="logo">
					</a>
				</div>
				<div class="nav-item theme-text">
					<a href="<?=base_url('dashboard')?>" class="nav-link"> <span class="page_title"><?=SITENAME?></span> </a>
				</div>
			</div>
			<div class="nav-item sidebar-toggle">
				<div class="btn-toggle sidebarCollapse">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>
				</div>
			</div>
		</div>
		<div class="shadow-bottom"></div>
		<ul class="list-unstyled menu-categories" id="mainNav">

			<li class="menu active">
				<a href="<?=base_url('dashboard')?>" aria-expanded="false" class="dropdown-toggle">
					<div class="">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
						<span>Dashboard</span>
					</div>
				</a>
			</li>

			<?=$this->permission->getEmployeeMenus()?>
			
			<li class="menu">
				<a href="<?=base_url('report')?>" aria-expanded="false" class="dropdown-toggle">
					<div class="">
						<?=getIcon('file_text')?>
						<span>Reports</span>
					</div>
				</a>
			</li>
		</ul>
		
	</nav>

</div>
