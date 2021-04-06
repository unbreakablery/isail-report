<?php
    require '../../inc/config.php';

    $common->authorizePage("class_management");

    $iSAIL_UI->title = $common->getPrefixProjectName() . ' ' . 'Edit Class';

    if ($common->role == 3) {
        header('Location: /index.php');
        exit;
    }

    $data = $_POST;
    if (isset($data['update_class_id'])) {
        $class_id = $_REQUEST['id'];
        $user_ids = $data['user_ids'];
        $class_name = $data['name'];
        $class_date = $data['date'];
        $class_description = $data['description'];

        //update class table
        $data_fields = array(
                            array('key' => 'name',          'value' => $class_name),
                            array('key' => 'date',          'value' => $class_date),
                            array('key' => 'description',   'value' => $class_description)
                        );

        $condition_fields = array(
                                array(
                                    'key'       => 'class_ID', 
                                    'value'     => $class_id, 
                                    'operator'  => '='
                                )
                            );

        $sql = $common->generateQueryForUpdateClass($data_fields, $condition_fields);
        $result = mysqli_query($common->db_connect, $sql);

        //update users table
        for ($i = 0; $i < count($user_ids); $i += 1) {
            $user_ids[$i] = "'" . mysqli_real_escape_string($common->db_connect, $user_ids[$i]) . "'";
        }

        $data_fields = array(
                            array(
                                'key'   => 'class_ID', 
                                'value' => 0
                            )
                        );
        
        $condition_fields = array(
                                array('key' => 'class_ID',  'value' => $class_id, 'operator' => '='),
                                array('key' => 'userid',    'value' => $user_ids, 'operator' => 'NOT IN')
                            );

        $sql = $common->generateQueryForUpdateUsers($data_fields, $condition_fields);
        $result = mysqli_query($common->db_connect, $sql);

        $data_fields = array(
                            array(
                                'key'   => 'class_ID', 
                                'value' => $class_id
                            )
                        );
    
        $condition_fields = array(
                                array(
                                    'key'       => 'userid', 
                                    'value'     => $user_ids, 
                                    'operator'  => 'IN'
                                )
                            );

        $sql = $common->generateQueryForUpdateUsers($data_fields, $condition_fields);
        $result = mysqli_query($common->db_connect, $sql);

        header('Location: management.php');
        exit;
    }

    $class_id = $_REQUEST['id'];

    $fields = array(
                    array(
                        'key'       => 'class_ID', 
                        'value'     => $class_id, 
                        'operator'  => '='
                    )
                );
    
    $query = $common->generateQueryForGetClasses($fields);
    $result = mysqli_query($common->db_connect, $query);

    $classObj = mysqli_fetch_array($result);

    if ($classObj == NULL) {
        header('Location: management.php');
        exit;
    }

    //Get users belong to this class
    $fields = array(
                    array(
                        'key'       => 'class_ID', 
                        'value'     => $class_id, 
                        'operator'  => '='
                    )
                );
    $query = $common->generateQueryForGetUsersByField($fields);
    $result = mysqli_query($common->db_connect, $query);

    $selectedUserList = array();
    while ($row = mysqli_fetch_array($result)) {
        $selectedUserList[] = $row['userid'];
    };

    //Get all users
    $query = $common->generateQueryForGetUsersByField();
    $result = mysqli_query($common->db_connect, $query);

    $userList = array();
    while ($row = mysqli_fetch_array($result)) {
        $userList[] = $row;
    };

?>

<?php require '../../inc/views/template_head_start.php'; ?>

