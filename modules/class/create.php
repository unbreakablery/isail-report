<?php 
    require '../../inc/config.php';

    $common->authorizePage("class_create");

    $iSAIL_UI->title = $common->getPrefixProjectName() . ' ' . 'Create Class';

    if ($common->role == 3) {
        header('Location: /index.php');
        exit;
    }

    $data = $_POST;
    $user_ids = isset($_SESSION['user_ids']) && is_array($_SESSION['user_ids']) ? $_SESSION['user_ids'] : array();
    if (empty($user_ids) && empty($data['ids'])) {
        header('Location: create_criteria.php');
        exit;
    }

    // check if class create
    if (isset($data['create_class_id'])) {
        $data_fields = array(
                            array('key' => 'name',          'value' => mysqli_real_escape_string($common->db_connect, $data['name'])),
                            array('key' => 'description',   'value' => mysqli_real_escape_string($common->db_connect, $data['description'])),
                            array('key' => 'date',          'value' => mysqli_real_escape_string($common->db_connect, $data['date'])),
                            array('key' => 'trainer_ID',    'value' => 0)
                        );
        $sql = $common->generateQueryForInsertClass($data_fields);
        
        $res = mysqli_query($common->db_connect, $sql);

        if ($res) {
            $class_id = mysqli_insert_id($common->db_connect);

            // wrap with string
            $user_ids = $data['ids'];
            for ($i = 0; $i < count($user_ids); $i += 1) {
                $user_ids[$i] = "'" . mysqli_real_escape_string($common->db_connect, $user_ids[$i]) . "'";
            }
            
            $data_fields = array(
                    array('key' => 'class_ID',  'value' => $class_id)
                );
    
            $condition_fields = array(
                    array('key' => 'userid',    'value' => $user_ids, 'operator' => 'IN')
                );

            $sql = $common->generateQueryForUpdateUsers($data_fields, $condition_fields);
            $result = mysqli_query($common->db_connect, $sql);
        }

        $_SESSION['user_ids'] = array();

        header('Location: create_result.php?id=' . $class_id);
        exit;
    } else {
        // save ids
        $user_ids = array_merge($user_ids, (isset($data['ids']) && is_array($data['ids'])) ? $data['ids'] : []);
        $_SESSION['user_ids'] = $user_ids;

        if (isset($data['search_for_more'])) {
            header('Location: create_criteria.php');
            exit;
        }

        // wrap with string
        for ($i = 0; $i < count($user_ids); $i += 1) {
            $user_ids[$i] = "'" . mysqli_real_escape_string($common->db_connect, $user_ids[$i]) . "'";
        }
    }

    $fields = array(
                    array('key' => 'userid', 'value' => $user_ids, 'operator' => 'IN')
                );
    $query = $common->generateQueryForGetUsersByField($fields);
    $result = mysqli_query($common->db_connect, $query);

    $results = array();
    while ($row = mysqli_fetch_array($result)) {
        $results[] = $row;
    }
?>

<?php require '../../inc/views/template_head_start.php'; ?>

<!-- Page JS Plugins CSS -->
<link rel="stylesheet" href="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css">
<link rel="stylesheet" href="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/datatables/dataTables.checkboxes.css">

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
                Create Class
            </h1>
        </div>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content">
    <div class="block">
        <div class="block-header">
            <h3 class="block-title text-primary">Class Details</h3>
        </div>
        <div class="block-content">
            <form class="form-horizontal push-10-t" id="classForm" method="post">
                <div class="form-group">
                    <div class="col-sm-6">
                        <div class="form-material">
                            <input class="form-control" type="text" name="name" placeholder="Name of Class">
                            <label class="text-uppercase">Name <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-material">
                            <input class="form-control js-datepicker" type="text" name="date" data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd">
                            <label class="text-uppercase">Date <span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-6">
                        <div class="form-material">
                            <textarea class="form-control" type="text" name="description" placeholder="Description"></textarea>
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
                        <?php foreach ($results as $result) { ?>
                            <tr>
                                <td class="text-center"><?php echo $result['userid']; ?></td>
                                <td class="text-center user-id"><?php echo $result['userid']; ?></td>
                                <td class="text-center first-name"><?php echo $result['first_name']; ?></td>
                                <td class="text-center last-name"><?php echo $result['last_name']; ?></td>
                                <td class="text-center"><?php echo $result['org_ID']; ?></td>
                                <td class="text-center"><?php echo $result['franchise']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="push-20">
                    <a href="create_criteria.php" class="btn btn-sm btn-default">Search for More</a>
                    <button class="btn btn-sm btn-primary pull-right" type="submit" name="create_class_id">Create Class</button>
                </div>
            </form>            
        </div>
    </div>

</div>
<!-- END Page Content -->

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
        userTable.columns(0).checkboxes.select();

        $("#classForm").submit(function() {
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

            var rows_selected = [];
            $.each(userTable.rows().nodes(), function(index, node) {
                if ($(node).find('input[type=checkbox]').prop('checked')) {
                    rows_selected.push($(node).find('td.user-id').html());
                }
            });
            if (!rows_selected.length) {
                $("#note-modal-content").text("You should check one user at least.");
                $("#note-modal").modal({backdrop: 'static'});
                return false;
            }

            var form = this;
            $.each(rows_selected, function(index, rowId){
                // Create a hidden element
                $(form).append(
                    $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', 'ids[]')
                        .val(rowId)
                );
            });
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
    });
</script>

<?php require '../../inc/views/template_footer_end.php'; ?>