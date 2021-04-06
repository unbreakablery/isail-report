<?php 
    require '../../inc/config.php';
    
    $common->authorizePage("player_report");

    $iSAIL_UI->title = $common->getPrefixProjectName() . ' ' . 'Player Report - Criteria';

    $userBU = '';
    
    if ($common->role == 3) {
        $userBU = $common->getBuFromUnixID();
        if ($userBU === false) {
            header('Location: /errors/500.php');
        }
    }
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
                    <i class="fa fa-table text-primary"></i>
                </span>
                Player In-Game Performance Report
            </h1>
        </div>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content content-narrow">
    <div class="row">
        <div class="col-sm-12">
            <div class="block">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Criteria</h3>
                </div>
                <div class="block-content block-content-narrow">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert alert-info alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <p><a class="alert-link" href="javascript:void(0)"><i class="fa fa-info-circle"></i> For each search criteria, please leave Blank to select <em>'ALL'</em>.</a></p>
                            </div>
                        </div>
                    </div>

                    <form class="form-horizontal push-10-t" action="report.php" method="post" id="reportForm">
                        
                        <?php include '../../inc/views/report_criteria.php'; ?>
                        
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="form-material floating">
                                    <select class="form-control" name="level" size="1">
                                        <option value="1">Level 1</option>
                                        <option value="2">Level 2</option>
                                        <option value="3">Level 3</option>
                                    </select>
                                    <label for="level">Please Select Game Level</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
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
</div>
<!-- END Page Content -->

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

        $("#clear-criteria-button").click(function() {
            $('#time_period').val('');
            $('#time_period').trigger('change');

            $('#game_key_number').val('');

            $("#organization").val("");
            $("#organization").trigger('change');

            $("#franchise").val("");
            $("#franchise").trigger('change');

            $('[name="manager_first_name"]').val('');
            $('[name="manager_last_name"]').val('');

            $('[name="customer_first_name"]').val('');
            $('[name="customer_last_name"]').val('');

            $('[name="class_id"]').val('');
            $('[name="class_date"]').val('');

            $('[name="product"]').val('');
            $('[name="indication"]').val('');

            $('[name="unix_id"]').val('');
            $('[name="level"]').val('1');

            $("#criteria-remember").prop('checked', false);
        });

        $('#organization').change(function (e) {
            if (e.target.value === 'BioOnc') {
                $('.bu-row').removeClass('d-none');
            } else {
                $('.bu-row').addClass('d-none');
            }
        });

        $('#time_period').change(function (e) {
            $('#specific_date').val('');
            $('#specific_time').html('');

            $('#game_key_number').val('');

            if (e.target.value === 'specific-date') {
                $('.specific-date-row').removeClass('d-none');
            } else {
                $('.specific-date-row').addClass('d-none');
                $('.specific-time-row').addClass('d-none');
            }

            if (e.target.value === 'game-key-instance') {
                $('#game_key_number').val("");
                $('.gkn-row').removeClass('d-none');
            } else {
                $('.gkn-row').addClass('d-none');
            }
        });

        $('#specific_date').change(function (e) {
            if (e.target.value !== '') {
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

                                        setSpecificTimeFromLocalStorage('player_report_form');
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
        var session = window.localStorage.getItem('player_report_form');
        session = session ? JSON.parse(session) : [];
        for (let i = 0; i < session.length; i += 1) {
            var input = $('[name="' + session[i].name + '"]');
            if (input.attr('type') == 'checkbox') {
                input.prop('checked', true);
            } else {
                input.val(session[i].value);
                input.trigger('change');
            }
        }

        //check if role = 3
        var unixId  = "<?php echo $common->username; ?>";
        var userBu  = "<?php echo $userBU; ?>";
        var role    = <?php echo $common->role; ?>;
        if (role == 3) {
            $('[name="unix_id"]').val(unixId);
            $('[name="unix_id"]').prop("readonly", true);

            $('[name="organization"]').val(userBu);
            $('[name="organization"]').prop("disabled", true);
        }

        $('#reportForm').submit(function () {
            $('[name="organization"]').prop("disabled", false);
            
            if ($('#criteria-remember').is(':checked')) {
                window.localStorage.setItem('player_report_form', JSON.stringify($(this).serializeArray()));
            } else {
                window.localStorage.removeItem('player_report_form');
            }
        });
    });
</script>

<?php require '../../inc/views/template_footer_end.php'; ?>