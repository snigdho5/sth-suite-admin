<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <?php $this->load->view('top_css'); ?>
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>common/assets/extra-libs/multicheck/multicheck.css">
    <link href="<?php echo base_url(); ?>common/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>common/assets/libs/select2/dist/css/select2.min.css">
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

                            <form class="form-horizontal" id="create_lead_form">
                                <?php //print_obj($leadS_data);die; 
                                ?>
                                <div class="card-body">
                                    <h4 class="card-title"><?php echo $page_title; ?> <button type="button" class="btn badge badge-pill badge-success" onclick="location.href='<?php echo base_url() . 'leadsources'; ?>'">Lead Source List</button></h4>
                                    <p id="chk_msg2" style="display: none;"></p>

                                    <div class="form-group row">
                                        <label for="fname" class="col-sm-3 text-right control-label col-form-label">Orion Lead Source:</label>
                                        <div class="col-sm-9">
                                            <input type="hidden" id="lead_source_id" name="lead_source_id" value="<?php echo (!empty($leadS_data) && $leadS_data['olsm_id'] != '') ? $leadS_data['olsm_id'] : '0'; ?>">

                                            <input type="text" class="form-control" id="orion_lead_source" name="orion_lead_source" placeholder="Orion Lead Source Value.." value="<?php echo (!empty($leadS_data) && $leadS_data['orion_lead_source_c'] != '') ? $leadS_data['orion_lead_source_c'] : ''; ?>" required="">
                                            <label id="chk_msg" style="display: none;"></label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="fname" class="col-sm-3 text-right control-label col-form-label">Business Model:</label>
                                        <div class="col-sm-9">

                                            <select class="select2 form-control m-t-15" style="width: 100%; height:36px;" id="business_model" name="business_model" required="">
                                                <option value="" <?php echo (!empty($leadS_data) && $leadS_data['business_model_c'] == '') ? 'selected' : ''; ?>>Select</option>
                                                <?php
                                                if (!empty($bm_data)) {
                                                    foreach ($bm_data as $key => $value) {
                                                ?>
                                                        <option value="<?php echo $value['business_model']; ?>" <?php echo (!empty($leadS_data) && $leadS_data['business_model_c'] == $value['business_model']) ? 'selected' : ''; ?>><?php echo $value['business_model']; ?></option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </select>

                                        </div>
                                    </div>

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
    <script src="<?php echo base_url(); ?>common/assets/extra-libs/multicheck/datatable-checkbox-init.js"></script>
    <script src="<?php echo base_url(); ?>common/assets/extra-libs/multicheck/jquery.multicheck.js"></script>
    <script src="<?php echo base_url(); ?>common/assets/extra-libs/DataTables/datatables.min.js"></script>

    <script src="<?php echo base_url(); ?>common/assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="<?php echo base_url(); ?>common/assets/libs/select2/dist/js/select2.min.js"></script>

    <script>
        $(".select2").select2();

        $("#orion_lead_source").keyup(function() {

            var orion_lead_source = $('#orion_lead_source').val();

            if (orion_lead_source != '') {
                $('#submit').attr("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url() . 'duplicate_check_leadsource'; ?>",
                    data: {
                        orion_lead_source: orion_lead_source
                    },

                    success: function(d) {
                        if (d.is_exists == 1) {
                            $('#chk_msg').show();
                            $('#chk_msg').html('<i class="icofont-close-squared-alt"></i> Lead source already exists..!!');
                            $("#chk_msg").css("color", "red");
                            $('#submit').attr("disabled", true);
                            return false;
                        } else {
                            $('#chk_msg').show();
                            $('#chk_msg').html('<i class="icofont-tick-boxed"></i> Lead source available.');
                            $("#chk_msg").css("color", "green");
                            $('#submit').attr("disabled", false);
                        }
                    }
                });
            } else {
                $('#chk_msg').hide();
            }

        });

        $("form[id='create_lead_form']").submit(function(e) {

            var formData = new FormData($(this)[0]);

            $.ajax({
                url: "<?php echo base_url(); ?>createleadsource",
                type: "POST",
                data: formData,
                success: function(d) {

                    if (d.is_blank == 1) {

                        if (d.bm_val == 0) {
                            $('#chk_msg2').show();
                            $('#chk_msg2').html('<i class="icofont-close-squared-alt"></i> Business model is required..!!');
                            $("#chk_msg2").css("color", "red");
                        } else {
                            $('#chk_msg2').show();
                            $('#chk_msg2').html('<i class="icofont-close-squared-alt"></i> Lead source is required..!!');
                            $("#chk_msg2").css("color", "red");
                            $('#submit').attr("disabled", true);
                        }

                    } else if (d.added == 'success') {

                        alert('Lead source Tagged!');
                        window.location.reload();

                    } else if (d.added == 'failure') {

                        alert('Something went wrong!');
                        $('#chk_msg2').show();
                        $('#chk_msg2').html('<i class="icofont-close-squared-alt"></i> Something went wrong!!');
                        $("#chk_msg2").css("color", "red");
                        $('#submit').attr("disabled", true);

                    } else if (d.updated == 'success') {

                        alert('Lead source tag updated!');
                        window.location.reload();

                    } else {
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