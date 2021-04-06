<?php 
    require '../../inc/config.php';
    
    $common->authorizePage("comparison_model_report");

    $iSAIL_UI->title = $common->getPrefixProjectName() . ' ' . $common->getModelsShortLabel() . ' ' . 'Comparison Report - Criteria';
?>

<?php require '../../inc/views/template_head_start.php'; ?>

<!-- Page JS Plugins CSS -->
<link rel="stylesheet" href="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css">

<?php require '../../inc/views/template_head_end.php'; ?>
<?php require '../../inc/views/base_head.php'; ?>

<!-- Page Header -->
<div class="content bg-gray-lighter">
    <div class="row items-push">
        <div class="col-sm-12">
            <h1 class="page-heading font-w700 text-primary">
                <span class="item item-circle bg-primary-lighter">
                    <i class="si si-bar-chart text-primary"></i>
                </span>
                <?php echo $common->getModelsShortLabel(); ?> Comparison Report
            </h1>
        </div>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content content-narrow">
    <!-- Material Design -->
    <div class="row">
        <div class="col-sm-12">
            <div class="block">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Criteria</h3>
                </div>

                <div class="block-content block-content-narrow">

                    <form class="form-horizontal push-10-t" action="report.php" method="post" id="reportForm">
                        
                        <div class="form-group">
                            <div class="col-sm-6 specific-date-row">
                                <div class="form-material">
                                    <label for="specific_date">Time Period:</label>
                                    <input class="form-control js-datepicker" type="text" id="specific_date" name="specific_date" data-date-format="yyyy-mm-dd" placeholder="Specific Date (yyyy-mm-dd)">
                                </div>
                            </div>

                            <div class="col-sm-6 d-none specific-time-row">
                                <div class="form-material floating">
                                    <select class="form-control" id="specific_time" name="specific_time" size="1">
                                        <option></option>
                                    </select>
                                    <label for="specific_time">Which game instance on </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6 remove-padding-l">
                                <label class="col-sm-12">Select BUs for Comparison: </label>
                                <?php
                                    if ($common->role == 4) {
                                        $condition = array(
                                                        'key'       => 'who_dm', 
                                                        'value'     => $common->username, 
                                                        'operator'  => '='
                                                    );
                                    }
                                    
                                    $fields = array();
                                    $fields[] = array('key' => 'org_ID', 'value' => NULL,   'operator' => 'IS NOT');
                                    $fields[] = array('key' => 'org_ID', 'value' => '',     'operator' => '!=');
                                    if (!empty($condition)) {
                                        $fields[] = $condition;
                                    }

                                    $query = $common->generateQueryForGetItemFromUsers('org_ID', 'organization', $fields);
                                    
                                    $result = mysqli_query($common->db_connect, $query);
                                    foreach ($result as $row) { 
                                        if ($row['organization'] == '') continue;
                                ?>
                                <div class="col-sm-12">
                                    <label class="css-input css-checkbox css-checkbox-primary">
                                        <input type="checkbox" name="bu_ids[]" value="<?php echo $row['organization']; ?>"><span></span> <?php echo $row['organization']; ?>
                                    </label>
                                </div>
                                <?php } ?>
                            </div>
                        
                            <div class="col-sm-6 push-8-t">
                                <div class="form-material floating">
                                    <select class="form-control" name="level" id="level" size="1">
                                        <option value="1">Level 1</option>
                                        <option value="2">Level 2</option>
                                        <option value="3">Level 3</option>
                                    </select>
                                    <label for="level">Select Level for Comparison: </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">   
                            <label class="col-sm-12">Select <?php echo $common->getModelsShortLabel(); ?> Item(s) for Comparison: </label>
                            <div class="col-sm-12">
                                <label class="css-input css-checkbox css-checkbox-primary">
                                    <input type="checkbox" name="model_parent_items[]" value="sales_group_1"><span></span> <?php echo strtoupper($common->getSalesLabels('sales_group_1', 'label')); ?> (Average of three scores below)
                                </label>
                            </div>
                            <div class="col-sm-12 push-25-l">
                                <label class="css-input css-checkbox css-checkbox-primary">
                                    <input type="checkbox" name="model_sub_items[]" value="sales1"><span></span> <?php echo $common->getSalesLabels('sales1', 'short_label'); ?>: <?php echo $common->getSalesLabels('sales1', 'label'); ?>
                                </label>
                            </div>
                            <div class="col-sm-12 push-25-l">
                                <label class="css-input css-checkbox css-checkbox-primary">
                                    <input type="checkbox" name="model_sub_items[]" value="sales2"><span></span> <?php echo $common->getSalesLabels('sales2', 'short_label'); ?>: <?php echo $common->getSalesLabels('sales2', 'label'); ?>
                                </label>
                            </div>
                            <div class="col-sm-12 push-25-l">
                                <label class="css-input css-checkbox css-checkbox-primary">
                                    <input type="checkbox" name="model_sub_items[]" value="sales3"><span></span> <?php echo $common->getSalesLabels('sales3', 'short_label'); ?>: <?php echo $common->getSalesLabels('sales3', 'label'); ?>
                                </label>
                            </div>
                            <div class="col-sm-12">
                                <label class="css-input css-checkbox css-checkbox-primary">
                                    <input type="checkbox" name="model_parent_items[]" value="sales_group_2"><span></span> <?php echo strtoupper($common->getSalesLabels('sales_group_2', 'label')); ?> (Average of three scores below)
                                </label>
                            </div>
                            <div class="col-sm-12 push-25-l">
                                <label class="css-input css-checkbox css-checkbox-primary">
                                    <input type="checkbox" name="model_sub_items[]" value="sales4"><span></span> <?php echo $common->getSalesLabels('sales4', 'short_label'); ?>: <?php echo $common->getSalesLabels('sales4', 'label'); ?>
                                </label>
                            </div>
                            <div class="col-sm-12 push-25-l">
                                <label class="css-input css-checkbox css-checkbox-primary">
                                    <input type="checkbox" name="model_sub_items[]" value="sales5"><span></span> <?php echo $common->getSalesLabels('sales5', 'short_label'); ?>: <?php echo $common->getSalesLabels('sales5', 'label'); ?>
                                </label>
                            </div>
                            <div class="col-sm-12 push-25-l">
                                <label class="css-input css-checkbox css-checkbox-primary">
                                    <input type="checkbox" name="model_sub_items[]" value="sales6"><span></span> <?php echo $common->getSalesLabels('sales6', 'short_label'); ?>: <?php echo $common->getSalesLabels('sales6', 'label'); ?>
                                </label>
                            </div>
                            <div class="col-sm-12">
                                <label class="css-input css-checkbox css-checkbox-primary">
                                    <input type="checkbox" name="model_parent_items[]" value="sales_group_3"><span></span> <?php echo strtoupper($common->getSalesLabels('sales_group_3', 'label')); ?> (Average of three scores below)
                                </label>
                            </div>
                            <div class="col-sm-12 push-25-l">
                                <label class="css-input css-checkbox css-checkbox-primary">
                                    <input type="checkbox" name="model_sub_items[]" value="sales7"><span></span> <?php echo $common->getSalesLabels('sales7', 'short_label'); ?>: <?php echo $common->getSalesLabels('sales7', 'label'); ?>
                                </label>
                            </div>
                            <div class="col-sm-12 push-25-l">
                                <label class="css-input css-checkbox css-checkbox-primary">
                                    <input type="checkbox" name="model_sub_items[]" value="sales8"><span></span> <?php echo $common->getSalesLabels('sales8', 'short_label'); ?>: <?php echo $common->getSalesLabels('sales8', 'label'); ?>
                                </label>
                            </div>
                            <div class="col-sm-12 push-25-l">
                                <label class="css-input css-checkbox css-checkbox-primary">
                                    <input type="checkbox" name="model_sub_items[]" value="sales9"><span></span> <?php echo $common->getSalesLabels('sales9', 'short_label'); ?>: <?php echo $common->getSalesLabels('sales9', 'label'); ?>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="css-input switch switch-sm switch-primary">
                                    <input type="checkbox" id="criteria-remember" name="criteria-remember" value="1"><span></span> Remember Criteria?
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button class="btn btn-sm btn-primary" type="submit">View Report</button>
                                <button class="btn btn-sm btn-default" type="button" id="clear-criteria-button">Clear Criteria</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END Material Design -->
