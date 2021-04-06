<?php 
    require '../../inc/config.php';

    $common->authorizePage("class_create");

    $iSAIL_UI->title = $common->getPrefixProjectName() . ' ' . 'Create Class';

    $data = $_POST;
    
    if ($common->role == 3 || !isset($data['submit'])) {
        header('Location: /index.php');
        exit;
    }

    $query = $common->generateQueryForGetUsersForClassID($data);

    $result = mysqli_query($common->db_connect, $query);

    $results = array();

    while ($row = mysqli_fetch_array($result)) {
        $results[] = $row;
    }
?>

<?php require '../../inc/views/template_head_start.php'; ?>

<!-- Page JS Plugins CSS -->
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
                Users For Class
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
            <form action="create.php" method="post" id="userForm">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/class_pages.js -->
                <table class="table table-bordered table-striped js-dataTable-full table-header-bg table-vcenter" id="userTable">
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
                                <td class="text-center"><?php echo $result['userid']; ?></td>
                                <td class="text-center"><?php echo $result['first_name']; ?></td>
                                <td class="text-center"><?php echo $result['last_name']; ?></td>
                                <td class="text-center"><?php echo $result['org_ID']; ?></td>
                                <td class="text-center"><?php echo $result['franchise']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="push-20">
                    <button type="submit" class="btn btn-sm btn-default" name="search_for_more">Search for More</button>
                    <button type="submit" class="btn btn-sm btn-primary pull-right" name="class_roster">Submit Class Roster</button>
                </div>           
            </form>
        </div>
    </div>
    <!-- END Dynamic Table Full -->

</div>
<!-- END Page Content -->

<?php require '../../inc/views/modal_note.php'; ?>

<?php require '../../inc/views/base_footer.php'; ?>
<?php require '../../inc/views/template_footer_start.php'; ?>

<!-- Page JS Plugins -->
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/datatables/dataTables.checkboxes.min.js"></script>

<!-- Page JS Code -->
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/pages/class_pages.js"></script>
<script>
    $(document).ready(function() {
        var userTable = $('#userTable').DataTable();

        var user_ids = <?php echo json_encode(isset($_SESSION['user_ids']) && is_array($_SESSION['user_ids']) ? $_SESSION['user_ids'] : array()) ?>;
        
        $("#userForm").submit(function() {
            var rows_selected = userTable.column(0).checkboxes.selected();
            if (!rows_selected.length && !user_ids.length) {
                $("#note-modal-content").text("You must choose one user at least.");
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
    });
</script>

<?php require '../../inc/views/template_footer_end.php'; ?>