<!-- Page JS Plugins CSS -->
<link rel="stylesheet" href="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/datatables/dataTables.checkboxes.css">
<link rel="stylesheet" href="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css">

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
                Edit Class
            </h1>
        </div>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content">
    <div class="block">
        <div class="block-header">
            <h3 class="block-title pull-left text-primary">Class Details</h3>
            <div class="text-right">
                <button class="btn btn-sm btn-default btn-back pull-right push-5-r" type="button">Look up Classes</button>
            </div>
        </div>
        <div class="block-content">
            <form class="form-horizontal push-10-t" id="classForm" method="post">
                <div class="form-group">
                    <div class="col-sm-6">
                        <div class="form-material">
                            <input class="form-control" type="text" name="name" placeholder="Name of Class" value="<?php echo $classObj['name']; ?>" />
                            <label class="text-uppercase">Name <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-material">
                            <input class="form-control js-datepicker" type="text" name="date" data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd" value="<?php echo $classObj['date']; ?>" />
                            <label class="text-uppercase">Date <span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <div class="form-material">
                            <textarea class="form-control" type="text" name="description" placeholder="Description"><?php echo $classObj['description']; ?></textarea>
                            <label class="text-uppercase">Description</label>
                        </div>
                    </div>
                </div>

                <table class="table table-bordered table-striped table-header-bg table-vcenter selectCallback-dataTable" id="userTable">
                    <thead>
                        <tr>
                            <th class="text-center w-pt-5"></th>
                            <th class="text-center">UNIX_ID</th>
                            <th class="text-center">First Name</th>
                            <th class="text-center">Last Name</th>
                            <th class="text-center">BU</th>
                            <th class="text-center">Franchise</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($userList as $user) { ?>
                            <tr>
                                <td class="text-center"><?php echo $user['userid']; ?></td>
                                <td class="text-center user-id"><?php echo $user['userid']; ?></td>
                                <td class="text-center first-name"><?php echo $user['first_name']; ?></td>
                                <td class="text-center last-name"><?php echo $user['last_name']; ?></td>
                                <td class="text-center"><?php echo $user['org_ID']; ?></td>
                                <td class="text-center"><?php echo $user['franchise']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="push-50">
                    <button class="btn btn-sm btn-primary pull-right" type="submit">Update Class</button>
                    <button class="btn btn-sm btn-default btn-back pull-right push-5-r" type="button">Look up Classes</button>
                </div>
            </form>            
        </div>
    </div>

</div>
<!-- END Page Content -->

<!-- Confirm Update Modal -->
<div class="modal fade" id="confirm-update-modal" tabindex="-1" role="dialog" aria-hidden="true">
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
                            Would you update this class information really?
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-sm btn-primary" type="button" data-dismiss="modal" id="update-yes-button"><i class="fa fa-check"></i> Yes</button>
            </div>
        </div>
    </div>
</div>
<!-- END Confirm Update Modal -->

<?php require '../../inc/views/modal_note.php'; ?>

<?php require '../../inc/views/base_footer.php'; ?>
<?php require '../../inc/views/template_footer_start.php'; ?>

<!-- Page JS Plugins -->
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/datatables/dataTables.checkboxes.min.js"></script>

<!-- Page JS Code -->
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/pages/class_pages.js"></script>
<script>

    iSAIL_UI.initHelper('datepicker');

    $(document).ready(function() {
        var userTable = $('#userTable').DataTable();
        var selectedUserList = <?php echo json_encode($selectedUserList); ?>;
        $.each(userTable.columns(0).data()[0], function(index, value) {
            if (selectedUserList.indexOf(value) != -1) {
                var item = userTable.row(index).node();
                $(item).find('input[type=checkbox]').prop('checked', true);
            }
        });

        var rows_selected = [];

        $("#classForm").submit(function(e) {
            if (!$('input[name="name"]').val()) {
                $("#note-modal-content").text("Class Name is required.");
                $("#note-modal").modal({backdrop: 'static'});
                return false;
            }
            if (!$('input[name="date"]').val()) {
                $("#note-modal-content").text("Date is required.");
                $("#note-modal").modal({backdrop: 'static'});
                return false;
            }

            rows_selected = [];
            $.each(userTable.rows().nodes(), function(index, node) {
                if ($(node).find('input[type=checkbox]').prop('checked')) {
                    rows_selected.push($(node).find('td.user-id').html());
                }
            });
            
            if (!rows_selected.length) {
                $("#note-modal-content").text("You must choose one user at least.");
                $("#note-modal").modal({backdrop: 'static'});
                return false;
            }

            e.preventDefault();
            
            $("#confirm-update-modal").modal({backdrop: 'static'});
        });

        $("button#update-yes-button").click(function() {
            $.each(rows_selected, function(index, rowId){
                // Create a hidden element
                $("#classForm").append(
                    $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', 'user_ids[]')
                        .val(rowId)
                );
            });

            $("#classForm").append(
                $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', 'update_class_id')
                    .val(true)
            );

            $("#classForm")[0].submit();
        });

        $('#userTable tbody').on('click', 'tr td:first-child input[type=checkbox]', function (e) {
            var row = $(this).closest('tr');
            if (!$(this).is(':checked')) {
                var username = userTable.row(row).data()[2] + " " + userTable.row(row).data()[3];
                if (confirm("Are you sure you want to unselect '" + username + "' from Roster?")) {
                    $(this).prop('checked', false);
                } else {
                    $(this).prop('checked', true);
                }
            }
        } );

        $("button.btn-back").click(function() {
            window.location.href = "management.php";
        });
    });
</script>

<?php require '../../inc/views/template_footer_end.php'; ?>