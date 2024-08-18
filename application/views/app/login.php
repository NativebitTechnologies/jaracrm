<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, minimal-ui, viewport-fit=cover">
	<meta name="theme-color" content="#B7E2F5">
	<meta name="author" content="DexignZone"> 
	<meta name="robots" content="index, follow"> 
    <meta name="keywords" content="android, ios, mobile, application template, progressive web app, ui kit, multiple color, dark layout">
	<meta name="description" content="W3Grocery - Complete solution of some popular application like - grocery app, shop vendor app, driver app and progressive web app">
	<meta property="og:title" content="W3Grocery: Pre-Build Grocery Mobile App Template ( Bootstrap 5 + PWA )">
	<meta property="og:description" content="W3Grocery - Complete solution of some popular application like - grocery app, shop vendor app, driver app and progressive web app">
	<meta property="og:image" content="https://w3grocery.dexignzone.com/xhtml/social-image.png">
	<meta name="format-detection" content="telephone=no">
	
	<!-- Favicons Icon -->
	<link rel="shortcut icon" type="image/x-icon" href="<?=base_url('assets/app/images/favicon.png')?>">
    
    <!-- Title -->
	<title>JARA CRM</title>
    
    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="<?=base_url('assets/app/css/style.css')?>">
    <link rel="stylesheet" type="text/css" href="<?=base_url('assets/app/css/jp_helper.css')?>">
    

</head>   
<body data-theme-color="color-teal" class="login_page">
<div class="page-wraper">
    
	<!-- Preloader -->
	<div id="preloader">
		<div class="loader">
			<span></span>
			<span></span>
			<span></span>
			<span></span>
			<span></span>
		</div>
	</div>
    <!-- Preloader end-->

    <!-- Page Content -->
    <div class="page-content">
        <!-- Banner -->
        <div class="banner-wrapper mb-0 py-2">
            <div class="container inner-wrapper">
                <img src="<?=base_url('assets/app/images/logo.png')?>" style="width:50%" alt="/">
            </div>
        </div>
		<div class="text-center bg-warning text-dark fw-bold p-1">JUST AUTOMIZING ROUTINE ACTIVITIES</div>
        <!-- Banner End -->
		<div class="container">
			<div class="card dz-form-group login_box">
				<div class="card-header d-block border-0 text-center">
					<h2 class="title mb-0">Please login to your account</h2>
				</div>
				<div class="card-body">
					<form id="loginform" action="<?=base_url('app/login/auth');?>" method="POST">
						<div class="mb-3 input-group input-mini">
							<span class="input-group-text"><i class="fa fa-user"></i></span>
							<input type="text" class="form-control" placeholder="User ID" id="user_name" name="user_name">
						</div>
						<?=form_error('user_name')?>
						<div class="mb-3 input-group input-mini">
							<span class="input-group-text"><i class="fa fa-lock"></i></span>
							<input type="password" class="form-control dz-password" placeholder="Password" id="user_psw" name="user_psw">
							<span class="input-group-text show-pass"> 
								<i class="fa fa-eye-slash"></i>
								<i class="fa fa-eye"></i>
							</span>
						</div>
						<?=form_error('user_psw')?>
						<div class="input-group">
							<button type="submit" class="btn mt-2 btn-warning w-100">SIGN IN</a>
						</div>
					</form>
				</div>
			</div>
		</div>
    </div>
    <!-- Page Content End -->
    
</div>
<!--**********************************
    Scripts
***********************************-->
<script src="<?=base_url('assets/app/js/jquery.js')?>"></script>
<script src="<?=base_url('assets/app/vendor/bootstrap/js/bootstrap.bundle.min.js')?>"></script>
<script src="<?=base_url('assets/app/vendor/swiper/swiper-bundle.min.js')?>"></script><!-- Swiper -->
<script src="<?=base_url('assets/app/js/dz.carousel.js')?>"></script><!-- Swiper -->
<script src="<?=base_url('assets/app/js/settings.js')?>"></script>
<script src="<?=base_url('assets/app/js/custom.js')?>"></script>
</body>
</html>