<?php 
    require '../../inc/config.php'; 

    $common->authorizePage("user_management");

    $iSAIL_UI->title = $common->getPrefixProjectName() . ' ' . 'Create Users';
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
                    <i class="si si-users text-primary"></i>
                </span>
                Create Users
            </h1>
        </div>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content">
    <div class="row remove-margin-l remove-margin-r">
        <!-- Warning Alert -->
        <div class="alert alert-warning alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <p><i class='fa fa-warning'></i> When you import csv file, the same users will be removed. <em class="text-danger">Click <a class="alert-link" id="remove-users-data" href="javascript:void(0)">here</a> to remove all users from database.</em></p>
        </div>
        <!-- END Warning Alert -->
    </div>
    <div class="row remove-margin-l remove-margin-r">
        <!-- Info Alert -->
        <div class="alert alert-info alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <p><i class='fa fa-info-circle'></i> CSV Fields : Unix_ID, First Name, Last Name, Password, Email, Role, BU, Franchise, Product, Indication, and Manager</p>
        </div>
        <!-- END Info Alert -->
    </div>

    <div class="block block-themed">
        <div class="block-header bg-primary">
            <h3 class="block-title">Import users data from CSV file</h3>
        </div>
        <div class="block-content block-content-narrow">
            <!-- Upload Users Data Form -->
            <form class="form-horizontal" method="post" id="import_users_form" name="import_users_form" enctype="multipart/form-data">
                <input type="hidden" name="action_name" value="import_users" />
                <div class="form-group">
                    <label class="col-md-5 control-label">File For Importing: </label>
                    <div class="col-md-5">
                        <input type="file" class="push-5-t push-5-b" id="users_source_file" name="users_source_file" accept=".csv">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-5 col-md-offset-5">
                        <button class="btn btn-sm btn-primary" type="submit">Import</button>
                        <button class="btn btn-sm btn-default btn-back" type="button">Look up Users</button>
                    </div>
                </div>
            </form>
            <!-- END Upload Users Data Form -->
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
                            Would you import users from this csv file really?
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
                            Would you remove all users from database really?
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
        
        $("#import_users_form").submit(function(e) {
            if (document.getElementById("users_source_file").files.length == 0) {
                $("#require-file-modal").modal({backdrop: 'static'});
                return false;
            }
            
            $("#confirm-import-modal").modal({backdrop: 'static'});
            e.preventDefault();

        });

        $('#import-yes-button').click(function() {
            $.ajax({
				url:            "/apis/users.php",
				dataType:       "json",
				type:           "post",
                data:           new FormData(document.getElementById("import_users_form")),
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
            window.location.href = "management.php";
        });

        $("#remove-users-data").click(function() {
            $("#confirm-remove-modal").modal({backdrop: 'static'});
        });

        $('#remove-yes-button').click(function() {
            $.ajax({
				url:        "/apis/users.php",
                dataType:   "json",
                type:       "post",
                data:       {
                                action_name:    "remove_users"
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