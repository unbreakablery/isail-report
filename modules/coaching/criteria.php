<?php 
    require '../../inc/config.php';

    $common->authorizePage("coaching_report");

    $iSAIL_UI->title = $common->getPrefixProjectName() . ' ' . 'Coaching Report - Criteria';

    //Get users
    $query = $common->generateQueryForGetUserFromConv();

    $result = mysqli_query($common->db_connect, $query);

    $users = array();
    while ($row = mysqli_fetch_array($result)) {
        $users[] = $row;
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
                    <i class="fa fa-sitemap text-primary"></i>
                </span>
                Coaching Report
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
                    <form class="form-horizontal push-10-t" method="post" id="reportForm">
                        <input type="hidden" name="player_name" value="" />
                        <input type="hidden" name="action_name" value="levels_for_coaching" />

                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-material floating">
                                    <select class="form-control" id="time_period" name="time_period" size="1">
                                        <option></option>
                                        <option value="first-time">First Time Playing the Level</option>
                                        <option value="last-time">Most Recent Time Playing the Level</option>
                                        <option value="game-key-instance">Game Key Instance</option>
                                        <option value="specific-date">Specific Date</option>
                                    </select>
                                    <label for="time_period">What period would you like to select for the report?</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6 d-none specific-date-row">
                                <div class="form-material">
                                    <input class="form-control js-datepicker" type="text" id="specific_date" name="specific_date" data-date-format="yyyy-mm-dd" placeholder="Specific Date (yyyy-mm-dd)">
                                    <label for="specific_date">Please Select specific date</label>
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

                            <div class="col-sm-6 d-none gkn-row">
                                <div class="form-material">
                                    <input type="text" id="game_key_number" name="game_key_number" class="form-control" placeholder="Game Key Number" maxlength="24"/>                                    
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="form-material floating">
                                    <select class="form-control" id="player" name="player" size="1">
                                        <option value=""></option>
                                        <?php
                                            foreach ($users as $user) { 
                                        ?>
                                            <option value="<?php echo $user['userid']; ?>"><?php echo $user['username']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <label for="player">Select Player for Coaching: </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-material floating">
                                    <select class="form-control" name="level" id="level" size="1">
                                        <option value="1">Level 1</option>
                                        <option value="2">Level 2</option>
                                        <option value="3">Level 3</option>
                                    </select>
                                    <label for="level">Select Level for Coaching: </label>
                                </div>
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
                                <!-- <button class="btn btn-sm btn-primary" type="submit">View Report</button> -->
                                <button class="btn btn-sm btn-primary" type="button" id="view-instances">View Instances</button>
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

<!-- Level Instances Modal -->
<div class="modal fade" id="level-instances-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout">
        <div class="modal-content">
            <div class="block block-themed block-transparent remove-margin-b">
                <div class="block-header bg-primary">
                    <ul class="block-options">
                        <li>
                            <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Level Instances For Coaching</h3>
                </div>
                <div class="block-content">
                    <form class="form-horizontal push-10-t" id="coachingForm" method="post" action="report.php">
                        <input type="hidden" name="instance-username" value="" />
                        <input type="hidden" name="instance-level" value="" />

                        <!-- Info Alert -->
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <div class="row remove-margin-l remove-margin-r">
                                    <div class="alert alert-info alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <p>
                                            <a class="alert-link" href="javascript:void(0)">
                                                <i class="fa fa-info-circle"></i> You must choose a level instance for coaching.
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-1"></div>
                        </div>
                        <!-- END Info Alert -->

                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                <div class="form-material">
                                    <select class="form-control" name="level-instance" id="level-instance" size="1">
                                    </select>
                                    <label for="level-instance">Level Instances for Coaching: </label>
                                </div>
                            </div>
                            <div class="col-sm-3"></div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-default" type="button" id="cancel-coach">Cancel</button>
                <button class="btn btn-sm btn-primary" type="button" id="coach-report"><i class="fa fa-check"></i> Coach Report</button>
            </div>
        </div>
    </div>
</div>
<!-- END Level Instances Modal -->

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

        $('#time_period').change(function (e) {
            $('#specific_date').val("");
            $('#specific_time').html("");
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

                                        setSpecificTimeFromLocalStorage('coaching_report_form');
                                    }
                                }
                });
            } else {
                $('#specific_time').html("");
                $('.specific-time-row label').html("");
                $('.specific-time-row').addClass('d-none');
            }
        });

        $('button#view-instances').click(function() {
            if ($("#player").val() == null || $("#player").val() == "") {
                $("#note-modal-content").text("You must select a player to coach.");
                $("#note-modal").modal({backdrop: 'static'});
                $("#player").focus();
                return false;
            }
            if ($("#level").val() == null || $("#level").val() == "") {
                $("#note-modal-content").text("You must select a level to coach.");
                $("#note-modal").modal({backdrop: 'static'});
                $("#level").focus();
                return false;
            }

            $.ajax({
                    url:            "/apis/reports.php",
                    dataType:       "json",
                    type:           "post",
                    data:           new FormData(document.getElementById('reportForm')),
                    contentType:    false,
                    cache:          false,
                    processData:    false,
                    success:        function( data ) {
                                        if (!data.status) {

                                        } else {
                                            $("#level-instance").html('');
                                            level_instance_content = '';

                                            for (i = 0; i < data.instances.length; i++) {
                                                level_instance_content += '<option value="' + data.instances[i].level_ID + '">';
                                                level_instance_content += 'Level ID: ' + data.instances[i].level_ID + ', ';
                                                level_instance_content += data.instances[i].date + ' ' + data.instances[i].time;
                                                level_instance_content += '</option>';
                                            }

                                            $("#level-instance").html(level_instance_content);

                                            $("#level-instances-modal").modal({backdrop: 'static'});
                                        }
                                    }
            });
        });

        $("#cancel-coach").click(function() {
            $("#level-instances-modal").modal('hide');
        });

        $("#coach-report").click(function() {
            level_ID = $("#level-instance").val();

            if (level_ID != undefined && level_ID != null && level_ID != '') {
                $("input[name='instance-username']").val($("input[name='player_name']").val());
                $("input[name='instance-level']").val($("#level").val());

                $("#coachingForm").append("<input type='hidden' name='instance-label' value='" + $("#level-instance option:selected").text() + "' />");

                if ($('#criteria-remember').is(':checked')) {
                    window.localStorage.setItem('coaching_report_form', JSON.stringify($("#reportForm").serializeArray()));
                } else {
                    window.localStorage.removeItem('coaching_report_form');
                }

                $("#coachingForm").submit();
            }
        });

        $("#player").change(function() {
            $("input[name='player_name']").val($(this).find('option:selected').text());
        });

        $("#clear-criteria-button").click(function() {
            $('#time_period').val('');
            $('#time_period').trigger('change');

            $('#game_key_number').val('');

            $("#player").val("");
            $("#level").val("1");
            $("#criteria-remember").prop('checked', false);
        });

        // save form
        // try to load values
        var session = window.localStorage.getItem('coaching_report_form');
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
    });
</script>
<?php require '../../inc/views/template_footer_end.php'; ?>