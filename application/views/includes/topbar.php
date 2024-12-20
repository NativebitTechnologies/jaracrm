<!--  BEGIN NAVBAR  -->
<div class="header-container">
	<header class="header navbar navbar-expand-sm expand-header">
		<a href="javascript:void(0);" class="sidebarCollapse">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
			<!--<h5 class="page_title"><?=(!empty($headData->pageTitle)) ? $headData->pageTitle : SITENAME?></h5>-->
		</a>
		<span class="page_title"><?=(!empty($headData->pageTitle)) ? $headData->pageTitle : SITENAME?></span>
		
		<ul class="navbar-item flex-row ms-lg-auto ms-0">
			
			<li class="nav-item dropdown notification-dropdown">
				<a href="javascript:void(0);" class="nav-link dropdown-toggle" id="notificationDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg><span class="badge badge-success"></span>
				</a>

				<div class="dropdown-menu position-absolute" aria-labelledby="notificationDropdown">
					<div class="drodpown-title message">
						<h6 class="d-flex justify-content-between"><span class="align-self-center">Messages</span> <span class="badge badge-primary">9 Unread</span></h6>
					</div>
					<div class="notification-scroll">
						<div class="dropdown-item">
							<div class="media server-log">
								<img src="<?=base_url();?>assets/images/users/user_default.png" class="img-fluid me-2" alt="avatar">
								<div class="media-body">
									<div class="data-info">
										<h6 class="">Kara Young</h6>
										<p class="">1 hr ago</p>
									</div>
									
									<div class="icon-status">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
									</div>
								</div>
							</div>
						</div>
						
						<div class="drodpown-title notification mt-2">
							<h6 class="d-flex justify-content-between"><span class="align-self-center">Notifications</span> <span class="badge badge-secondary">16 New</span></h6>
						</div>

						<div class="dropdown-item">
							<div class="media server-log">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-server"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6" y2="6"></line><line x1="6" y1="18" x2="6" y2="18"></line></svg>
								<div class="media-body">
									<div class="data-info">
										<h6 class="">Server Rebooted</h6>
										<p class="">45 min ago</p>
									</div>

									<div class="icon-status">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
									</div>
								</div>
							</div>
						</div>
						
					</div>
				</div>
				
			</li>

			<li class="nav-item dropdown user-profile-dropdown  order-lg-0 order-1">
				<a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<div class="avatar-container">
						<div class="avatar avatar-sm avatar-indicators avatar-online">
							<img alt="avatar" src="<?=base_url();?>assets/images/users/user_default.png" class="rounded-circle">
						</div>
					</div>
				</a>

				<div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
					<div class="user-profile-section">
						<div class="media mx-auto">
							<div class="emoji me-2">
								&#x1F44B;
							</div>
							<div class="media-body">
								<h5><?=$userName?></h5>
								<p><?=$userRoleName?></p>
							</div>
						</div>
					</div>
					<div class="dropdown-item">
						<a href="user-profile.html">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> <span>Profile</span>
						</a>
					</div>
					<div class="dropdown-item">
						<a href="app-mailbox.html">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-inbox"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path></svg> <span>Inbox</span>
						</a>
					</div>
					<div class="dropdown-item">
						<a href="auth-boxed-lockscreen.html">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg> <span>Lock Screen</span>
						</a>
					</div>
					<div class="dropdown-item">
						<a href="<?=base_url('login/logout')?>">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> <span>Log Out</span>
						</a>
					</div>
				</div>
				
			</li>
		</ul>
	</header>
</div>
<!--  END NAVBAR  -->