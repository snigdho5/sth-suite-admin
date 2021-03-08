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
							<h5 class="card-title"><?php echo $page_title; ?> <button type="button" class="btn badge badge-pill badge-success" onclick="location.href='<?php echo base_url().'addleadsource'; ?>'">Add <?php echo $page_title; ?></button></h5>
							<div class="table-responsive">
								<table id="zero_config" class="table table-striped table-bordered">
									<thead>
									<tr class="textcen">
										<th>Sl</th>
										<th>Created On</th>
                                        <th>Orion Lead Source</th>
                                        <th>Business Model</th>
										<?php if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1 && $this->session->userdata('usergroup')==1) { 
												?>
										<th>Action</th>
										<?php } ?>

									</tr>
									</thead>
									<tbody class="textcen">
									<?php
									if(!empty($leadsource_data)){
										//print_obj($leadsource_data);die;
										$sl=1;
										foreach($leadsource_data as $key => $val){
											?>
											<tr>
												<td><?php echo $sl; ?></td>
												<td><?php echo $val['added_dtime']; ?></td>
												<td><?php echo $val['orion_lead_source_c']; ?></td>
												<td><?php echo $val['business_model_c']; ?></td>
												<?php if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1 && $this->session->userdata('usergroup')==1) {  ?>
													<td>
														<button class="btn btn-cyan btn-sm" type="button" onclick="location.href='<?php echo base_url().'editleadsource/'.$val['olsm_id']; ?>'"><i class="icofont-pencil-alt-2"></i></button>
														<button type="button" class="btn btn-danger btn-sm del_ld" data-ldid="<?php echo $val['olsm_id']; ?>" data-ldname="<?php echo $val['orion_lead_source_c']; ?>"><i class="fas fa-trash-alt"></i></button>
													</td>
												<?php } ?>
												
											</tr>
											<?php
											$sl++;
										}
									}
									else{
										if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1 && $this->session->userdata('usergroup')==1) { 
										?>
										<tr><td colspan="4">No data found</td></tr>
									<?php
										}else{
											?>
											<tr><td colspan="3">No data found</td></tr>
											<?php
											}
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
	
	$(document).on('click', '.del_ld', function(){
		
		var ldid = $(this).attr('data-ldid');
		var ldname = $(this).attr('data-ldname');
		

		conf = confirm('Are you sure to delete '+ldname+'?');
		if(conf){
			$.ajax({

					type:'POST',

					url:'<?php echo base_url();?>deleteleadsource',

					data:{ldid:ldid},

					success:function(d){

						if(d.deleted=='success'){

							alert('Lead Source deleted!');
							window.location.reload();

						}
						else if(d.deleted=='not_exists'){

							alert('Lead Source not exists!');

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
