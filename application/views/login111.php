
<!DOCTYPE html>
<html lang="en" >

<head>
	<title>Login - <?=(!empty(SITENAME))?SITENAME:""?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/x-icon" href="<?=base_url();?>assets/images/favicon.png"/>
    <link href="<?=base_url();?>assets/src/login_style.css" rel="stylesheet" type="text/css" />
	
	<!--Google Font-->
    <!--<link href="<?=base_url();?>assets/src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />-->
	<link href="https://fonts.googleapis.com/css?family=Ubuntu:500" rel="stylesheet">
	<!--Jquery CDN-->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body translate="no">
	<section data-identity="Batman">
		<blockquote>
		I am
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
	<div class="panda-login">
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
			<h1>Panda Login</h1>
			<div class="form-group">
				<input type="text" name="user_name" id="user_name" class="form-control">
				<label class="form-label">Username</label>
			</div>
			<div class="form-group">
				<input type="password" name="password" id="password" class="form-control">
				<label class="form-label">Password</label>
				<p class="alert">Invalid Credentials..!!</p>
				<button class="btn">Login </button>
			</div>
		</form>
	</div>
	<!--
		<div class="row">
			<div class="col-60 text-animate">
				<section data-identity="Batman">
					<blockquote>
					I am
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
			<div class="col-40 panda-login">
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
					<h1>Panda Login</h1>
					<div class="form-group">
						<input type="text" name="user_name" id="user_name" class="form-control">
						<label class="form-label">Username</label>
					</div>
					<div class="form-group">
						<input type="password" name="password" id="password" class="form-control">
						<label class="form-label">Password</label>
						<p class="alert">Invalid Credentials..!!</p>
						<button class="btn">Login </button>
					</div>
				</form>
			</div>
		</div>
	-->
	
<script  src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
	var index = 0;
	var data= ["Batman", "your father", "no man", "Groot", "Spartacus", "Julius fucking Caesar", "no one", "Dracula", "Lord Voldemort", "the black wizards", "whatever you say I am, if I wasn't, then why would I say I am ? In the news, the papers everyday I am, radio won't even play my jam.", "the one, I'm the one! "];

	var span= document.querySelector('span');
	var section= document.querySelector('section');

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

	$(document).ready(function(){
	});
	$('#password').focusin(function(){
	  $('form').addClass('up')
	});
	$('#password').focusout(function(){
	  $('form').removeClass('up')
	});

	// Panda Eye move
	$(document).on( "mousemove", function( event ) {
	  var dw = $(document).width() / 15;
	  var dh = $(document).height() / 15;
	  var x = event.pageX/ dw;
	  var y = event.pageY/ dh;
	  $('.eye-ball').css({
		width : x,
		height : y
	  });
	});

	// validation


	$('.btn').click(function(){
	  $('form').addClass('wrong-entry');
		setTimeout(function(){ 
		   $('form').removeClass('wrong-entry');
		 },3000 );
	});
</script>

  
</body>

</html>
