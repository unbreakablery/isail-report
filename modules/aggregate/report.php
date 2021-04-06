<?php 
    require '../../inc/config.php';
    
    $common->authorizePage("aggregate_report");

    $iSAIL_UI->title = $common->getPrefixProjectName() . ' ' . 'Aggregate Report';

    if (empty($_POST)) {
        header('Location: criteria.php');
        exit;
    }

    $organization = isset($_POST['organization']) ? $_POST['organization'] : '';

    if (isset($_POST['from_back']) && $_POST['from_back'] == 1) {
        $pureLevel = isset($_SESSION['level']) ? $_SESSION['level'] : '1';
        $organization = $_SESSION['user_bu'];
        $time_period = $_SESSION['time_period'];
    } else {
        $pureLevel = isset($_POST['level']) ? $_POST['level'] : '1';
        $time_period = isset($_POST['time_period']) ? $_POST['time_period'] : '';
    }

    $time_period_label = $common->getTimePeriodLabel($time_period);

    $_SESSION['user_bu'] = $organization;

    if (isset($_POST['from_back']) && $_POST['from_back'] == 1) {
        $query = $common->generateQueryForAggregateReport($_SESSION);

        if (isset($_SESSION['specific_date']) && !empty($_SESSION['specific_date'])) {
            $time_period_label .= ' ' . $_SESSION['specific_date'];
        }

        if (isset($_SESSION['specific_time']) && !empty($_SESSION['specific_time'])) {
            $time_period_label .= ' ' . substr($_SESSION['specific_time'], 0, 8);
        }

        if (isset($_SESSION['game_key_number']) && !empty($_SESSION['game_key_number'])) {
            $time_period_label .= ' [' . $_SESSION['game_key_number'] . ']';
        }
    } else {
        $query = $common->generateQueryForAggregateReport($_POST);

        if (isset($_POST['specific_date']) && !empty($_POST['specific_date'])) {
            $time_period_label .= ' ' . $_POST['specific_date'];
        }

        if (isset($_POST['specific_time']) && !empty($_POST['specific_time'])) {
            $time_period_label .= ' ' . substr($_POST['specific_time'], 0, 8);
        }

        if (isset($_POST['game_key_number']) && !empty($_POST['game_key_number'])) {
            $time_period_label .= ' [' . $_POST['game_key_number'] . ']';
        }
    }
    
    $result = mysqli_query($common->db_connect, $query);

    $result = mysqli_fetch_array($result);
    
    if (isset($_POST['export_csv'])) {
        header('Content-Description: File Transfer');
        header('Content-Encoding: UTF-8');
        header('Content-Type: application/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $pdf->getFileNameForExport('AR', 'csv') . '"');
        header('Expires: 0');
    
        ob_start();

        echo "\xEF\xBB\xBF"; // UTF-8 BOM

        echo "Aggregate In-Game Performance Report" . "\r\n";

        echo "\r\n";

        echo "Level: " . $pureLevel . "\r\n";

        echo "BU: " . (($organization == "") ? "ALL" : $organization) . "\r\n";

        echo "Time Period: " . $time_period_label . "\r\n";

        echo "\r\n";
        
        echo ",,Foundation Knowledge,Deep Knowledge,Advanced Knowledge" . "\r\n";
        
        echo strtoupper($common->getSalesLabels('sales_group_1', 'label')) . "," . $common->getSalesLabels('sales1', 'label') . "," . $result['sales1_red'] . "," . $result['sales1_yellow'] . "," . $result['sales1_green'] . "\r\n";
        echo "," . $common->getSalesLabels('sales2', 'label') . "," . $result['sales2_red'] . "," . $result['sales2_yellow'] . "," . $result['sales2_green'] . "\r\n";
        echo "," . $common->getSalesLabels('sales3', 'label') . "," . $result['sales3_red'] . "," . $result['sales3_yellow'] . "," . $result['sales3_green'] . "\r\n";
    
        echo strtoupper($common->getSalesLabels('sales_group_2', 'label')) . "," . $common->getSalesLabels('sales4', 'label') . "," . $result['sales4_red'] . "," . $result['sales4_yellow'] . "," . $result['sales4_green'] . "\r\n";
        echo "," . $common->getSalesLabels('sales5', 'label') . "," . $result['sales5_red'] . "," . $result['sales5_yellow'] . "," . $result['sales5_green'] . "\r\n";
        echo "," . $common->getSalesLabels('sales6', 'label') . "," . $result['sales6_red'] . "," . $result['sales6_yellow'] . "," . $result['sales6_green'] . "\r\n";
    
        echo strtoupper($common->getSalesLabels('sales_group_3', 'label')) . "," . $common->getSalesLabels('sales7', 'label') . "," . $result['sales7_red'] . "," . $result['sales7_yellow'] . "," . $result['sales7_green'] . "\r\n";
        echo "," . $common->getSalesLabels('sales8', 'label') . "," . $result['sales8_red'] . "," . $result['sales8_yellow'] . "," . $result['sales8_green'] . "\r\n";
        echo "," . $common->getSalesLabels('sales9', 'label') . "," . $result['sales9_red'] . "," . $result['sales9_yellow'] . "," . $result['sales9_green'] . "\r\n";
    
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
        <?php include '../../' . $iSAIL_UI->assets_folder . '/css/pdfs/aggregate_report.css'; ?>
        <?php echo '</style>'; ?>

        <h1 class="text-center">Aggregate In-Game Performance Report</h1>
        <h4 class="text-left remove-margin-t push-10-b">
            <table>
                <tr>
                    <td class="text-right padding-10-r remove-border">Level:</td>
                    <td class="text-primary remove-border"><em><?php echo $pureLevel; ?></em></td>
                </tr>
                <tr>
                    <td class="text-right padding-10-r remove-border">BU:</td>
                    <td class="text-primary remove-border"><em><?php echo ($organization == "") ? "ALL" : $organization; ?></em></td>
                </tr>
                <tr>
                    <td class="text-right padding-10-r remove-border">Time Period:</td>
                    <td class="text-primary remove-border"><em><?php echo $time_period_label; ?></em></td>
                </tr>
            </table>
        </h4>
        <div class="text-left font-weight-bold">
            <table class="v-center">
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
        <table class="full-width">
            <tbody>
                <tr>
                    <td class="element-text text-center bg-modern text-white text-uppercase w-pt-15" rowspan="3"><?php echo $common->getSalesLabels('sales_group_1', 'label'); ?></td>
                    <td class="element-text bg-default text-right text-white w-pt-25"><?php echo $common->getSalesLabels('sales1', 'label'); ?></td>
                    <td class="w-pt-60">
                        <?php echo $common->generateProgressBar($result['sales1_red'], $result['sales1_yellow'], $result['sales1_green']) ?>
                    </td>
                </tr>
                <tr>
                    <td class="element-text bg-default text-right text-white"><?php echo $common->getSalesLabels('sales2', 'label'); ?></td>
                    <td class="w-pt-60">
                        <?php echo $common->generateProgressBar($result['sales2_red'], $result['sales2_yellow'], $result['sales2_green']) ?>
                    </td>
                </tr>
                <tr>
                    <td class="element-text bg-default text-right text-white"><?php echo $common->getSalesLabels('sales3', 'label'); ?></td>
                    <td class="w-pt-60">
                        <?php echo $common->generateProgressBar($result['sales3_red'], $result['sales3_yellow'], $result['sales3_green']) ?>
                    </td>
                </tr>
                
                <tr>
                    <td class="element-text text-center bg-modern text-white text-uppercase padding-2-5 v-center" rowspan="3"><?php echo $common->getSalesLabels('sales_group_2', 'label'); ?></td>
                    <td class="element-text bg-default text-right text-white"><?php echo $common->getSalesLabels('sales4', 'label'); ?></td>
                    <td class="w-pt-60">
                        <?php echo $common->generateProgressBar($result['sales4_red'], $result['sales4_yellow'], $result['sales4_green']) ?>
                    </td>
                </tr>
                <tr>
                    <td class="element-text bg-default text-right text-white"><?php echo $common->getSalesLabels('sales5', 'label'); ?></td>
                    <td class="w-pt-60">
                        <?php echo $common->generateProgressBar($result['sales5_red'], $result['sales5_yellow'], $result['sales5_green']) ?>
                    </td>
                </tr>
                <tr>
                    <td class="element-text bg-default text-right text-white"><?php echo $common->getSalesLabels('sales6', 'label'); ?></td>
                    <td class="w-pt-60">
                        <?php echo $common->generateProgressBar($result['sales6_red'], $result['sales6_yellow'], $result['sales6_green']) ?>
                    </td>
                </tr>

                <tr>
                    <td class="element-text text-center bg-modern text-white text-uppercase padding-2-5 v-center" rowspan="3"><?php echo $common->getSalesLabels('sales_group_3', 'label'); ?></td>
                    <td class="element-text bg-default text-right text-white"><?php echo $common->getSalesLabels('sales7', 'label'); ?></td>
                    <td class="w-pt-60">
                        <?php echo $common->generateProgressBar($result['sales7_red'], $result['sales7_yellow'], $result['sales7_green']) ?>
                    </td>
                </tr>
                <tr>
                    <td class="element-text bg-default text-right text-white"><?php echo $common->getSalesLabels('sales8', 'label'); ?></td>
                    <td class="w-pt-60">
                        <?php echo $common->generateProgressBar($result['sales8_red'], $result['sales8_yellow'], $result['sales8_green']) ?>
                    </td>
                </tr>
                <tr>
                    <td class="element-text bg-default text-right text-white"><?php echo $common->getSalesLabels('sales9', 'label'); ?></td>
                    <td class="w-pt-60">
                        <?php echo $common->generateProgressBar($result['sales9_red'], $result['sales9_yellow'], $result['sales9_green']) ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php
        $content = ob_get_contents();
        ob_end_clean();
        $pdf->generatePDF($content, 'L', 'AR');
        
        exit;
    }
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
                    <i class="fa fa-tasks text-primary"></i>
                </span>
                Aggregate In-Game Performance Report
            </h1>
        </div>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content">
    
    <div class="row remove-margin-l remove-margin-r">
        <!-- Info Alert -->
        <div class="alert alert-info alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <p><a class="alert-link" href="javascript:void(0)"><i class="fa fa-info-circle"></i> Note</a>: Click on colored bar to see more detailed information.</p>
        </div>
        <!-- END Info Alert -->
    </div>

    <div class="block">
        <div class="table-responsive">
            <table class="table text-center remove-margin-b table-vcenter">
                <thead>
                    <tr>
                        <th colspan="2">
                            <h3 class="block-title pull-left">
                                <table>
                                    <tr>
                                        <td class="text-right padding-10-r w-px-150">Level:</td>
                                        <td class="text-normal w-px-250"><small><?php echo $pureLevel; ?></small></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right padding-10-r">BU:</td>
                                        <td class="text-normal"><small><?php echo ($organization == "") ? "ALL" : $organization; ?></small></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right padding-10-r">Time Period:</td>
                                        <td class="text-normal"><small><?php echo $time_period_label; ?></small></td>
                                    </tr>
                                </table>
                            </h3>
                        </th>
                        <th>
                            <div class="text-right">
                                <button class="btn btn-default btn-back" type="button">Back</button>
                                <button class="btn btn-primary" data-toggle="modal" data-target="#modal-popout" type="button"><i class="fa fa-info-circle"></i> Legend</button>
                                <button class="btn btn-primary" id="btn_export_csv"><i class="fa fa-file-excel-o"></i> Export CSV</button>
                                <button class="btn btn-primary" id="btn_export_pdf"><i class="fa fa-file-pdf-o"></i> Export in PDF</button>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="3">
                            <div class="text-left push-10-t font-w700">
                                <span class="text-primary fa-pull-left push-10-r"><i class="fa fa-info-circle"></i> Legend : </span>
                                <span class="text-danger cursor-pointer" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $common->getLegendColor('foundation'); ?> <?php echo $common->getLegendScoreRange('foundation'); ?>"><?php echo $common->getLegendLabel('foundation'); ?>, </span>
                                <span class="text-warning cursor-pointer" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $common->getLegendColor('deep'); ?> <?php echo $common->getLegendScoreRange('deep'); ?>"><?php echo $common->getLegendLabel('deep'); ?>, </span>
                                <span class="text-success cursor-pointer" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $common->getLegendColor('advanced'); ?> <?php echo $common->getLegendScoreRange('advanced'); ?>"><?php echo $common->getLegendLabel('advanced'); ?></span>
                            </div>
                            <div class="text-right">
                                <button class="btn btn-default btn-back push-m45-t" type="button">Back</button>
                            </div>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
                    <tr>
                        <td class="font-w600 text-center bg-modern text-white text-uppercase padding-2-5 w-px-150" rowspan="3"><?php echo $common->getSalesLabels('sales_group_1', 'label'); ?></td>
                        <td class="bg-default text-right text-white w-px-250"><?php echo $common->getSalesLabels('sales1', 'label'); ?></td>
                        <td>
                            <div class="progress remove-margin-b">
                                <a href="element_score.php?param=sales1&rubric=foundation">
                                    <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales1_red'] + $result['sales1_yellow'] + $result['sales1_green'] == 0) ? 0 : ($result['sales1_red'] / ($result['sales1_red'] + $result['sales1_yellow'] + $result['sales1_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales1_red']; ?></div>
                                </a>
                                <a href="element_score.php?param=sales1&rubric=deep">
                                    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales1_red'] + $result['sales1_yellow'] + $result['sales1_green'] == 0) ? 0 : ($result['sales1_yellow'] / ($result['sales1_red'] + $result['sales1_yellow'] + $result['sales1_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales1_yellow']; ?></div>
                                </a>
                                <a href="element_score.php?param=sales1&rubric=advanced">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales1_red'] + $result['sales1_yellow'] + $result['sales1_green'] == 0) ? 0 : ($result['sales1_green'] / ($result['sales1_red'] + $result['sales1_yellow'] + $result['sales1_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales1_green']; ?></div>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="bg-default text-right text-white"><?php echo $common->getSalesLabels('sales2', 'label'); ?></td>
                        <td>
                            <div class="progress remove-margin-b">
                                <a href="element_score.php?param=sales2&rubric=foundation">
                                    <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales2_red'] + $result['sales2_yellow'] + $result['sales2_green'] == 0) ? 0 : ($result['sales2_red'] / ($result['sales2_red'] + $result['sales2_yellow'] + $result['sales2_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales2_red']; ?></div>
                                </a>
                                <a href="element_score.php?param=sales2&rubric=deep">
                                    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales2_red'] + $result['sales2_yellow'] + $result['sales2_green'] == 0) ? 0 : ($result['sales2_yellow'] / ($result['sales2_red'] + $result['sales2_yellow'] + $result['sales2_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales2_yellow']; ?></div>
                                </a>
                                <a href="element_score.php?param=sales2&rubric=advanced">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales2_red'] + $result['sales2_yellow'] + $result['sales2_green'] == 0) ? 0 : ($result['sales2_green'] / ($result['sales2_red'] + $result['sales2_yellow'] + $result['sales2_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales2_green']; ?></div>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="bg-default text-right text-white"><?php echo $common->getSalesLabels('sales3', 'label'); ?></td>
                        <td>
                            <div class="progress remove-margin-b">
                                <a href="element_score.php?param=sales3&rubric=foundation">
                                    <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales3_red'] + $result['sales3_yellow'] + $result['sales3_green'] == 0) ? 0 : ($result['sales3_red'] / ($result['sales3_red'] + $result['sales3_yellow'] + $result['sales3_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales3_red']; ?></div>
                                </a>
                                <a href="element_score.php?param=sales3&rubric=deep">
                                    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales3_red'] + $result['sales3_yellow'] + $result['sales3_green'] == 0) ? 0 : ($result['sales3_yellow'] / ($result['sales3_red'] + $result['sales3_yellow'] + $result['sales3_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales3_yellow']; ?></div>
                                </a>
                                <a href="element_score.php?param=sales3&rubric=advanced">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales3_red'] + $result['sales3_yellow'] + $result['sales3_green'] == 0) ? 0 : ($result['sales3_green'] / ($result['sales3_red'] + $result['sales3_yellow'] + $result['sales3_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales3_green']; ?></div>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="font-w600 text-center bg-modern text-white text-uppercase padding-2-5" rowspan="3"><?php echo $common->getSalesLabels('sales_group_2', 'label'); ?></td>
                        <td class="bg-default text-right text-white"><?php echo $common->getSalesLabels('sales4', 'label'); ?></td>
                        <td>
                            <div class="progress remove-margin-b">
                                <a href="element_score.php?param=sales4&rubric=foundation">
                                    <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales4_red'] + $result['sales4_yellow'] + $result['sales4_green'] == 0) ? 0 : ($result['sales4_red'] / ($result['sales4_red'] + $result['sales4_yellow'] + $result['sales4_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales4_red']; ?></div>
                                </a>
                                <a href="element_score.php?param=sales4&rubric=deep">
                                    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales4_red'] + $result['sales4_yellow'] + $result['sales4_green'] == 0) ? 0 : ($result['sales4_yellow'] / ($result['sales4_red'] + $result['sales4_yellow'] + $result['sales4_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales4_yellow']; ?></div>
                                </a>
                                <a href="element_score.php?param=sales4&rubric=advanced">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales4_red'] + $result['sales4_yellow'] + $result['sales4_green'] == 0) ? 0 : ($result['sales4_green'] / ($result['sales4_red'] + $result['sales4_yellow'] + $result['sales4_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales4_green']; ?></div>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="bg-default text-right text-white"><?php echo $common->getSalesLabels('sales5', 'label'); ?></td>
                        <td>
                            <div class="progress remove-margin-b">
                                <a href="element_score.php?param=sales5&rubric=foundation">
                                    <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales5_red'] + $result['sales5_yellow'] + $result['sales5_green'] == 0) ? 0 : ($result['sales5_red'] / ($result['sales5_red'] + $result['sales5_yellow'] + $result['sales5_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales5_red']; ?></div>
                                </a>
                                <a href="element_score.php?param=sales5&rubric=deep">
                                    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales5_red'] + $result['sales5_yellow'] + $result['sales5_green'] == 0) ? 0 : ($result['sales5_yellow'] / ($result['sales5_red'] + $result['sales5_yellow'] + $result['sales5_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales5_yellow']; ?></div>
                                </a>
                                <a href="element_score.php?param=sales5&rubric=advanced">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales5_red'] + $result['sales5_yellow'] + $result['sales5_green'] == 0) ? 0 : ($result['sales5_green'] / ($result['sales5_red'] + $result['sales5_yellow'] + $result['sales5_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales5_green']; ?></div>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="bg-default text-right text-white"><?php echo $common->getSalesLabels('sales6', 'label'); ?></td>
                        <td>
                            <div class="progress remove-margin-b">
                                <a href="element_score.php?param=sales6&rubric=foundation">
                                    <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales6_red'] + $result['sales6_yellow'] + $result['sales6_green'] == 0) ? 0 : ($result['sales6_red'] / ($result['sales6_red'] + $result['sales6_yellow'] + $result['sales6_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales6_red']; ?></div>
                                </a>
                                <a href="element_score.php?param=sales6&rubric=deep">
                                    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales6_red'] + $result['sales6_yellow'] + $result['sales6_green'] == 0) ? 0 : ($result['sales6_yellow'] / ($result['sales6_red'] + $result['sales6_yellow'] + $result['sales6_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales6_yellow']; ?></div>
                                </a>
                                <a href="element_score.php?param=sales6&rubric=advanced">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales6_red'] + $result['sales6_yellow'] + $result['sales6_green'] == 0) ? 0 : ($result['sales6_green'] / ($result['sales6_red'] + $result['sales6_yellow'] + $result['sales6_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales6_green']; ?></div>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="font-w600 text-center bg-modern text-white text-uppercase padding-2-5" rowspan="3"><?php echo $common->getSalesLabels('sales_group_3', 'label'); ?></td>
                        <td class="bg-default text-right text-white"><?php echo $common->getSalesLabels('sales7', 'label'); ?></td>
                        <td>
                            <div class="progress remove-margin-b">
                                <a href="element_score.php?param=sales7&rubric=foundation">
                                    <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales7_red'] + $result['sales7_yellow'] + $result['sales7_green'] == 0) ? 0 : ($result['sales7_red'] / ($result['sales7_red'] + $result['sales7_yellow'] + $result['sales7_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales7_red']; ?></div>
                                </a>
                                <a href="element_score.php?param=sales7&rubric=deep">
                                    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales7_red'] + $result['sales7_yellow'] + $result['sales7_green'] == 0) ? 0 : ($result['sales7_yellow'] / ($result['sales7_red'] + $result['sales7_yellow'] + $result['sales7_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales7_yellow']; ?></div>
                                </a>
                                <a href="element_score.php?param=sales7&rubric=advanced">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales7_red'] + $result['sales7_yellow'] + $result['sales7_green'] == 0) ? 0 : ($result['sales7_green'] / ($result['sales7_red'] + $result['sales7_yellow'] + $result['sales7_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales7_green']; ?></div>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="bg-default text-right text-white"><?php echo $common->getSalesLabels('sales8', 'label'); ?></td>
                        <td>
                            <div class="progress remove-margin-b">
                                <a href="element_score.php?param=sales8&rubric=foundation">
                                    <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales8_red'] + $result['sales8_yellow'] + $result['sales8_green'] == 0) ? 0 : ($result['sales8_red'] / ($result['sales8_red'] + $result['sales8_yellow'] + $result['sales8_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales8_red']; ?></div>
                                </a>
                                <a href="element_score.php?param=sales8&rubric=deep">
                                    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales8_red'] + $result['sales8_yellow'] + $result['sales8_green'] == 0) ? 0 : ($result['sales8_yellow'] / ($result['sales8_red'] + $result['sales8_yellow'] + $result['sales8_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales8_yellow']; ?></div>
                                </a>
                                <a href="element_score.php?param=sales8&rubric=advanced">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales8_red'] + $result['sales8_yellow'] + $result['sales8_green'] == 0) ? 0 : ($result['sales8_green'] / ($result['sales8_red'] + $result['sales8_yellow'] + $result['sales8_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales8_green']; ?></div>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="bg-default text-right text-white"><?php echo $common->getSalesLabels('sales9', 'label'); ?></td>
                        <td>
                            <div class="progress remove-margin-b">
                                <a href="element_score.php?param=sales9&rubric=foundation">
                                    <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales9_red'] + $result['sales9_yellow'] + $result['sales9_green'] == 0) ? 0 : ($result['sales9_red'] / ($result['sales9_red'] + $result['sales9_yellow'] + $result['sales9_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales9_red']; ?></div>
                                </a>
                                <a href="element_score.php?param=sales9&rubric=deep">
                                    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales9_red'] + $result['sales9_yellow'] + $result['sales9_green'] == 0) ? 0 : ($result['sales9_yellow'] / ($result['sales9_red'] + $result['sales9_yellow'] + $result['sales9_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales9_yellow']; ?></div>
                                </a>
                                <a href="element_score.php?param=sales9&rubric=advanced">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result['sales9_red'] + $result['sales9_yellow'] + $result['sales9_green'] == 0) ? 0 : ($result['sales9_green'] / ($result['sales9_red'] + $result['sales9_yellow'] + $result['sales9_green'])) * 100; ?>%"  data-toggle="tooltip" data-placement="top" title="Click on colored bar to see more detailed information."><?php echo $result['sales9_green']; ?></div>
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- END Classic Design -->
</div>
<!-- END Page Content -->

<?php require '../../inc/views/modal_legend.php'; ?>

<?php require '../../inc/views/modal_confirm_export.php'; ?>

<?php require '../../inc/views/base_footer.php'; ?>
<?php require '../../inc/views/template_footer_start.php'; ?>

<!-- Global JS -->
<?php require '../../inc/views/global.js.php'; ?>

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
