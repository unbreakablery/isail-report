<?php 
    require '../../inc/config.php'; 

    $common->authorizePage("coaching_report");

    $iSAIL_UI->title = $common->getPrefixProjectName() . ' ' . 'Coaching Report Management';
?>

<?php require '../../inc/views/template_head_start.php'; ?>
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
                Coaching Report Management
            </h1>
        </div>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content">
    
    <div class="block block-themed">
        <div class="block-header bg-primary">
            <ul class="block-options">
                <li>
                    <button type="button" data-toggle="block-option" data-action="content_toggle"></button>
                </li>
            </ul>
            <h3 class="block-title">Import dialogue data from CSV file</h3>
        </div>
        <div class="block-content block-content-narrow">
            <div class="row">
                <!-- Warning Alert -->
                <div class="alert alert-warning alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <p><i class='fa fa-warning'></i> When you import csv file, the same data will be removed. <em class="text-danger">Click <a class="alert-link" id="remove-dialogue-data" href="javascript:void(0)">here</a> to remove all data from database.</em></p>
                </div>
                <!-- END Warning Alert -->
            </div>
            <div class="row">
                <!-- Info Alert -->
                <div class="alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <p><i class='fa fa-info-circle'></i> CSV Fields : Level, ID, Title, Description, Actor, Conversant, Dialogue, Feedback, Rubric, Param</p>
                </div>
                <!-- END Info Alert -->
            </div>

            <!-- Upload Dialogue Data Form -->
            <form class="form-horizontal" method="post" id="import_dialogue_form" name="import_dialogue_form" enctype="multipart/form-data">
                <input type="hidden" name="action_name" value="import_coaching_dialogue" />
                <div class="form-group">
                    <label class="col-md-5 control-label" for="">File For Importing: </label>
                    <div class="col-md-5">
                        <input type="file" id="dialogue_source_file" name="dialogue_source_file" class="push-5 push-5-t" accept=".csv">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-5 col-md-offset-5">
                        <button class="btn btn-sm btn-primary" type="submit">Import</button>
                        <button class="btn btn-sm btn-default btn-back" type="button">Go to Coaching Report</button>
                    </div>
                </div>
            </form>
            <!-- END Upload Dialogue Data Form -->
        </div>
    </div>

    <div class="block block-themed">
        <div class="block-header bg-primary">
            <ul class="block-options">
                <li>
                    <button type="button" data-toggle="block-option" data-action="content_toggle"></button>
                </li>
            </ul>
            <h3 class="block-title">Import dialogue NPC character data from CSV file</h3>
        </div>
        <div class="block-content block-content-narrow">
            <div class="row">
                <!-- Warning Alert -->
                <div class="alert alert-warning alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <p><i class='fa fa-warning'></i> When you import csv file, old data will be removed. <em class="text-danger">Click <a class="alert-link" id="remove-dialogue-npc-data" href="javascript:void(0)">here</a> to remove all data from database.</em></p>
                </div>
                <!-- END Warning Alert -->
            </div>
            <div class="row">
                <!-- Info Alert -->
                <div class="alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <p><i class='fa fa-info-circle'></i> CSV Fields : Level, NPC#, Character</p>
                </div>
                <!-- END Info Alert -->
            </div>

            <!-- Upload Dialogue NPC Data Form -->
            <form class="form-horizontal" method="post" id="import_dialogue_npc_form" name="import_dialogue_npc_form" enctype="multipart/form-data">
                <input type="hidden" name="action_name" value="import_coaching_dialogue_npc" />
                <div class="form-group">
                    <label class="col-md-5 control-label" for="">File For Importing: </label>
                    <div class="col-md-5">
                        <input type="file" id="dialogue_npc_source_file" name="dialogue_npc_source_file" class="push-5 push-5-t" accept=".csv">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-5 col-md-offset-5">
                        <button class="btn btn-sm btn-primary" type="submit">Import</button>
                        <button class="btn btn-sm btn-default btn-back" type="button">Go to Coaching Report</button>
                    </div>
                </div>
            </form>
            <!-- END Upload Dialogue NPC Data Form -->
        </div>
    </div>

</div>
<!-- END Page Content -->

<!-- Require File Modal -->
<div class="modal fade" id="require-file-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout">
        <div class="modal-content">
            <div class="block block-themed block-transparent remove-margin-b">
                <div class="block-header bg-primary">
                    <ul class="block-options">
                        <li>
                            <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Note</h3>
                </div>
                <div class="block-content">
                    <div class="text-center text-danger">
                        <p>
                            <i class="fa fa-warning"></i> 
                            You must select a csv source file to import.
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary" type="button" data-dismiss="modal"><i class="fa fa-check"></i> Okay</button>
            </div>
        </div>
    </div>
