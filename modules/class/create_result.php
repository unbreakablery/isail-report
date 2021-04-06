<?php
    require '../../inc/config.php';

    $common->authorizePage("class_create");

    $iSAIL_UI->title = $common->getPrefixProjectName() . ' ' . 'Create Class';

    if (!isset($_REQUEST['id']) || empty($_REQUEST['id'])) {
        header('Location: create_criteria.php');
        exit;
    }

    $fields = array(
                    array(
                        'key'       => 'class_ID', 
                        'value'     => mysqli_escape_string($common->db_connect, $_REQUEST['id']), 
                        'operator'  => '='
                    )
                );
    $query = $common->generateQueryForGetClasses($fields);
    $result = mysqli_query($common->db_connect, $query);
    
    $row = mysqli_fetch_array($result);
    if (!$row) {
        header('Location: create_criteria.php');
        exit;
    }
?>

<?php require '../../inc/views/template_head_start.php'; ?>

<!-- Page JS Plugins CSS -->
<link rel="stylesheet" href="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">

<?php require '../../inc/views/template_head_end.php'; ?>
<?php require '../../inc/views/base_head.php'; ?>

<div class="content bg-gray-lighter">
    <div class="row items-push remove-margin-l remove-margin-r">
        <div class="col-12">
            <p class="content-mini content-mini-full bg-primary text-white">The class has received the Class ID - <strong class="text-white class-id-number"><?php echo $row['class_ID'] ?></strong> . Please keep track of your Class ID number. </p>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content">
    <div class="block">
        <div class="block-content">
            <table class="table table-vcenter">
                <colgroup>
                    <col width="30%">
                    <col width="70%">
                </colgroup>
                <tbody>
                    <tr>
                        <td class="bg-success text-white text-right text-uppercase font-w700">Class ID:</td>
                        <td><?php echo $row['class_ID']; ?></td>
                    </tr>
                    <tr>
                        <td class="bg-success text-white text-right text-uppercase font-w700">Name of Class:</td>
                        <td><?php echo $row['name']; ?></td>
                    </tr>
                    <tr>
                        <td class="bg-success text-white text-right text-uppercase font-w700">Date of Class:</td>
                        <td><?php echo $row['date']; ?></td>
                    </tr>
                    <tr>
                        <td class="bg-success text-white text-right text-uppercase font-w700">Description of Class:</td>
                        <td><?php echo nl2br(htmlspecialchars($row['description'])); ?></td>
                    </tr>
                </tbody>
            </table>
            <div class="push-20">
                <a href="create_criteria.php" class="btn btn-sm btn-default">Search for More</a>
                <a href="management.php" class="btn btn-sm btn-primary pull-right">Look up Classes</a>
            </div>
        </div>
    </div>

</div>
<!-- END Page Content -->

<?php require '../../inc/views/base_footer.php'; ?>
<?php require '../../inc/views/template_footer_start.php'; ?>
<?php require '../../inc/views/template_footer_end.php'; ?>