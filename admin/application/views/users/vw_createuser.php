<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
	<?php $this->load->view('top_css'); ?>
	<!-- Custom CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'common/assets/extra-libs/multicheck/multicheck.css';?>">
	<link href="<?php echo base_url().'common/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css';?>" rel="stylesheet">
	<title><?php echo comp_name; ?> | Add User</title>
</head>

<body>
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
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ============================================================== -->
<div id="main-wrapper">
	<!-- ============================================================== -->
	<!-- Topbar header - style you can find in pages.scss -->
	<?php $this->load->view('header_main'); ?>
	<!-- End Topbar header -->
	<!-- Left Sidebar - style you can find in sidebar.scss  -->
	<?php $this->load->view('sidebar_main'); ?>
	<!-- End Left Sidebar - style you can find in sidebar.scss  -->
	<!-- ============================================================== -->
	<div class="page-wrapper">
		<!-- ============================================================== -->
		<div class="page-breadcrumb">
			<div class="row">
				<div class="col-12 d-flex no-block align-items-center">
					<h4 class="page-title">Add User</h4>
					<div class="ml-auto text-right">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page">Add User</li>
							</ol>
						</nav>
					</div>
				</div>
			</div>
		</div>
		<!-- ============================================================== -->
		<div class="container-fluid">
			<!-- ============================================================== -->
			<div class="row">
				<div class="col-12">
					<div class="card">
						<?php
					if (isset($update_success) && $update_success != '') {
						echo "<p><i class=\"icofont-tick-boxed\" style=\"color:green\"></i> Status: ".$update_success."</p>";
					}elseif (isset($update_failure) && $update_failure != '') {
						echo "<p><i class=\"fas fa-exclamation-triangle\" style=\"color:yellow\"></i> Error: ".$update_failure."</p>";
					}else{
						//echo "<p style='color:#f5f2f0'><i class=\"fas fa-exclamation-triangle\" style=\"color:yellow\"></i> Something went wrong!</p>";
					}
					?>
						<form class="form-horizontal" id="create_user_form">
							<?php //print_obj($user_data);die; ?>
                                <div class="card-body">
                                    <h4 class="card-title">Craete New User <button type="button" class="btn badge badge-pill badge-success" onclick="location.href='<?php echo base_url().'users'; ?>'">Users List</button></h4>
                                    <div class="form-group row">
                                        <label for="fname" class="col-sm-3 text-right control-label col-form-label">Full Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Full Name.." required="">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="lname" class="col-sm-3 text-right control-label col-form-label">User Group</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="user_group" name="user_group">
                                            	<option value="0">- Select -</option>
                                            	<option value="2">Admin</option>
                                            	<option value="3">User</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="lname" class="col-sm-3 text-right control-label col-form-label">User Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="user_name" name="user_name" placeholder="User Name.." required="">
                                            <label id="chk_username" style="display: none;"></label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="password" class="col-sm-3 text-right control-label col-form-label">Password</label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="password" name="password" placeholder="Password.."  required="">
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="button" class="btn btn-success generate_pass">Generate</button>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group row">
                                        <label for="password" class="col-sm-3 text-right control-label col-form-label">Company</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="password" placeholder="Company Name Here">
                                        </div>
                                    </div> -->
                                    
                                </div>
                                <div class="border-top">
                                    <div class="card-body">
                                        <button type="button" id="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
					</div>
				</div>
			</div>
			<!-- ============================================================== -->
		</div>
		<!-- ============================================================== -->
		<!-- End Container fluid  -->
		<!-- ============================================================== -->
		<!-- ============================================================== -->
		<!-- footer -->
		<!-- ============================================================== -->
		<?php $this->load->view('footer'); ?>
		<!-- ============================================================== -->
		<!-- End footer -->
		<!-- ============================================================== -->
	</div>
	<!-- ============================================================== -->
	<!-- End Page wrapper  -->
	<!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<?php $this->load->view('bottom_js'); ?>
<!-- this page js -->
<script src="<?php echo base_url().'common/assets/extra-libs/multicheck/datatable-checkbox-init.js';?>"></script>
<script src="<?php echo base_url().'common/assets/extra-libs/multicheck/jquery.multicheck.js';?>"></script>
<script src="<?php echo base_url().'common/assets/extra-libs/DataTables/datatables.min.js';?>"></script>
<script>
	/****************************************
	 *       Basic Table                   *
	 ****************************************/
	$('#zero_config').DataTable();

	$("#user_name").keyup(function(){
    var username = $('#user_name').val();
    if(username!=''){
    	$.ajax({
	    type: "POST",
	    url: "<?php echo base_url().'duplicate_check_un'; ?>",
	    data:{user_name:username},
	    
	    success: function(d){
	        if(d.user_exists == 1)
	        {
	            $('#chk_username').show();
	            $('#chk_username').html('<i class="icofont-close-squared-alt"></i> Username already exists..!!');
	            $("#chk_username").css("color", "red");
	            $('#submit').attr("disabled", true);
	            return false;
	        }
	        else if(d.user_exists == 3)
	        {
	            $('#chk_username').show();
	            $('#chk_username').html('<i class="icofont-close-squared-alt"></i> You can&apos;t use your current username!');
	            $("#chk_username").css("color", "red");
	            $('#submit').attr("disabled", true);
	        }else
	        {
	            $('#chk_username').show();
	            $('#chk_username').html('<i class="icofont-tick-boxed"></i> Username available.');
	            $("#chk_username").css("color", "green");
	            $('#submit').attr("disabled", false);
	        }
	     }
	    });
    }else{
    	$('#chk_username').hide();
    }
   
  });

	$("#submit").click(function(){

		var full_name = $('#full_name').val();
		var user_group = $('#user_group').val();
		var user_name = $('#user_name').val();
		var password = $('#password').val();

				$.ajax({

					type:'POST',

					url:'<?php echo base_url();?>createuser',

					data:{full_name:full_name,user_group:user_group,user_name:user_name,password:password},

					success:function(d){

						if(d.user_added=='success'){

							alert('User added!');
							window.location.reload();

						}
						else if(d.user_added=='already_exists'){

							alert('User already exists!');

						}else{
							alert('Something went wrong!');
						}

					}

				});


		});

	$('.generate_pass').click (function makeid(){

		   var text = "";

		   var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";



		   for( var i=0; i < 7; i++ )

		       text += possible.charAt(Math.floor(Math.random() * possible.length));

		     $('#password').val(text);



		});
</script>

</body>

</html>
