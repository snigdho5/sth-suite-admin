<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
	<?php $this->load->view('top_css'); ?>
	<!-- Custom CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>common/assets/extra-libs/multicheck/multicheck.css">
	<link href="<?php echo base_url(); ?>common/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>common/assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>common/assets/libs/select2/dist/css/select2.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css">
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

							<form class="form-horizontal" id="filter_form">
								<div class="card-body">
									<h4 class="card-title"><?php echo $page_title; ?> [*Indian numbers only(+91).<?php echo ($sms_balance != '') ? $sms_balance : 'Api Error' ?>] </h4>

									<div class="form-group row">
										<label for="fname" class="col-sm-2 text-left control-label col-form-label"> Date From:</label>
										<div class="col-sm-4">
											<input type="hidden" class="form-control" name="owner_get" id="owner_get" value="<?php echo $owner; ?>">
											<input type="hidden" class="form-control" name="assigned" id="assigned" value="<?php echo $assigned; ?>">
											<input type="text" class="form-control date_from" id="datepicker-autoclose" name="date_from" placeholder="mm/dd/yyyy" autocomplete="off">
										</div>
										<label for="fname" class="col-sm-2 text-left control-label col-form-label"> Date To:</label>
										<div class="col-sm-4">
											<input type="text" class="form-control date_to" id="datepicker-autoclose2" name="date_to" placeholder="mm/dd/yyyy" autocomplete="off">
										</div>
									</div>

									<div class="form-group row">
										<label for="fname" class="col-sm-2 text-left control-label col-form-label"> Owner:</label>
										<div class="col-sm-4">
											<?php
											if ($owner == 'ALL') {
											?>
												<select class="select2 form-control m-t-15" style="width: 100%; height:36px;" id="owner" name="owner">
													<option value="">Select</option>
													<?php
													if (!empty($owner_data)) {
														foreach ($owner_data as $key => $value) {
													?>
															<option value="<?php echo $value['owner']; ?>"><?php echo $value['owner']; ?></option>
													<?php
														}
													}
													?>
												</select>
												<?php
											} else if ($owner == 'special_user') {
											?>
												<select class="select2 form-control m-t-15" style="width: 100%; height:36px;" id="owner" name="owner">
													<option value="">Select</option>
													<?php
													if (!empty($owner_data)) {
														foreach ($owner_data as $key => $value) {
													?>
															<option value="<?php echo $value['owner']; ?>"><?php echo $value['owner']; ?></option>
													<?php
														}
													}
													?>
												</select>

											<?php
											} else if ($owner == 'assigned_user') {
											?>
												<select class="select2 form-control m-t-15" style="width: 100%; height:36px;" id="owner" name="owner" disabled>
													<option value=""></option>
												</select>

											<?php
											} else {
											?>
												<select class="select2 form-control m-t-15" style="width: 100%; height:36px;" id="owner" name="owner" readonly>
													<option value="<?php echo $owner; ?>"><?php echo $owner; ?></option>
												</select>
											<?php
											}
											?>
										</div>

										<label for="fname" class="col-sm-2 text-left control-label col-form-label"> Business Model:</label>
										<div class="col-sm-4">
											<select class="select2 form-control m-t-15" style="width: 100%; height:36px;" id="business_model" name="business_model">
												<option value="">Select</option>
												<?php
												if (!empty($bm_data)) {
													foreach ($bm_data as $key => $value) {
												?>
														<option value="<?php echo $value['business_model']; ?>"><?php echo $value['business_model']; ?></option>
												<?php
													}
												}
												?>
											</select>
										</div>
									</div>

									<div class="form-group row">
										<label for="fname" class="col-sm-2 text-left control-label col-form-label"> Orion Lead Source:</label>
										<div class="col-sm-4">
											<select class="select2 form-control m-t-15" style="width: 100%; height:36px;" id="orion_lead_source" name="orion_lead_source">

												<option value="">Select</option>
												<?php
												if (!empty($leadsource_data)) {
													foreach ($leadsource_data as $key => $value) {
												?>
														<option value="<?php echo $value['orion_lead_source']; ?>"><?php echo $value['orion_lead_source']; ?></option>
												<?php
													}
												}
												?>
											</select>
										</div>
										<label for="fname" class="col-sm-2 text-left control-label col-form-label">SMS Status :</label>
										<div class="col-sm-4">
											<select class="form-control m-t-15" style="width: 100%; height:36px;" id="sms_status" name="sms_status">
												<option value="">Select</option>
												<option value="1">Sent</option>
												<option value="2">Not Sent</option>
											</select>
										</div>

										<!-- <label for="fname" class="col-sm-2 text-left control-label col-form-label"> Business Model:</label>
										<div class="col-sm-4"></div> -->
									</div>


								</div>
								<div class="border-top">
									<div class="card-body">
										<button type="submit" id="submit" class="btn btn-success submit-btn">Submit</button>
										<span id="chk_msg2" style="display: none;"></span>
									</div>
								</div>
							</form>
						</div>



						<div class="card table-div" style="display: none;">
							<div class="card-body">
								<h4 class="card-title">Search Result:</h4>
								<div class="table-responsive">
									<span class="show-date-range" style="display: none; margin-left:5px;"></span>
									<button type="button" class="btn btn-sm btn-primary export-btn">Export <i class="icofont-file-excel"></i></button>

									<table id="zero_config" class="table table-striped table-bordered">
										<thead>
											<tr class="textcen">
												<th>#
													<input type="checkbox" class="check-all" value="">
													<button type="button" class="btn btn-sm btn-primary sms-btn" style="display: none;"><i class="icofont-ui-message"></i></button>
												</th>
												<th>Sl</th>
												<th>DateCreated</th>
												<th>FullName</th>
												<th>Mobile</th>
												<th>Email</th>
												<th>BusinessModel</th>
												<th>Owner</th>
												<th>LeadOrigin</th>
												<th>OrionLeadSource</th>
												<th>LastSent</th>
												<th>LastSentSMS</th>
											</tr>
										</thead>
										<tbody class="textcen table-data">

										</tbody>
									</table>
								</div>
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


	<!-- Modal -->
	<div class="modal fade" id="smsmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel"><?php echo comp_name; ?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form class="form-horizontal" id="sms_form">
						<div class="card-body">
							<h4 class="card-title"><?php echo $page_title; ?> [<?php echo ($sms_balance != '') ? $sms_balance : 'Api Error' ?>]</h4>

							<div class="form-group row">
								<label for="fname" class="col-sm-2 text-left control-label col-form-label"> SMS to:</label>
								<div class="col-sm-10">
									<input type="hidden" class="form-control" name="owner_get" id="owner_get" value="<?php echo $owner; ?>">
									<input type="hidden" class="form-control" name="sms_arr" id="sms_arr" value="">
									<input type="hidden" class="form-control" name="phone_nos" id="phone_nos" value="">
									<textarea class="form-control sms-to" placeholder="SMS to:" style="min-height: 130px;" readonly></textarea>
								</div>
							</div>

							<div class="form-group row">
								<label for="fname" class="col-sm-2 text-left control-label col-form-label"> Selected:</label>
								<div class="col-sm-10">
									<label class="show-selected"></label>
								</div>
							</div>

							<div class="form-group row">
								<label for="fname" class="col-sm-2 text-left control-label col-form-label"> SMS Body:</label>
								<div class="col-sm-10">
									<textarea class="form-control sms-body" name="sms_body" placeholder="Type your sms here.." style="min-height: 100px;"></textarea>
								</div>
							</div>

						</div>
						<div class="border-top">
							<div class="card-body">
								<button type="submit" class="btn btn-success submit-btn-sms">Submit</button>
								<span id="chk_msg_sms" style="display: none;"></span>
							</div>
						</div>
					</form>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="smsbodymodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">View Last Sent Message</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">

					<h5 class="form-control detailed-sms-body"></h5>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal -->

	<?php $this->load->view('bottom_js'); ?>
	<!-- this page js -->
	<script src="<?php echo base_url(); ?>common/assets/extra-libs/multicheck/datatable-checkbox-init.js"></script>
	<script src="<?php echo base_url(); ?>common/assets/extra-libs/multicheck/jquery.multicheck.js"></script>
	<script src="<?php echo base_url(); ?>common/assets/extra-libs/DataTables/datatables.min.js"></script>
	<script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
	<script src="<?php echo base_url(); ?>common/assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
	<script src="<?php echo base_url(); ?>common/assets/libs/select2/dist/js/select2.full.min.js"></script>
	<script src="<?php echo base_url(); ?>common/assets/libs/select2/dist/js/select2.min.js"></script>


	<script>
		$(".select2").select2();
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
			var oTable;
			$("form[id='filter_form']").submit(function(e) {

				var date_from = $('.date_from').val();
				var date_to = $('.date_to').val();

				if (date_from != '' && date_to == '') {
					$('#chk_msg2').show();
					$('#chk_msg2').html('<i class="icofont-close-squared-alt" style="color:red;"></i> Please select Date To field!');
					e.preventDefault();
				} else if (date_from == '' && date_to != '') {
					$('#chk_msg2').show();
					$('#chk_msg2').html('<i class="icofont-close-squared-alt" style="color:red;"></i> Please select Date From field!');
					e.preventDefault();
				} else {

					$('.submit-btn').prop('disabled', true);
					$('#chk_msg2').show();
					$('#chk_msg2').html('<i class="fa fa-spinner fa-spin" style="font-size:24px"></i>');
					$(".export-btn").hide();

					var formData = new FormData($(this)[0]);
					var htmld = '';
					var counter = 1;
					var phone = 0;
					var name = '';
					var lastsent = '';
					var lastsentwrap = '';
					var strlength = 25;

					$.ajax({
						url: "<?php echo base_url(); ?>addons/getleads",
						type: "POST",
						data: formData,
						success: function(d) {

							$('.submit-btn').prop('disabled', false);
							$('#zero_config').DataTable().destroy();
							$('#zero_config tbody').empty();

							if (d.success == '1') {

								$('#chk_msg2').hide();
								$('.table-div').show();
								$('.show-date-range').show();
								$('.show-date-range').html('<b>Filtered With:</b> ' + d.filterData);

								if (d.tableData != "") {
									
									$(".export-btn").show();
									$('.export-btn').attr('onClick', "location.href='" + d.downpath + "'");
									
									$.each(d.tableData, function(index, value) {
										//console.log(value.dateentered);
										phone = (value.phonework != '' && value.phonework != '-') ? value.phonework : value.phonemobile;
										name = value.firstname + ' ' + value.lastname;
										lastsent = value.last_sent_sms_body;

										//console.log(lastsent.length);
										if (lastsent.length > strlength) {
											lastsentwrap = lastsent.substring(0, strlength);
											lastsent = '<span class="wrap-msg-body" data-msg-body="' + lastsent + '" >' + lastsentwrap + '...</span>';
										} else {
											lastsent = '<span>' + lastsent + '</span>';
										}



										htmld += '<tr>';
										htmld += '<td><br><input type="checkbox" class="rec-ids" id="rec_ids" name="rec_ids[]" value="' + value.leadid + '" data-sname="' + name + '" data-sphone="' + phone + '" data-smail="' + value.leademail + '"></td>';
										htmld += '<td>' + counter + '</td>';
										htmld += '<td>' + value.dateentered + '</td>';
										htmld += '<td>' + name + '</td>';
										htmld += '<td>' + phone + '</td>';
										htmld += '<td>' + value.leademail + '</td>';
										htmld += '<td>' + (value.businessmodel).toUpperCase() + '</td>';
										htmld += '<td>' + value.owner + '</td>';
										htmld += '<td>' + value.leadorigin + '</td>';
										htmld += '<td>' + value.orionleadsource + '</td>';
										htmld += '<td>' + value.last_sent_sms_dt + '</td>';
										htmld += '<td>' + lastsent + '</td>';
										htmld += '</tr>';

										counter++;
									});

									$('.table-data').html(htmld);
									//$('#zero_config').DataTable();
									oTable = $('#zero_config').dataTable({
										stateSave: true
									});

								} else {
									htmld += '<tr><td colspan="8">No data found!!</td></tr>';
									$('#zero_config').DataTable();
								}
							} else {
								$('#chk_msg2').html('<i class="icofont-close-squared-alt" style="color:red;"></i> ' + d.error_msg);
							}
						},
						cache: false,
						contentType: false,
						processData: false
					});

					e.preventDefault();
				}

			});

			//sms

			//del show hide
			$(document).on('change', '.rec-ids', function() {

				var allPages = oTable.fnGetNodes();
				if ($(".rec-ids", allPages).is(':checked')) {
					$(".sms-btn").show();
				} else {
					$(".sms-btn").hide();
				}

			});

			//check all

			$(document).on('change', '.check-all', function() {
				//console.log('ff');
				//console.log(allPages);

				var allPages = oTable.fnGetNodes();

				if ($(".check-all").is(':checked')) {
					$(".sms-btn").show();
					$('.rec-ids', allPages).attr('checked', 'checked');
					//$('.rec-ids').prop('checked', 'checked');
					$(this).val('uncheck all');
				} else {
					$(".sms-btn").hide();
					$('.rec-ids', allPages).removeAttr('checked');
					$(this).val('check all');
				}
			});

			$(document).on('click', '.sms-btn', function() {

				var allPages = oTable.fnGetNodes();
				var i = 0;
				var limit = 1000; //set limit fro max selection
				//var count_i = 0;
				var arr = {};
				var sendto_details = '';
				var sendto_nos = '';
				$('.rec-ids:checked', allPages).each(function() {
					arr[i] = {};
					arr[i]['rec_id'] = $(this).val();
					arr[i]['sname'] = $(this).attr('data-sname');
					arr[i]['sphone'] = $(this).attr('data-sphone');
					arr[i]['smail'] = $(this).attr('data-smail');

					sendto_details += $(this).attr('data-sphone') + ' (' + $(this).attr('data-sname') + '), ';
					sendto_nos += $(this).attr('data-sphone') + ',';
					i++;
				});
				//console.log(arr);
				$(".sms-to").val(sendto_details);
				$("#sms_arr").val(JSON.stringify(arr));
				$("#phone_nos").val(sendto_nos);
				$(".sms-body").val('');
				$("#chk_msg_sms").hide();

				//count_i = i + 1;

				if (i > limit) {
					$('.submit-btn-sms').prop('disabled', true);
					$("#chk_msg_sms").show();
					$(".show-selected").html('<b style="color:red;">' + i + '</b>/' + limit);
					$('#chk_msg_sms').html('<i class="icofont-close-squared-alt" style="color:red;"></i> <b>Error:</b> You have exceeded the selection limit: <b>' + limit + '</b>!!');
				} else {
					$(".show-selected").html(i + '/' + limit);
					$('.submit-btn-sms').prop('disabled', false);
					$("#chk_msg_sms").hide();
				}

				$('#smsmodal').modal('toggle');
				$('#smsmodal').modal('show');
				//$('#smsmodal').modal('hide');

			});

			$("form[id='sms_form']").submit(function(e) {

				$('.submit-btn-sms').prop('disabled', true);
				$('#chk_msg_sms').show();
				$('#chk_msg_sms').html('<i class="fa fa-spinner fa-spin" style="font-size:24px"></i> Sending..');

				var formData = new FormData($(this)[0]);
				var resp = '';

				$.ajax({
					url: "<?php echo base_url(); ?>addons/sendbulksms",
					type: "POST",
					data: formData,
					success: function(d) {
						$('.submit-btn-sms').prop('disabled', false);

						//console.log(d.response);
						if (d.success == '1') {
							$.each(d.response, function(index, value) {
								//console.log(value.name);
								if (value.api_response != 'No Numbers Found') {
									resp += '<i class="icofont-tick-mark" style="color:green;"></i> <b>Successfully Sent to:</b> ' + value.sent_phone + ' [' + value.name + '], ';
								} else {
									resp += '<i class="icofont-close-squared-alt" style="color:red;"></i> <b> Error: ' + value.api_response + ' :</b> ' + value.sent_phone + ' [' + value.name + '], ';
								}


							});
							$('#chk_msg_sms').html(resp);
						} else {
							$('#chk_msg_sms').html('<i class="icofont-close-squared-alt" style="color:red;"></i> <b>Error:</b> ' + d.msg);
						}
					},
					cache: false,
					contentType: false,
					processData: false
				});

				e.preventDefault();

			});

			$(document).on('click', '.wrap-msg-body', function() {

				var msgbody = $(this).attr('data-msg-body');

				$(".detailed-sms-body").html(msgbody);
				$('#smsbodymodal').modal('toggle');
				$('#smsbodymodal').modal('show');
				//$('#smsbodymodal').modal('hide');

			});

		});
	</script>

</body>

</html>