</div>
<!-- END Require File Modal -->

<!-- Confirm Import Modal -->
<div class="modal fade" id="confirm-import-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout">
        <div class="modal-content">
            <div class="block block-themed block-transparent remove-margin-b">
                <div class="block-header bg-primary">
                    <ul class="block-options">
                        <li>
                            <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Confirm</h3>
                </div>
                <div class="block-content">
                    <div class="text-center text-default">
                        <p>
                            <i class="fa fa-question-circle"></i> 
                            Would you import data from this csv file really?
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-sm btn-primary" type="button" data-dismiss="modal" id="import-yes-button"><i class="fa fa-check"></i> Yes</button>
            </div>
        </div>
    </div>
</div>
<!-- END Confirm Import Modal -->

<!-- Confirm Remove Modal -->
<div class="modal fade" id="confirm-remove-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout">
        <div class="modal-content">
            <div class="block block-themed block-transparent remove-margin-b">
                <div class="block-header bg-primary">
                    <ul class="block-options">
                        <li>
                            <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Confirm</h3>
                </div>
                <div class="block-content">
                    <div class="text-center text-default">
                        <p>
                            <i class="fa fa-question-circle"></i> 
                            Would you remove all data from database really?
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-sm btn-primary" type="button" data-dismiss="modal" id="remove-yes-button"><i class="fa fa-check"></i> Yes</button>
            </div>
        </div>
    </div>
</div>
<!-- END Confirm Remove Modal -->

<?php require '../../inc/views/base_footer.php'; ?>
<?php require '../../inc/views/template_footer_start.php'; ?>

<!-- Page JS Code -->
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
<script>
    $(document).ready(function() {
        var form_name   = '';
        var table_name  = '';

        $("#import_dialogue_form").submit(function(e) {
            if (document.getElementById("dialogue_source_file").files.length == 0) {
                $("#require-file-modal").modal({backdrop: 'static'});
                return false;
            }
            
            $("#confirm-import-modal").modal({backdrop: 'static'});
            form_name = 'import_dialogue_form';
            e.preventDefault();

        });

        $("#import_dialogue_npc_form").submit(function(e) {
            if (document.getElementById("dialogue_npc_source_file").files.length == 0) {
                $("#require-file-modal").modal({backdrop: 'static'});
                return false;
            }
            
            $("#confirm-import-modal").modal({backdrop: 'static'});
            form_name = 'import_dialogue_npc_form';
            e.preventDefault();

        });

        $('#import-yes-button').click(function() {
            $.ajax({
				url:            "/apis/reports.php",
				dataType:       "json",
				type:           "post",
                data:           new FormData(document.getElementById(form_name)),
                contentType:    false,
                cache:          false,
                processData:    false,
				success:        function( data ) {
                                    if (!data.status) {
                                        $.notify({
                                            icon:               'fa fa-times' || '',
                                            message:            data.msg + "<br/> SQL: " + data.error_query,
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
                                    } else {
                                        $.notify({
                                            icon:               'fa fa-check',
                                            message:            data.msg,
                                            url:                ''
                                        },
                                        {
                                            element:            'body',
                                            type:               'success',
                                            allow_dismiss:      true,
                                            newest_on_top:      true,
                                            showProgressbar:    false,
                                            placement:          {
                                                                    from:   'top',
                                                                    align:  'center' || 'right'
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

                                    }
                                }
			});
        });

        $("button.btn-back").click(function() {
            window.location.href = "criteria.php";
        });

        $("#remove-dialogue-data").click(function() {
            $("#confirm-remove-modal").modal({backdrop: 'static'});
            table_name = 'dialogue';
        });

        $("#remove-dialogue-npc-data").click(function() {
            $("#confirm-remove-modal").modal({backdrop: 'static'});
            table_name = 'dialogue_npc';
        });

        $('#remove-yes-button').click(function() {
            $.ajax({
				url:        "/apis/reports.php",
                dataType:   "json",
                type:       "post",
                data:       {
                                action_name:    "remove_coaching",
                                table_name:     table_name
                            },
				success:    function( data ) {
                                if (!data.status) {
                                    $.notify({
                                        icon:               'fa fa-times' || '',
                                        message:            data.msg + "<br/> SQL: " + data.error_query,
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
                                } else {
                                    $.notify({
                                        icon:               'fa fa-check',
                                        message:            data.msg,
                                        url:                ''
                                    },
                                    {
                                        element:            'body',
                                        type:               'success',
                                        allow_dismiss:      true,
                                        newest_on_top:      true,
                                        showProgressbar:    false,
                                        placement:          {
                                                                from:   'top',
                                                                align:  'center' || 'right'
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

                                }
				            }
			});
        });
    });
</script>

<?php require '../../inc/views/template_footer_end.php'; ?>