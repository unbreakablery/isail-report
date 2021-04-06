<?php 
    require '../../inc/config.php';
    
    $common->authorizePage("user_management");

    $iSAIL_UI->title = $common->getPrefixProjectName() . ' ' . 'User Management';

    //Get all users
    $query = $common->generateQueryForGetUsers();

    $result = mysqli_query($common->db_connect, $query);

    $users = array();

    while ($row = mysqli_fetch_array($result)) {
        $row['role_text'] = $common->getRoleText($row['role']);
        $users[] = $row;
    }

?>

<?php require '../../inc/views/template_head_start.php'; ?>

<!-- Page JS Plugins CSS -->
<link rel="stylesheet" href="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">

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
                User Management
            </h1>
        </div>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content">
    <!-- Dynamic Table Full -->
    <div class="block">
        <div class="block-content">
            <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/player_report.js -->
            <table class="table table-bordered table-striped js-dataTable-full table-header-bg table-vcenter" id="userTable">
                <thead>
                    <tr>
                        <th class="text-center">&#8470;</th>
                        <th class="text-center">User ID</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Role</th>
                        <th class="text-center hidden-md">Organization</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($users); $i++) { ?>
                        <tr>
                            <td class="text-center"><?php echo ($i + 1); ?></td>
                            <td class="text-left"><?php echo $users[$i]['userid']; ?></td>
                            <td class="text-left"><?php echo $users[$i]['first_name'] . ' ' . $users[$i]['last_name']; ?></td>
                            <td class="text-center"><?php echo $users[$i]['role_text']; ?></td>
                            <td class="text-center hidden-md"><?php echo $users[$i]['organization']; ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button class="btn btn-success btn-rounded" type="button" data-id="<?php echo $users[$i]['id']; ?>">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-rounded" type="button" data-id="<?php echo $users[$i]['userid']; ?>">
                                        <i class="fa fa-remove"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- END Dynamic Table Full -->
</div>
<!-- END Page Content -->

<!-- Confirm Modal -->
<div class="modal fade" id="modal-popout" tabindex="-1" role="dialog" aria-hidden="true">
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
                        <p><i class="fa fa-question-circle"></i> Would you remove this user really?</p>
                        <p class="text-danger"><em><i class="fa fa-warning"></i> All conversation and level data associated with this user will be removed.</em></p>
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
<!-- END Confirm Modal -->

<?php require '../../inc/views/base_footer.php'; ?>
<?php require '../../inc/views/template_footer_start.php'; ?>

<!-- Page JS Plugins -->
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>

<!-- Page JS Code -->
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/pages/player_report.js"></script>
<script>
    $(document).ready(function() {

        var table = $('#userTable').DataTable();
        var removeTarget = null;
        var removeUserId = 0;
 
        $('#userTable tbody').on( 'click', 'button.btn-danger', function () {
            $('#modal-popout').modal({backdrop: 'static'});
            removeTarget = table.row( $(this).parents('tr') );
            removeUserId = $(this).attr('data-id');
        } );

        $('#userTable tbody').on( 'click', 'button.btn-success', function () {
            editUserId = $(this).data('id');
            window.location.href = "edit.php?id=" + editUserId;
        } );

        $('#remove-yes-button').click(function() {
            $.ajax({
				url:        "/apis/users.php",
				dataType:   "json",
				type:       "post",
				data:       {
                                action_name:    'remove_user',
                                user_id:        removeUserId
                            },
				success:    function( data ) {
                                if (!data.status) {
                                    $.notify({
                                        icon:               'fa fa-times',
                                        message:            data.msg + "<br/>" + data.error,
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
                                    removeTarget.remove().draw();

                                    $.notify({
                                        icon:               'fa fa-check',
                                        message:            data.msg,
                                        url:                'management.php'
                                    },
                                    {
                                        element:            'body',
                                        type:               'success',
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
                                                            },
                                        onClosed:           function() { 
                                                                window.location.href = "management.php"; 
                                                            }
                                    });
                                }
				            }
			});
        });
    });
</script>

<?php require '../../inc/views/template_footer_end.php'; ?>