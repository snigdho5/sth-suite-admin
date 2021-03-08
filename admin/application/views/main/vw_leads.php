<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
	<?php $this->load->view('top_css'); ?>
	<!-- Custom CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'common/assets/extra-libs/multicheck/multicheck.css';?>">
	<link href="<?php echo base_url().'common/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css';?>" rel="stylesheet">
	<title><?php echo comp_name; ?> | <?php echo $page_title; ?></title>
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
					<h4 class="page-title"><?php echo $page_title; ?></h4>
					<div class="ml-auto text-right">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page"><?php echo $page_title; ?></li>
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
						<div class="card-body">
							<h5 class="card-title">Last 100 <?php echo $page_title; ?> List
							<!-- <a class="pull-right btn btn-primary" href="<?php echo base_url()?>exportPartner"><i class="icofont-file-excel"></i> Export</a> -->
						</h5>
							<div class="table-responsive">
								<table id="zero_config" class="table table-striped table-bordered">
									<thead>
									<tr class="textcen">
										<th>Sl</th>
										<th>Created On</th>
										<th>Name</th>
										<th>Phone</th>
										<th>Email</th>
										<th>Owner</th>
										<th>Business Model</th>

									</tr>
									</thead>
									<tbody class="textcen">
									<?php
									if(!empty($leads_data)){
										//print_obj($leads_data);
										$sl=1;
										foreach($leads_data as $key => $val){
											?>
											<tr>
												<td><?php echo $sl; ?></td>
												<td><?php echo $val['date_entered']; ?></td>
												<td><?php echo $val['name']; ?></td>
												<td><?php echo $val['phone']; ?></td>
												<td><?php echo $val['lead_email_c']; ?></td>
												<td><?php echo $val['owner_c']; ?></td>
												<td><?php echo $val['business_model_c']; ?></td>
											
											</tr>
											<?php
											$sl++;
										}
									}
									else{
										?>
										<tr><td colspan="5">No data found</td></tr>
									<?php
										}
									?>
									</tbody>
								</table>
							</div>

						</div>
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

$(document).ready(function(){
	  $(".del_part").click(function(){

		var partid = $(this).attr('data-partid');
		var fullname = $(this).attr('data-fullname');

		conf = confirm('Are you sure to delete '+fullname+'?');
		if(conf){
			$.ajax({

					type:'POST',

					url:'<?php echo base_url();?>deletepartner',

					data:{partid:partid},

					success:function(d){

						if(d.deleted=='success'){

							alert('Deleted!');
							window.location.reload();

						}
						else if(d.deleted=='not_exists'){

							alert('Does not exists!');

						}else{
							alert('Something went wrong!');
						}

					}

				});
			}
		});	

	

});
</script>

</body>

</html>