</div>
<!-- END Page Content -->

<?php require '../../inc/views/modal_note.php'; ?>

<?php require '../../inc/views/base_footer.php'; ?>
<?php require '../../inc/views/template_footer_start.php'; ?>

<!-- Global JS -->
<?php require '../../inc/views/global.js.php'; ?>

<!-- Page Plugin JS -->
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
<script>
    $(document).ready(function() {

        iSAIL_UI.initHelper('datepicker');
        
        $("[name='model_parent_items[]']").click(function() {
            $("[name='model_sub_items[]']").prop("checked", false);
            if ($("[name='model_parent_items[]']").is(":checked")) {
                $("[name='model_sub_items[]']").prop("disabled", true);
                $("[name='model_sub_items[]']").parent().addClass("css-input-disabled");
            } else {
                $("[name='model_sub_items[]']").prop("disabled", false);
                $("[name='model_sub_items[]']").parent().removeClass("css-input-disabled");
            }
        });

        $('#reportForm').submit(function () {
            if ($("[name='bu_ids[]']:checked").length < 2) {
                $("#note-modal-content").text("You must select 2 BUs at least to compare.");
                $("#note-modal").modal({backdrop: 'static'});
                return false;
            }
            if ($("[name='model_parent_items[]']:checked").length == 0 && 
                $("[name='model_sub_items[]']:checked").length == 0) {
                $("#note-modal-content").text("You must select 1 <?php echo $common->getModelsShortLabel(); ?> item at least to compare.");
                $("#note-modal").modal({backdrop: 'static'});
                return false;
            }

            if ($('#criteria-remember').is(':checked')) {
                window.localStorage.setItem('comparison_model_report_form', JSON.stringify($(this).serializeArray()));
            } else {
                window.localStorage.removeItem('comparison_model_report_form');
            }
        });

        $("#clear-criteria-button").click(function() {
            $("#level").val("1");
            $("[name='bu_ids[]']").prop("checked", false);
            $("[name='model_parent_items[]']").prop("checked", false);
            $("[name='model_sub_items[]']").prop("checked", false);
            $("[name='model_sub_items[]']").prop("disabled", false);
            $("[name='model_sub_items[]']").parent().removeClass("css-input-disabled");

            $('#specific_date').val('');
            $('#specific_date').trigger('change');

            $("#criteria-remember").prop('checked', false);
        });

        $("#specific_date").change(function(e) {
            if (e.target.value != '') {
                $.ajax({
                    url:        "/apis/reports.php",
                    dataType:   "json",
                    type:       "post",
                    data:       {
                                    action_name:    "get_time_period",
                                    date:           e.target.value
                                },
                    success:    function( data ) {
                                    if (!data.status) {
                                        $.notify({
                                            icon:               'fa fa-times',
                                            message:            data.msg,
                                            url:                ''
                                        },
                                        {
                                            element:            'body',
                                            type:               'danger',
                                            allow_dismiss:      true,
                                            newest_on_top:      true,
                                            showProgressbar:    false,
                                            placement:          {
                                                                    from:   'top',
                                                                    align:  'center'
                                                                },
                                            offset:             20,
                                            spacing:            10,
                                            z_index:            1033,
                                            delay:              5000,
                                            timer:              1000,
                                            animate:            {
                                                                    enter:  'animated fadeIn',
                                                                    exit:   'animated fadeOutDown'
                                                                }
                                        });

                                        $('#specific_time').html("");
                                        $('.specific-time-row').addClass('d-none');
                                    } else {
                                        var timeElements = "<option></option>";
                                        if (data.times.length > 0) {
                                            for (i = 0; i < data.times.length; i++) {
                                                timeElements += "<option value='" + data.times[i]['time'] + "'>" + data.times[i]['time'] + "</option>";
                                            }
                                        }
                                        $('#specific_time').html(timeElements);
                                        $('.specific-time-row label').html("Which game instance on " + e.target.value);
                                        $('.specific-time-row').removeClass('d-none');

                                        setSpecificTimeFromLocalStorage('comparison_model_report_form');
                                    }
                                }
                });
            } else {
                $('#specific_time').html("");
                $('.specific-time-row label').html("");
                $('.specific-time-row').addClass('d-none');
            }
        });

        // save form
        // try to load values
        var session = window.localStorage.getItem('comparison_model_report_form');
        session = session ? JSON.parse(session) : [];
        for (var i = 0; i < session.length; i += 1) {
            var input   = $('[name="' + session[i].name + '"]');
            var name    = session[i].name;
            var value   = session[i].value;
            if (input.attr('type') == 'checkbox') {
                if (name == 'bu_ids[]' || name == 'model_parent_items[]' || name == 'model_sub_items[]') {
                    $.each(input, function(index, node) {
                        if ($(node).val() == value) {
                            if (name == 'model_parent_items[]') {
                                $(node).prop('checked', true);
                                $("[name='model_sub_items[]']").prop("checked", false);
                                if ($("[name='model_parent_items[]']").is(":checked")) {
                                    $("[name='model_sub_items[]']").prop("disabled", true);
                                    $("[name='model_sub_items[]']").parent().addClass("css-input-disabled");
                                } else {
                                    $("[name='model_sub_items[]']").prop("disabled", false);
                                    $("[name='model_sub_items[]']").parent().removeClass("css-input-disabled");
                                }
                            } else {
                                $(node).prop('checked', true);
                            }
                        }
                    });
                } else {
                    input.prop('checked', true);
                }
            } else {
                input.val(session[i].value);
                input.trigger('change');
            }
        }
    });
</script>
<?php require '../../inc/views/template_footer_end.php'; ?>