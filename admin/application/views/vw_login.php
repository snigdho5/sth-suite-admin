
<!DOCTYPE html>
<html dir="ltr">

<head>
	<?php $this->load->view('top_css'); ?>
	<title><?php echo comp_name; ?> | Login</title>
</head>

<body>
<div class="main-wrapper">
	<!-- ============================================================== -->
	<!-- Preloader - style you can find in spinners.css -->
	<!-- ============================================================== -->
	<div class="preloader">
		<div class="lds-ripple">
			<div class="lds-pos"></div>
			<div class="lds-pos"></div>
		</div>
	</div>
	<!-- ============================================================== -->
	<!-- Preloader - style you can find in spinners.css -->
	<!-- ============================================================== -->
	<!-- ============================================================== -->
	<!-- Login box.scss -->
	<!-- ============================================================== -->
	<div class="auth-wrapper d-flex no-block justify-content-center align-items-center bg-dark">
		<div class="auth-box bg-dark border-top border-secondary">
			<div id="loginform">
				<div class="text-center p-t-20 p-b-20">
					<span class="db"><img src="<?php echo base_url().'common/assets/images/logo2.png';?>" alt="logo" /></span><br /><br />
					<h5 id="chk_msg2" style='display: none;'></h5>
				</div>
				<!-- Form -->
				<form class="form-horizontal m-t-20" id="loginform">
					<div class="row p-b-30">
						<div class="col-12">
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text bg-success text-white" id="basic-addon1"><i class="ti-user"></i></span>
								</div>
								<input type="text" class="form-control form-control-lg" placeholder="Username" id="username" name="username" aria-label="Username" aria-describedby="basic-addon1" required="">
							</div>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text bg-warning text-white" id="basic-addon2"><i class="ti-pencil"></i></span>
								</div>
								<input type="password" class="form-control form-control-lg" placeholder="Password" id="password" name="password" aria-label="Password" aria-describedby="basic-addon1" required="">
							</div>
						</div>
					</div>
					<div class="row border-top border-secondary">
						<div class="col-12">
							<div class="form-group">
								<div class="p-t-20">
									<!-- <button class="btn btn-info" id="to-recover" type="button"><i class="fa fa-lock m-r-5"></i> Lost password?</button> -->
									<button class="btn btn-success float-right" type="submit" id="submit">Login</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			
			<!--login form ends-->
			<div id="recoverform">
				<div class="text-center">
					<span class="text-white">Enter your e-mail address below and we will send you instructions how to recover a password.</span>
				</div>
				<div class="row m-t-20">
					<!-- Form -->
					<form class="col-12">
						<!-- email -->
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text bg-danger text-white" id="basic-addon1"><i class="ti-email"></i></span>
							</div>
							<input type="text" class="form-control form-control-lg" placeholder="Email Address" aria-label="Username" aria-describedby="basic-addon1">
						</div>
						<!-- pwd -->
						<div class="row m-t-20 p-t-20 border-top border-secondary">
							<div class="col-12">
								<a class="btn btn-success" href="#" id="to-login" name="action">Back To Login</a>
								<button class="btn btn-info float-right" type="button" name="action">Recover</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- ============================================================== -->
</div>
<!-- ============================================================== -->

<?php $this->load->view('bottom_js'); ?>
<!-- This page plugin js -->
<!-- ============================================================== -->
<script>

	$('[data-toggle="tooltip"]').tooltip();
	$(".preloader").fadeOut();
	// ==============================================================
	// Login and Recover Password
	// ==============================================================
	$('#to-recover').on("click", function() {
		$("#loginform").slideUp();
		$("#recoverform").fadeIn();
	});
	$('#to-login').click(function(){

		$("#recoverform").hide();
		$("#loginform").fadeIn();
	});

	$("form[id='loginform']").submit(function(e) {

		var formData = new FormData($(this)[0]);

		$.ajax({
			url: "<?php echo base_url();?>chk_login",
			type: "POST",
			data: formData,
			success: function (d) {
				if(d.checkedlogin=='rule_error'){

							$('#chk_msg2').show();
				            $('#chk_msg2').html('<b style="color:#f5f2f0;"><i class="icofont-warning" style="color:yellow;"></i> ERROR: '+d.errors+'</b>');

						}
						else if(d.checkedlogin=='mismatch_error'){

							$('#chk_msg2').show();
				            $('#chk_msg2').html('<b style="color:#f5f2f0;"><i class="icofont-warning" style="color:yellow"></i> ERROR: Mismatch in Username / Password!</b>');

						}else if(d.checkedlogin=='success'){

							window.location.href = '<?php echo base_url();?>dashboard';

						}else{
							alert('Something went wrong!');
						}
			},
			cache: false,
			contentType: false,
			processData: false
		});

		e.preventDefault();
	});

</script>

</body>

</html>
