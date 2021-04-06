<?php 
    require '../../inc/config.php';

    $common->authorizePage("player_report");

    $iSAIL_UI->title = $common->getPrefixProjectName() . ' ' . 'Player Report';

    if (empty($_POST)) {
        header('Location: criteria.php');
        exit;
    }

    $level = isset($_POST['level']) ? $_POST['level'] : '1';

    $time_period_label = $common->getTimePeriodLabel($_POST['time_period']);

    if ($_POST['time_period'] == 'specific-date') {
        if (isset($_POST['specific_date'])) {
            $time_period_label .= ' ' . $_POST['specific_date'];
        }
        if (isset($_POST['specific_time'])) {
            $time_period_label .= ' ' . $_POST['specific_time'];
        }
    }

    if (isset($_POST['game_key_number']) && !empty($_POST['game_key_number'])) {
        $time_period_label .= ' [' . $_POST['game_key_number'] . ']';
    }

    $query = $common->generateISAILQuery($_POST);

    $result = mysqli_query($common->db_connect, $query);

    $players = array();

    while ($row = mysqli_fetch_array($result)) {
        $temp = array();
        $temp['userid']         = $row['userid'];
        $temp['username']       = $row['first_name'] . ' ' . $row['last_name'];
        $temp['time']           = $row['time'];

        $temp['sales1']         = $common->getScoreByParam($level, 'sales1', $row['sales1']);
        $temp['sales1_color']   = $common->getProgressClassName($temp['sales1']);

        $temp['sales2']         = $common->getScoreByParam($level, 'sales2', $row['sales2']);
        $temp['sales2_color']   = $common->getProgressClassName($temp['sales2']);

        $temp['sales3']         = $common->getScoreByParam($level, 'sales3', $row['sales3']);
        $temp['sales3_color']   = $common->getProgressClassName($temp['sales3']);

        $temp['sales4']         = $common->getScoreByParam($level, 'sales4', $row['sales4']);
        $temp['sales4_color']   = $common->getProgressClassName($temp['sales4']);

        $temp['sales5']         = $common->getScoreByParam($level, 'sales5', $row['sales5']);
        $temp['sales5_color']   = $common->getProgressClassName($temp['sales5']);

        $temp['sales6']         = $common->getScoreByParam($level, 'sales6', $row['sales6']);
        $temp['sales6_color']   = $common->getProgressClassName($temp['sales6']);

        $temp['sales7']         = $common->getScoreByParam($level, 'sales7', $row['sales7']);
        $temp['sales7_color']   = $common->getProgressClassName($temp['sales7']);

        $temp['sales8']         = $common->getScoreByParam($level, 'sales8', $row['sales8']);
        $temp['sales8_color']   = $common->getProgressClassName($temp['sales8']);
        
        $temp['sales9']         = $common->getScoreByParam($level, 'sales9', $row['sales9']);
        $temp['sales9_color']   = $common->getProgressClassName($temp['sales9']);

        array_push($players, $temp);
    }

    if (isset($_POST['export_csv'])) {
        header('Content-Description: File Transfer');
        header('Content-Encoding: UTF-8');
        header('Content-Type: application/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $pdf->getFileNameForExport('PR', 'csv') . '"');
        header('Expires: 0');
    
        ob_start();
        
        echo "\xEF\xBB\xBF"; // UTF-8 BOM

        echo "Player In-Game Performance Report" . "\r\n";

        echo "\r\n";

        echo "Level: " . $level . "\r\n";

        echo "Time Period: " . $time_period_label . "\r\n";

        echo "\r\n";

        echo "№,PLAYER,TIMES," . strtoupper($common->getSalesLabels('sales_group_1', 'label')) 
            . ",,," . strtoupper($common->getSalesLabels('sales_group_2', 'label')) 
            . ",,," . strtoupper($common->getSalesLabels('sales_group_3', 'label'))
            . ",,\r\n";
        echo ",,";
        echo "," . $common->getSalesLabels('sales1', 'short_label');
        echo "," . $common->getSalesLabels('sales2', 'short_label');
        echo "," . $common->getSalesLabels('sales3', 'short_label');
        echo "," . $common->getSalesLabels('sales4', 'short_label');
        echo "," . $common->getSalesLabels('sales5', 'short_label');
        echo "," . $common->getSalesLabels('sales6', 'short_label');
        echo "," . $common->getSalesLabels('sales7', 'short_label');
        echo "," . $common->getSalesLabels('sales8', 'short_label');
        echo "," . $common->getSalesLabels('sales9', 'short_label');
        echo "\r\n";

        foreach ($players as $i => $player) {
            echo ($i + 1) . ",";
            echo $player['username']    . ",";
            echo $player['time']        . ",";
            echo $player['sales1']      . "%" . ",";
            echo $player['sales2']      . "%" . ",";
            echo $player['sales3']      . "%" . ",";
            echo $player['sales4']      . "%" . ",";
            echo $player['sales5']      . "%" . ",";
            echo $player['sales6']      . "%" . ",";
            echo $player['sales7']      . "%" . ",";
            echo $player['sales8']      . "%" . ",";
            echo $player['sales9']      . "%" . ",";
            echo "\r\n";
        }
    
        $csv = ob_get_contents();
        ob_end_clean();
        header("Content-Length: " . strlen($csv));
        echo $csv;
    
        exit;
    } else if (isset($_POST['btn_export_pdf'])) {
        $theme = 'default';

        if(isset($_COOKIE['colorTheme']) && $_COOKIE['colorTheme'] != NULL) {
            $colorTheme = explode('/', $_COOKIE['colorTheme']);
            $colorTheme = end($colorTheme);
            $colorTheme = explode('.', $colorTheme);
            $theme = $colorTheme[0];
        }
        
        $primaryColor = $common->getPrimaryColorByTheme($theme);
        
        ob_start();
        ?>
        <?php echo '<style type="text/css">'; ?>
        <?php include '../../' . $iSAIL_UI->assets_folder. '/css/pdfs/player_report.css'; ?>
        <?php echo '</style>'; ?>

        <page>
            <h1 class="text-center">Player In-Game Performance Report</h1>
            <h4 class="text-left remove-margin-t push-10-b">
                <table>
                    <tr>
                        <td class="text-right padding-10-r remove-border">Level:</td>
                        <td class="text-primary remove-border"><em><?php echo $level; ?></em></td>
                    </tr>
                    <tr>
                        <td class="text-right padding-10-r remove-border">Time Period:</td>
                        <td class="text-primary remove-border"><em><?php echo $time_period_label; ?></em></td>
                    </tr>
                </table>
            </h4>
            <div class="text-left font-weight-bold">
                <table class="v-center no-border">
                    <tr>
                        <td class="w-px-16">
                            <div class="icon-info-circle">i</div>
                        </td>
                        <td>
                            <span class="text-primary">Legend: </span>
                            <span class="text-danger"><?php echo $common->getLegend('foundation'); ?>, </span>
                            <span class="text-warning"><?php echo $common->getLegend('deep'); ?>, </span>
                            <span class="text-success"><?php echo $common->getLegend('advanced'); ?></span>
                        </td>
                    </tr>
                </table>
            </div>
            <table border="1" cellpadding="5" class="text-center text-white full-width">
                <thead>
                    <tr>
                        <th rowspan="2" class="default-label">No</th>
                        <th rowspan="2" class="default-label">Player</th>
                        <th rowspan="2" class="default-label">Times</th>
                        <th colspan="3" class="sales-group-label"><?php echo strtoupper($common->getSalesLabels('sales_group_1', 'label')); ?></th>
                        <th colspan="3" class="sales-group-label"><?php echo strtoupper($common->getSalesLabels('sales_group_2', 'label')); ?></th>
                        <th colspan="3" class="sales-group-label"><?php echo strtoupper($common->getSalesLabels('sales_group_3', 'label')); ?></th>
                    </tr>
                    <tr class="sales-label">
                        <th><?php echo $common->getSalesLabels('sales1', 'short_label'); ?></th>
                        <th><?php echo $common->getSalesLabels('sales2', 'short_label'); ?></th>
                        <th><?php echo $common->getSalesLabels('sales3', 'short_label'); ?></th>
                        <th><?php echo $common->getSalesLabels('sales4', 'short_label'); ?></th>
                        <th><?php echo $common->getSalesLabels('sales5', 'short_label'); ?></th>
                        <th><?php echo $common->getSalesLabels('sales6', 'short_label'); ?></th>
                        <th><?php echo $common->getSalesLabels('sales7', 'short_label'); ?></th>
                        <th><?php echo $common->getSalesLabels('sales8', 'short_label'); ?></th>
                        <th><?php echo $common->getSalesLabels('sales9', 'short_label'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($players); $i++) { ?>
                    <tr>
                        <td class="w-pt-4 text-black">
                            <?php echo ($i + 1); ?>
                        </td>
                        <td class="w-pt-24 text-black">
                            <?php echo $players[$i]['username']; ?>
                        </td>
                        <td class="w-pt-9 text-black">
                            <?php echo $players[$i]['time']; ?>
                        </td>
                        <td class="w-pt-7 <?php echo $players[$i]['sales1_color']; ?>">
                            <?php echo $players[$i]['sales1']; ?>%
                        </td>
                        <td class="w-pt-7 <?php echo $players[$i]['sales2_color']; ?>">
                            <?php echo $players[$i]['sales2']; ?>%
                        </td>
                        <td class="w-pt-7 <?php echo $players[$i]['sales3_color']; ?>">
                            <?php echo $players[$i]['sales3']; ?>%
                        </td>
                        <td class="w-pt-7 <?php echo $players[$i]['sales4_color']; ?>">
                            <?php echo $players[$i]['sales4']; ?>%
                        </td>
                        <td class="w-pt-7 <?php echo $players[$i]['sales5_color']; ?>">
                            <?php echo $players[$i]['sales5']; ?>%
                        </td>
                        <td class="w-pt-7 <?php echo $players[$i]['sales6_color']; ?>">
                            <?php echo $players[$i]['sales6']; ?>%
                        </td>
                        <td class="w-pt-7 <?php echo $players[$i]['sales7_color']; ?>">
                            <?php echo $players[$i]['sales7']; ?>%
                        </td>
                        <td class="w-pt-7 <?php echo $players[$i]['sales8_color']; ?>">
                            <?php echo $players[$i]['sales8']; ?>%
                        </td>
                        <td class="w-pt-7 <?php echo $players[$i]['sales9_color']; ?>">
                            <?php echo $players[$i]['sales9']; ?>%
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </page>
        <?php
        $content = ob_get_contents();
        ob_end_clean();
        $pdf->generatePDF($content, 'L', 'PR');

        exit;
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
                    <i class="fa fa-table text-primary"></i>
                </span>
                Player In-Game Performance Report
            </h1>
        </div>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content">
    <!-- Dynamic Table Full -->
    <div class="block">
        <div class="block-header">
            <h3 class="block-title pull-left">
                <table>
                    <tr>
                        <td class="text-right padding-10-r">Level:</td>
                        <td><small><?php echo $level; ?></small></td>
                    </tr>
                    <tr>
                        <td class="text-right padding-10-r">Time Period:</td>
                        <td><small><?php echo $time_period_label; ?></small></td>
                    </tr>
                </table>
            </h3>
            <div class="text-right">
                <button class="btn btn-default btn-back" type="button">Back</button>
                <button class="btn btn-primary" data-toggle="modal" data-target="#modal-popout" type="button"><i class="fa fa-info-circle"></i> Legend</button>
                <button class="btn btn-primary" id="btn_export_csv"><i class="fa fa-file-excel-o"></i> Export CSV</button>
                <button class="btn btn-primary" id="btn_export_pdf"><i class="fa fa-file-pdf-o"></i> Export in PDF</button>
            </div>
        </div>
        <div class="block-content">
            <div class="table-responsive">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/player_report.js -->
                <table class="table table-bordered table-striped js-dataTable-full table-header-bg table-vcenter">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-center">&#8470;</th>
                            <th rowspan="2" class="text-center">Player</th>
                            <th rowspan="2" class="text-center" data-toggle="tooltip" data-placement="top" title="Time in Level, Seconds">Times</th>
                            <th colspan="3" class="text-center sales-group-label"><?php echo strtoupper($common->getSalesLabels('sales_group_1', 'label')); ?></th>
                            <th colspan="3" class="text-center sales-group-label"><?php echo strtoupper($common->getSalesLabels('sales_group_2', 'label')); ?></th>
                            <th colspan="3" class="text-center sales-group-label"><?php echo strtoupper($common->getSalesLabels('sales_group_3', 'label')); ?></th>
                        </tr>
                        <tr>
                            <th class="text-center sales-label" data-toggle="tooltip" data-placement="top" title="<?php echo $common->getSalesLabels('sales1', 'label'); ?>"><?php echo $common->getSalesLabels('sales1', 'short_label'); ?></th>
                            <th class="text-center sales-label" data-toggle="tooltip" data-placement="top" title="<?php echo $common->getSalesLabels('sales2', 'label'); ?>"><?php echo $common->getSalesLabels('sales2', 'short_label'); ?></th>
                            <th class="text-center sales-label" data-toggle="tooltip" data-placement="top" title="<?php echo $common->getSalesLabels('sales3', 'label'); ?>"><?php echo $common->getSalesLabels('sales3', 'short_label'); ?></th>
                            <th class="text-center sales-label" data-toggle="tooltip" data-placement="top" title="<?php echo $common->getSalesLabels('sales4', 'label'); ?>"><?php echo $common->getSalesLabels('sales4', 'short_label'); ?></th>
                            <th class="text-center sales-label" data-toggle="tooltip" data-placement="top" title="<?php echo $common->getSalesLabels('sales5', 'label'); ?>"><?php echo $common->getSalesLabels('sales5', 'short_label'); ?></th>
                            <th class="text-center sales-label" data-toggle="tooltip" data-placement="top" title="<?php echo $common->getSalesLabels('sales6', 'label'); ?>"><?php echo $common->getSalesLabels('sales6', 'short_label'); ?></th>
                            <th class="text-center sales-label" data-toggle="tooltip" data-placement="top" title="<?php echo $common->getSalesLabels('sales7', 'label'); ?>"><?php echo $common->getSalesLabels('sales7', 'short_label'); ?></th>
                            <th class="text-center sales-label" data-toggle="tooltip" data-placement="top" title="<?php echo $common->getSalesLabels('sales8', 'label'); ?>"><?php echo $common->getSalesLabels('sales8', 'short_label'); ?></th>
                            <th class="text-center sales-label" data-toggle="tooltip" data-placement="top" title="<?php echo $common->getSalesLabels('sales9', 'label'); ?>"><?php echo $common->getSalesLabels('sales9', 'short_label'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < count($players); $i++) { ?>
                        <tr>
                            <td class="text-center">
                                <?php echo ($i + 1); ?>
                            </td>
                            <td class="text-center">
                                <?php echo $players[$i]['username']; ?>
                            </td>
                            <td class="text-center">
                                <?php echo $players[$i]['time']; ?>
                            </td>
                            <td class="text-center text-white <?php echo $players[$i]['sales1_color']; ?>">
                                <?php echo $players[$i]['sales1']; ?>%
                            </td>
                            <td class="text-center text-white <?php echo $players[$i]['sales2_color']; ?>">
                                <?php echo $players[$i]['sales2']; ?>%
                            </td>
                            <td class="text-center text-white <?php echo $players[$i]['sales3_color']; ?>">
                                <?php echo $players[$i]['sales3']; ?>%
                            </td>
                            <td class="text-center text-white <?php echo $players[$i]['sales4_color']; ?>">
                                <?php echo $players[$i]['sales4']; ?>%
                            </td>
                            <td class="text-center text-white <?php echo $players[$i]['sales5_color']; ?>">
                                <?php echo $players[$i]['sales5']; ?>%
                            </td>
                            <td class="text-center text-white <?php echo $players[$i]['sales6_color']; ?>">
                                <?php echo $players[$i]['sales6']; ?>%
                            </td>
                            <td class="text-center text-white <?php echo $players[$i]['sales7_color']; ?>">
                                <?php echo $players[$i]['sales7']; ?>%
                            </td>
                            <td class="text-center text-white <?php echo $players[$i]['sales8_color']; ?>">
                                <?php echo $players[$i]['sales8']; ?>%
                            </td>
                            <td class="text-center text-white <?php echo $players[$i]['sales9_color']; ?>">
                                <?php echo $players[$i]['sales9']; ?>%
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="row push-10 push-5-t remove-margin-l remove-margin-r">
                <div class="text-left push-10-t font-w700">
                    <span class="text-primary fa-pull-left push-10-r"><i class="fa fa-info-circle"></i> Legend : </span>
                    <span class="text-danger cursor-pointer" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $common->getLegendColor('foundation'); ?> <?php echo $common->getLegendScoreRange('foundation'); ?>"><?php echo $common->getLegendLabel('foundation'); ?>, </span>
                    <span class="text-warning cursor-pointer" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $common->getLegendColor('deep'); ?> <?php echo $common->getLegendScoreRange('deep'); ?>"><?php echo $common->getLegendLabel('deep'); ?>, </span>
                    <span class="text-success cursor-pointer" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $common->getLegendColor('advanced'); ?> <?php echo $common->getLegendScoreRange('advanced'); ?>"><?php echo $common->getLegendLabel('advanced'); ?></span>
                </div>
                <div class="text-right">
                    <button class="btn btn-default btn-back push-m45-t" type="button">Back</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END Dynamic Table Full -->
</div>
<!-- END Page Content -->

<?php require '../../inc/views/modal_legend.php'; ?>

<?php require '../../inc/views/modal_confirm_export.php'; ?>

<?php require '../../inc/views/base_footer.php'; ?>
<?php require '../../inc/views/template_footer_start.php'; ?>

<!-- Global JS -->
<?php require '../../inc/views/global.js.php'; ?>

<!-- Page JS Plugins -->
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.js"></script>

<!-- Page JS Code -->
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/pages/player_report.js"></script>
<script>
    $(document).ready(function() {

        $('#btn_export_csv').click(function () {
            $('#confirm-modal-content').text("Would you export this data in CSV really?");
            $('#export-pdf-yes').addClass('d-none');
            $('#export-csv-yes').removeClass('d-none');
            $('#confirm-modal').modal({backdrop: 'static'});
        });

        $('#export-csv-yes').click(function() {
            exportForm = getBasicForm();
            exportForm.push("<input type='hidden' name='export_csv' value='export_csv' />");
            exportForm.push('</form>');
            $(exportForm.join('')).prependTo('body').submit();
        });

        $('#btn_export_pdf').click(function () {
            $('#confirm-modal-content').text("Would you export this data in PDF really?");
            $('#export-pdf-yes').removeClass('d-none');
            $('#export-csv-yes').addClass('d-none');
            $('#confirm-modal').modal({backdrop: 'static'});
        });

        $('#export-pdf-yes').click(function() {
            exportForm = getBasicForm();
            exportForm.push("<input type='hidden' name='btn_export_pdf' value='btn_export_pdf' />");
            exportForm.push('</form>');
            $(exportForm.join('')).prependTo('body').submit();
        });

        $("button.btn-back").click(function() {
            window.location.href = "criteria.php";
        });
    });
</script>

<?php require '../../inc/views/template_footer_end.php'; ?>