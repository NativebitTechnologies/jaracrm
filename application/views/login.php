<!DOCTYPE html>
<html dir="ltr">
<head>
    <title>Login - <?=(!empty(SITENAME))?SITENAME:""?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/x-icon" href="<?=base_url();?>assets/images/favicon.png"/>
    <link href="<?=base_url();?>assets/layouts/collapsible-menu/css/light/loader.css" rel="stylesheet" type="text/css" />
    <link href="<?=base_url();?>assets/layouts/collapsible-menu/css/dark/loader.css" rel="stylesheet" type="text/css" />
    <script src="<?=base_url();?>assets/layouts/collapsible-menu/loader.js"></script>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="<?=base_url();?>assets/src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?=base_url();?>assets/layouts/collapsible-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
    <link href="<?=base_url();?>assets/layouts/collapsible-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />
    <link href="<?=base_url();?>assets/src/assets/css/light/authentication/auth-cover.css" rel="stylesheet" type="text/css" />
    <link href="<?=base_url();?>assets/src/assets/css/dark/authentication/auth-cover.css" rel="stylesheet" type="text/css" />
    <link href="<?=base_url();?>assets/src/assets/css/light/components/font-icons.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url();?>assets/src/assets/css/dark/components/font-icons.css" rel="stylesheet" type="text/css">
    <link href="<?=base_url();?>assets/src/jp_styles.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link href="<?=base_url();?>assets/src/login_style.css" rel="stylesheet" type="text/css" />


</head>
<body class="form">

    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    <div class="auth-container d-flex">

        <div class="container mx-auto align-self-center mw-100">
    
            <div class="row">
				<div class="col-6 d-lg-flex d-none h-100 my-auto top-0 start-0 text-center justify-content-center flex-column text-animate">
					<section data-identity="Batman">
						<blockquote>
							You can
						<span></span>
						</blockquote>
					</section>
					<div class="pen__lines-wrapper">
						<div class="pen__line"></div>
						<div class="pen__line"></div>
						<div class="pen__line"></div>
						<div class="pen__line"></div>
						<div class="pen__line"></div>
						<div class="pen__line"></div>
						<div class="pen__line"></div>
						<div class="pen__line"></div>
						<div class="pen__line"></div>
						<div class="pen__line"></div>
					</div>
				</div>

                <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-8 col-12 d-flex flex-column align-self-center ms-lg-auto me-lg-0 mx-auto panda-login sh-left">
                    <div class="card">
                        <div class="card-body">
							<div class="row">
								<div class="col-md-12 text-center">
									<img src="<?=base_url()?>assets/images/logo-white.png" align="center" alt="logo" width="45%" />
									<h2 class="mt-3 tagline">Just Automizing Routine Activities</h2>
								</div>
								<div class="col-md-12 mb-3 text-center">
									<div class="panda">
										<div class="ear"></div>
										<div class="face">
											<div class="eye-shade"></div>
											<div class="eye-white">
												<div class="eye-ball"></div>
											</div>
											<div class="eye-shade rgt"></div>
											<div class="eye-white rgt">
												<div class="eye-ball"></div>
											</div>
											<div class="nose"></div>
											<div class="mouth"></div>
										</div>
										<div class="body"> </div>
										<div class="foot">
											<div class="finger"></div>
										</div>
										<div class="foot rgt">
											<div class="finger"></div>
										</div>
									</div>
									<form class="" id="loginform" action="<?=base_url('login/auth');?>" method="post">
										<div class="hand"></div>
										<div class="hand rgt"></div>
										<h1>Sign in to continue</h1>
										<div class="form-group">
											<input type="text" name="user_name" id="user_name" class="form-control" required>
											<label class="form-label">Username</label>
										</div>
										<div class="form-group">
											<input type="password" name="user_psw" id="user_psw" class="form-control" required>
											<label class="form-label">Password</label>
											<p class="alert">Invalid Credentials..!!</p>
											<button class="btn">Login </button>
										</div>
									</form>
								</div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

    </div>
    
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
	<script src="<?=base_url()?>assets/src/jquery/dist/jquery.min.js"></script>
	<script src="<?=base_url();?>assets/src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->


    <script>
		var base_url = '<?=base_url();?>'; 
		var index = 0;
		var data= ["Track customer interactions", "Automize Lead capturing & distribution", "Set reminders and notifications for follow-ups", "Manage sales pipelines & progress", "Forecast sales and track performance against targets", "Track support issues and resolutions", "Analyze customer data and sales performance", "Manage Role-based access control"];

		var span= document.querySelector('span');
		var section= document.querySelector('section');
		
		$(document).ready(function(){
		
		});

		function init() {
		  let txt = document.createTextNode(data[index]);
		  section.dataset.identity = data[index];
		  span.innerText = txt.textContent;
		  index++;
		}

		init();

		setInterval(
		  function(){
			let txt = document.createTextNode(data[index]);
			section.dataset.identity = data[index];
			span.innerText = txt.textContent;
			index++;
			index = index < data.length ?  index++ : 0 ;
		  }
		, 4501);
		
		$('#password').focusin(function(){ $('form').addClass('up'); });
		$('#password').focusout(function(){ $('form').removeClass('up'); });

		// Panda Eye move
		$(document).on( "mousemove", function( event ) {
			var dw = $(document).width() / 15;
			var dh = $(document).height() / 15;
			var x = event.pageX/ dw;
			var y = event.pageY/ dh;
			$('.eye-ball').css({ width : x, height : y });
		});

		// validation


		$('.btn').click(function(){
		  $('form').addClass('wrong-entry');
			setTimeout(function(){ 
			   $('form').removeClass('wrong-entry');
			 },3000 );
		});
		
		/*
        const rmCheck = document.getElementById("rememberMe"),
        emailInput = document.getElementById("user_name");

        if (localStorage.checkbox && localStorage.checkbox !== "") {
            rmCheck.setAttribute("checked", "checked");
            emailInput.value = localStorage.username;
        } else {
            rmCheck.removeAttribute("checked");
            emailInput.value = "";
        }

        function lsRememberMe() {
            if (rmCheck.checked && emailInput.value !== "") {
                localStorage.username = emailInput.value;
                localStorage.checkbox = rmCheck.value;
            } else {
                localStorage.username = "";
                localStorage.checkbox = "";
            }
        }*/
    </script>
</body>

</html>