<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
	<?php $this->load->view('top_css'); ?>
	<!-- Custom CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'common/assets/extra-libs/multicheck/multicheck.css';?>">
	<link href="<?php echo base_url().'common/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css';?>" rel="stylesheet">
	<title><?php echo comp_name; ?> | Profile</title>
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
					<h4 class="page-title">Profile</h4>
					<div class="ml-auto text-right">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page">Profile</li>
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
						<form class="form-horizontal" method="post" action="<?php echo base_url().'changeprofile'; ?>">
							<?php //print_obj($user_data);die; ?>
                                <div class="card-body">
                                    <h4 class="card-title">Personal Info</h4>
                                    <div class="form-group row">
                                        <label for="fname" class="col-sm-3 text-right control-label col-form-label">Full Name</label>
                                        <div class="col-sm-9">
                                        	<input type="hidden" name="user_id" value="<?php echo ($user_data)?$user_data['userid']:''; ?>">
                                            <input type="text" class="form-control" name="full_name" placeholder="Full Name.." value="<?php echo ($user_data)?$user_data['fullname']:''; ?>" required="">
                                        </div>
                                    </div>
                                   
                                   <?php 
                                        if(!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1 && $this->session->userdata('usergroup')==1){
                                        ?>
                                        <div class="form-group row">
                                         <label for="lname" class="col-sm-3 text-right control-label col-form-label">User Group</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="user_group" name="user_group" >
                                            	<option value="0">- Select -</option>
                                            	<option value="1" <?php echo ($user_data && $user_data['user_group']==1)?'selected':''; ?>>SuperAdmin</option>
                                            	<option value="2" <?php echo ($user_data && $user_data['user_group']==2)?'selected':''; ?>>Admin</option>
                                            	<option value="3" <?php echo ($user_data && $user_data['user_group']==3)?'selected':''; ?>>User</option>
                                            </select>
                                            </div>
                                    	  </div>
                                    <?php } else{ } ?>
                                       
                                    <div class="form-group row">
                                        <label for="lname" class="col-sm-3 text-right control-label col-form-label">User Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="user_name" name="user_name" placeholder="User Name.." value="<?php echo ($user_data)?$user_data['username']:''; ?>" required="">
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
                                    <p><i class="icofont-login"></i> <b>Last Login:</b> <?php echo ($user_data)?$user_data['lastlogin']:''; ?> <i class="icofont-computer"></i> <b>Last Login IP:</b> <?php echo ($user_data)?$user_data['lastloginip']:''; ?> <i class="icofont-ui-clock"></i> <b>Last Updated:</b> <?php echo ($user_data)?$user_data['lastupdated']:''; ?></p>
                                </div>
                                <div class="border-top">
                                    <div class="card-body">
                                        <button type="submit" id="submit" class="btn btn-primary">Submit</button>
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
                $('#chk_username').html('<i class="icofont-tick-boxed"></i> Current username');
                $("#chk_username").css("color", "green");
                $('#submit').attr("disabled", false);
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
