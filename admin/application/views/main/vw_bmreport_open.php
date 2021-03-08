<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
	<?php $this->load->view('top_css'); ?>
	<!-- Custom CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>common/assets/extra-libs/multicheck/multicheck.css">
	<link href="<?php echo base_url(); ?>common/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>common/assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
		<?php //$this->load->view('header_main'); 
		?>
		<!-- End Topbar header -->
		<!-- Left Sidebar - style you can find in sidebar.scss  -->
		<?php //$this->load->view('sidebar_main'); 
		?>
		<!-- End Left Sidebar - style you can find in sidebar.scss  -->
		<!-- ============================================================== -->
		<div class="page-wrapper" style="margin-left: 0px;">
			<!-- ============================================================== -->
			<div class="page-breadcrumb" style="display: none;">
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

							<form class="form-horizontal" id="create_form" method="post">
								<div class="card-body">
									<h4 class="card-title"><?php echo $page_title; ?></h4>

									<div class="form-group row">
										<label for="fname" class="col-sm-2 text-left control-label col-form-label"> Date From:</label>
										<div class="col-sm-4">
											<input type="hidden" class="form-control" name="owner" id="owner" value="<?php echo $owner; ?>">
											<input type="text" class="form-control date_from" id="datepicker-autoclose" name="date_from" placeholder="mm/dd/yyyy" autocomplete="off" required>
										</div>
										<label for="fname" class="col-sm-2 text-left control-label col-form-label"> Date To:</label>
										<div class="col-sm-4">
											<input type="text" class="form-control date_to" id="datepicker-autoclose2" name="date_to" placeholder="mm/dd/yyyy" autocomplete="off" required>
										</div>
									</div>

									<!-- <div class="form-group row">
										<label for="fname" class="col-sm-3 text-left control-label col-form-label"> Business Model:</label>
										<div class="col-sm-9">
											<select class="form-control" name="business_model" required>
												<option value="b2b">B2B</option>
												<option value="b2c">B2C</option>
												<option value="others">Others</option>
											</select>
										</div>
									</div> -->


								</div>
								<div class="border-top">
									<div class="card-body">
										<button type="submit" id="submit" class="btn btn-success submit-btn">Submit</button>
										<span id="chk_msg2" style="display: none;"></span>
									</div>
								</div>
							</form>


							<div class="table-responsive table-div" style="display: none;">
								<span class="show-date-range" style="display: none; margin-left:5px;"></span>
								<button type="button" class="btn btn-sm btn-primary export-btn" style="display: none;">Export <i class="icofont-file-excel"></i></button>
								<table id="zero_config" class="table table-striped table-bordered">
									<thead>
										<tr class="textcen">
											<th>Sl</th>
											<th>Business Model</th>
											<th>Owner</th>
											<th>Total Leads</th>
											<th>Total Not Called Leads</th>
											<th>Total Called Leads</th>
											<th>Total Converted Leads</th>
										</tr>
									</thead>
									<tbody class="textcen table-data">

									</tbody>
								</table>
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
	<script src="<?php echo base_url(); ?>common/assets/extra-libs/multicheck/datatable-checkbox-init.js"></script>
	<script src="<?php echo base_url(); ?>common/assets/extra-libs/multicheck/jquery.multicheck.js"></script>
	<script src="<?php echo base_url(); ?>common/assets/extra-libs/DataTables/datatables.min.js"></script>
	<script src="<?php echo base_url(); ?>common/assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>


	<script>
		// $('#zero_config').DataTable();
		jQuery('#datepicker-autoclose').datepicker({
			autoclose: true,
			todayHighlight: true,
			endDate: "today",
			orientation: "bottom"
		});
		jQuery('#datepicker-autoclose2').datepicker({
			autoclose: true,
			todayHighlight: true,
			endDate: "today",
			orientation: "bottom"
		});

		$(document).ready(function() {
			//autoload table
			autoLoadTable();

			$("form[id='create_form']").submit(function(e) {

				$('.submit-btn').prop('disabled', true);
				$('#chk_msg2').show();
				$('#chk_msg2').html('<i class="fa fa-spinner fa-spin" style="font-size:24px"></i>');

				var formData = new FormData($(this)[0]);
				var htmld = '';
				var counter = 1;

				$.ajax({
					url: "<?php echo base_url(); ?>addons/getbmreport",
					type: "POST",
					data: formData,
					success: function(d) {
						$('.submit-btn').prop('disabled', false);
						$('.show-date-range').show();
						$('.show-date-range').html('<b>Selected Date Range:</b> ' + $('.date_from').val() + ' - ' + $('.date_to').val());

						if (d.export == 'success' && d.downpath != 0) {
							$('#chk_msg2').hide();
							$('.export-btn').show();
							$('.table-div').show();
							$('.export-btn').attr('onClick', "location.href='" + d.downpath + "'");

							$('#zero_config').DataTable().destroy();
							$('#zero_config tbody').empty();

							if (d.tableData != "") {
								$('.table-data').html(htmld);
								$.each(d.tableData, function(index, value) {
									//console.log(value.Business_model_c);

									htmld += '<tr>';
									htmld += '<td>' + counter + '</td>';
									htmld += '<td>' + (value.Business_model_c).toUpperCase() + '</td>';
									htmld += '<td>' + value.owner_c + '</td>';
									htmld += '<td>' + value.total_leads + '</td>';
									htmld += '<td>' + value.total_not_called_leads + '</td>';
									htmld += '<td>' + value.total_called_leads + '</td>';
									htmld += '<td>' + value.total_converted_leads + '</td>';

									htmld += '</tr>';
									counter++;
								});

								$('.table-data').html(htmld);
								$('#zero_config').DataTable();
							} else {
								htmld += '<tr><td colspan="8">No data found!!</td></tr>';
								$('.table-data').html(htmld);
								$('#zero_config').DataTable();
							}
						} else if (d.export == 'failure' && d.downpath == 0) {
							$('#chk_msg2').html('<i class="icofont-close-squared-alt" style="color:red;"></i> No data found!');
							$('.export-btn').hide();
							$('.table-div').hide();
						} else {
							$('#chk_msg2').html('<i class="icofont-close-squared-alt"></i> Something Went Wrong!');
							$('.export-btn').hide();
							$('.table-div').hide();
						}
					},
					cache: false,
					contentType: false,
					processData: false
				});

				e.preventDefault();
			});
		});

		function autoLoadTable() {
			$('.submit-btn').prop('disabled', true);
			$('#chk_msg2').show();
			$('#chk_msg2').html('<i class="fa fa-spinner fa-spin" style="font-size:24px"></i>');

			var htmld = '';
			var counter = 1;
			var owner = $('#owner').val();

			//get current month
			var today = new Date();
			var dd = String(today.getDate()).padStart(2, '0');
			var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
			var yyyy = today.getFullYear();

			var date_to = mm + '/' + dd + '/' + yyyy;
			var date_from = mm + '/' + '01' + '/' + yyyy;

			$.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>addons/getbmreport",
				data: {
					date_from: date_from,
					date_to: date_to,
					owner: owner
				},

				success: function(d) {
					$('.submit-btn').prop('disabled', false);
					$('.show-date-range').show();
					$('.show-date-range').html('<b>Selected Date Range:</b> ' + date_from + ' - ' + date_to);

					if (d.export == 'success' && d.downpath != 0) {
						$('#chk_msg2').hide();
						$('.export-btn').show();
						$('.table-div').show();
						$('.export-btn').attr('onClick', "location.href='" + d.downpath + "'");
						$('#zero_config').DataTable().destroy();
						$('#zero_config tbody').empty();

						if (d.tableData != "") {
							$.each(d.tableData, function(index, value) {
								//console.log(value.Business_model_c);

								htmld += '<tr>';
								htmld += '<td>' + counter + '</td>';
								htmld += '<td>' + (value.Business_model_c).toUpperCase() + '</td>';
								htmld += '<td>' + value.owner_c + '</td>';
								htmld += '<td>' + value.total_leads + '</td>';
								htmld += '<td>' + value.total_not_called_leads + '</td>';
								htmld += '<td>' + value.total_called_leads + '</td>';
								htmld += '<td>' + value.total_converted_leads + '</td>';

								htmld += '</tr>';
								counter++;
							});

							$('.table-data').html(htmld);
							$('#zero_config').DataTable();
						} else {
							htmld += '<tr><td colspan="8">No data found</td></tr>';
							$('.table-data').html(htmld);
							$('#zero_config').DataTable();
						}
					} else if (d.export == 'failure' && d.downpath == 0) {
						$('#chk_msg2').html('<i class="icofont-close-squared-alt" style="color:red;"></i> No data found!');
						$('.export-btn').hide();
						$('.table-div').hide();
					} else {
						$('#chk_msg2').html('<i class="icofont-close-squared-alt"></i> Something Went Wrong!');
						$('.export-btn').hide();
						$('.table-div').hide();
					}
				}
			});
		}
	</script>

</body>

</html>