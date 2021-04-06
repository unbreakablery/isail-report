<?php 
    require '../../inc/config.php';

    $common->authorizePage("class_management");

    $iSAIL_UI->title = $common->getPrefixProjectName() . ' ' . 'Class Management';
    
    $query = $common->generateQueryForGetClasses();
    $result = mysqli_query($common->db_connect, $query);

    $rows = array();

    while ($row = mysqli_fetch_array($result)) {
        $rows[] = $row;
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
                    <i class="fa fa-users text-primary"></i>
                </span>
                Class Management
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
            <table class="table table-bordered table-striped js-dataTable-full table-header-bg table-vcenter" id="classTable">
                <thead>
                    <tr>
                        <th class="text-center w-pt-15">Class ID</th>
                        <th class="text-center w-pt-20">Name</th>
                        <th class="text-center w-pt-15">Date</th>
                        <th class="text-center hidden-md">Description</th>
                        <th class="text-center w-pt-20">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $i => $row) { ?>
                        <tr>
                            <td class="text-center"><?php echo $row['class_ID']; ?></td>
                            <td class="text-center"><?php echo $row['name']; ?></td>
                            <td class="text-center"><?php echo $row['date']; ?></td>
                            <td class="text-left hidden-md"><?php echo nl2br(htmlspecialchars($row['description'])); ?></td> 
                            <td class="text-center">
                                <div class="btn-group">
                                    <button class="btn btn-success btn-rounded" type="button" data-id="<?php echo $row['class_ID']; ?>">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-rounded" type="button" data-id="<?php echo $row['class_ID']; ?>">
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
                        <p><i class="fa fa-question-circle"></i> Would you remove this class really?</p>
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

        var table = $('#classTable').DataTable();
        var editTarget = null;
        var editClassId = 0;

        $('#classTable tbody').on( 'click', 'button.btn-danger', function () {
            $('#modal-popout').modal({backdrop: 'static'});
            editTarget = table.row( $(this).parents('tr') );
            editClassId = $(this).attr('data-id');
        } );

        $('#classTable tbody').on( 'click', 'button.btn-success', function () {
            editClassId = $(this).data('id');
            window.location.href = "edit.php?id=" + editClassId;
        } );

        $('#remove-yes-button').click(function() {
            $.ajax({
				url:        "/apis/class.php",
				dataType:   "json",
				type:       "post",
				data:       {
                                action_type:    'delete',
                                class_id:       editClassId
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
                                    editTarget.remove().draw();

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
                                }
                            }
			});
        });
    });
</script>

<?php require '../../inc/views/template_footer_end.php'; ?>