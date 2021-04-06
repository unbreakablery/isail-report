<?php 
    require '../../inc/config.php';

    $common->authorizePage("aggregate_report");
    
    if (!isset($_GET['id']) || $_GET['id'] == "") {
        header('Location: criteria.php');
        exit;
    }

    if (!isset($_SESSION['param']) || $_SESSION['param'] == "") {
        header('Location: criteria.php');
        exit;
    }

    if (!isset($_SESSION['rubric']) || $_SESSION['rubric'] == "") {
        header('Location: criteria.php');
        exit;
    }

    $iSAIL_UI->title = $common->getPrefixProjectName() . ' ' . 'Learner Performance';

    if (isset($_SESSION['time_period']) && !empty($_SESSION['time_period']) && $_SESSION['time_period'] == "average-score") {
        $query = $common->generateISAILQuery($_SESSION);
    } else {
        $query = $common->generateQueryForGetLevelByID($_GET['id']);
    }

    if (isset($_SESSION['time_period'])) {
        $time_period = $_SESSION['time_period'];
    } else {
        header('Location: criteria.php');
        exit;
    }
    
    $time_period_label = $common->getTimePeriodLabel($time_period);

    if (isset($_SESSION['specific_date']) && !empty($_SESSION['specific_date'])) {
        $time_period_label .= ' ' . $_SESSION['specific_date'];
    }

    if (isset($_SESSION['specific_time']) && !empty($_SESSION['specific_time'])) {
        $time_period_label .= ' ' . substr($_SESSION['specific_time'], 0, 8);
    }

    if (isset($_SESSION['game_key_number']) && !empty($_SESSION['game_key_number'])) {
        $time_period_label .= ' [' . $_SESSION['game_key_number'] . ']';
    }

    $result = mysqli_query($common->db_connect, $query);

    $player = null;
    
    while ($row = mysqli_fetch_array($result)) {
        $player = $row;
    }

    if ($player == null) {
        header('Location: criteria.php');
        exit;
    }

    $level = $player['level'];
    $player_name = $player['first_name'] . ' ' . $player['last_name'];

    $sales1 = $common->getScoreByParam($level, 'sales1', $player['sales1']);
    $sales2 = $common->getScoreByParam($level, 'sales2', $player['sales2']);
    $sales3 = $common->getScoreByParam($level, 'sales3', $player['sales3']);
    $sales4 = $common->getScoreByParam($level, 'sales4', $player['sales4']);
    $sales5 = $common->getScoreByParam($level, 'sales5', $player['sales5']);
    $sales6 = $common->getScoreByParam($level, 'sales6', $player['sales6']);
    $sales7 = $common->getScoreByParam($level, 'sales7', $player['sales7']);
    $sales8 = $common->getScoreByParam($level, 'sales8', $player['sales8']);
    $sales9 = $common->getScoreByParam($level, 'sales9', $player['sales9']);

    $sales_model_mastery = 0;
    $sales_model_mastery += $sales2 + $sales1 + $sales3;
    $sales_model_mastery += $sales4 + $sales5 + $sales6;
    $sales_model_mastery += $sales7 + $sales9 + $sales8;

    $sales_model_mastery = round($sales_model_mastery / 9);

    if (isset($_POST['export_csv'])) {
        header('Content-Description: File Transfer');
        header('Content-Encoding: UTF-8');
        header('Content-Type: application/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $pdf->getFileNameForExport('GLP', 'csv') . '"');
        header('Expires: 0');

        ob_start();

        echo "\xEF\xBB\xBF"; // UTF-8 BOM

        echo "In-Game Learner Performance" . "\r\n";

        echo "\r\n";

        echo "Player: " . $player_name . "\r\n";

        echo "Level: " . $level . "\r\n";

        echo "Time Period: " . $time_period_label . "\r\n";

        echo "\r\n";
        
        echo "Sales Model Mastery: " . $sales_model_mastery . "%" . "\r\n";

        echo "\r\n";

        echo strtoupper($common->getSalesLabels('sales_group_1', 'label')) . "," . $common->getSalesLabels('sales1', 'label') . "," . $sales1 . "%". "\r\n";
        echo "," . $common->getSalesLabels('sales2', 'label') . "," .$sales2 . "%" . "\r\n";
        echo "," . $common->getSalesLabels('sales3', 'label') . "," .$sales3 . "%" . "\r\n";
        
        echo strtoupper($common->getSalesLabels('sales_group_2', 'label')) . "," . $common->getSalesLabels('sales4', 'label') . "," . $sales4 . "%". "\r\n";
        echo "," . $common->getSalesLabels('sales5', 'label') . "," .$sales5 . "%" . "\r\n";
        echo "," . $common->getSalesLabels('sales6', 'label') . "," .$sales6 . "%" . "\r\n";
        
        echo strtoupper($common->getSalesLabels('sales_group_3', 'label')) . "," . $common->getSalesLabels('sales7', 'label') . "," . $sales7 . "%". "\r\n";
        echo "," . $common->getSalesLabels('sales8', 'label') . "," .$sales8 . "%" . "\r\n";
        echo "," . $common->getSalesLabels('sales9', 'label') . "," .$sales9 . "%" . "\r\n";
        
        $csv = ob_get_contents();
        ob_end_clean();
        header("Content-Length: " . strlen($csv));
        echo $csv;
    
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
                In-Game Learner Performance
            </h1>
        </div>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content content-narrow">
    <div class="row">
        <div class="col-md-12">
            <!-- Block Tabs Alternative Style -->
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
                            <tr>
                                <td class="text-right padding-10-r">Player:</td>
                                <td><small><?php echo $player_name; ?></small></td>
                            </tr>
                        </table>
                    </h3>
                    <div class="text-right">
                        <button class="btn btn-default btn-back" type="button">Back</button>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modal-popout" type="button"><i class="fa fa-info-circle"></i> Legend</button>
                        <button class="btn btn-primary" id="btn_export_csv" type="button"><i class="fa fa-file-excel-o"></i> Export CSV</button>
                        <button class="btn btn-primary" id="btn_export_pdf" type="button"><i class="fa fa-file-pdf-o"></i> Export in PDF</button>
                    </div>
                </div>

                <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs">
                    <li class="active">
                        <a href="#performance-bar-tab">Bar</a>
                    </li>
                    <li>
                        <a href="#performance-donut-tab">Donut</a>
                    </li>
                </ul>

                <div class="block-content tab-content">
                    <div class="tab-pane active" id="performance-bar-tab" data-id="performance-bar-tab">
                        <div class="col-lg-12">
                            <!-- Bars -->
                            <div class="block chart-content">
                                <div class="block-content">
                                    <div class="row items-push-2x text-center">
                                        <div class="col-xs-6 col-sm-4">
                                            
                                        </div>
                                        <div class="col-xs-6 col-sm-4">
                                            <div class="push-10-t"><?php echo $sales_model_mastery; ?>%</div>
                                            <span class="js-slc-bar0"><?php echo $sales_model_mastery; ?></span>
                                            <div class="push-10-t">Sales Model Mastery</div>
                                        </div>
                                        <div class="col-xs-6 col-sm-4">
                                            
                                        </div>
                                    </div>

                                    <div class="row items-push-2x text-center isail-sparkline-bar">
                                        <div class="col-sm-2 bg-modern text-center text-white text-uppercase font-w700 isail-sparkline-bar-title push-5">
                                            <span><?php echo $common->getSalesLabels('sales_group_1', 'label'); ?></span>
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="col-sm-4 push-5">
                                                <div class="push-10-t"><?php echo $sales1; ?>%</div>
                                                <span class="js-slc-bar1"><?php echo $sales1; ?></span>
                                                <div class="push-10-t"><?php echo $common->getSalesLabels('sales1', 'label'); ?></div>
                                            </div>
                                            <div class="col-sm-4 push-5">
                                                <div class="push-10-t"><?php echo $sales2; ?>%</div>
                                                <span class="js-slc-bar2"><?php echo $sales2; ?></span>
                                                <div class="push-10-t"><?php echo $common->getSalesLabels('sales2', 'label'); ?></div>
                                            </div>
                                            <div class="col-sm-4 push-5">
                                                <div class="push-10-t"><?php echo $sales3; ?>%</div>
                                                <span class="js-slc-bar3"><?php echo $sales3; ?></span>
                                                <div class="push-10-t"><?php echo $common->getSalesLabels('sales3', 'label'); ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row items-push-2x text-center isail-sparkline-bar">
                                        <div class="col-sm-2 bg-modern text-center text-white text-uppercase font-w700 isail-sparkline-bar-title push-5">
                                            <span><?php echo $common->getSalesLabels('sales_group_2', 'label'); ?></span>
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="col-sm-4 push-5">
                                                <div class="push-10-t"><?php echo $sales4; ?>%</div>
                                                <span class="js-slc-bar4"><?php echo $sales4; ?></span>
                                                <div class="push-10-t"><?php echo $common->getSalesLabels('sales4', 'label'); ?></div>
                                            </div>
                                            <div class="col-sm-4 push-5">
                                                <div class="push-10-t"><?php echo $sales5; ?>%</div>
                                                <span class="js-slc-bar5"><?php echo $sales5; ?></span>
                                                <div class="push-10-t"><?php echo $common->getSalesLabels('sales5', 'label'); ?></div>
                                            </div>
                                            <div class="col-sm-4 push-5">
                                                <div class="push-10-t"><?php echo $sales6; ?>%</div>
                                                <span class="js-slc-bar6"><?php echo $sales6; ?></span>
                                                <div class="push-10-t"><?php echo $common->getSalesLabels('sales6', 'label'); ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row items-push-2x text-center isail-sparkline-bar">
                                        <div class="col-sm-2 bg-modern text-center text-white text-uppercase font-w700 isail-sparkline-bar-title push-5">
                                            <span><?php echo $common->getSalesLabels('sales_group_3', 'label'); ?></span>
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="col-sm-4 push-5">
                                                <div class="push-10-t"><?php echo $sales7; ?>%</div>
                                                <span class="js-slc-bar7"><?php echo $sales7; ?></span>
                                                <div class="push-10-t"><?php echo $common->getSalesLabels('sales7', 'label'); ?></div>
                                            </div>
                                            <div class="col-sm-4 push-5">
                                                <div class="push-10-t"><?php echo $sales8; ?>%</div>
                                                <span class="js-slc-bar8"><?php echo $sales8; ?></span>
                                                <div class="push-10-t"><?php echo $common->getSalesLabels('sales8', 'label'); ?></div>
                                            </div>
                                            <div class="col-sm-4 push-5">
                                                <div class="push-10-t"><?php echo $sales9; ?>%</div>
                                                <span class="js-slc-bar9"><?php echo $sales9; ?></span>
                                                <div class="push-10-t"><?php echo $common->getSalesLabels('sales9', 'label'); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END Bars -->
                        </div>
                    </div>
                    <div class="tab-pane" id="performance-donut-tab" data-id="performance-donut-tab">
                        <div class="col-lg-12">
                            <!-- Pie Chart Simple -->
                            <div class="block chart-content">
                                <div class="block-content">
                                    <div class="row items-push-2x text-center">
                                        <div class="col-xs-6 col-sm-4">

                                        </div>
                                        <div class="col-xs-6 col-sm-4">
                                            <!-- Pie Chart Container -->
                                            <div class="js-pie-chart pie-chart" data-percent="<?php echo $sales_model_mastery; ?>" data-line-width="25" data-size="100" data-bar-color="<?php echo $common->getDonutBarColor($sales_model_mastery); ?>" data-track-color="#eeeeee">
                                                <span><?php echo $sales_model_mastery; ?>%</span>
                                            </div>
                                            <div class="push-10-t">Sales Model Mastery</div>
                                        </div>
                                        <div class="col-xs-6 col-sm-4">

                                        </div>
                                    </div>
                                    <table class="table text-center table-vcenter remove-margin-b">                
                                        <colgroup>
                                            <col width="10%">
                                            <col width="30%">
                                            <col width="30%">
                                            <col width="30%">
                                        </colgroup>
                                        <tbody>
                                            <tr>
                                                <th class="bg-modern text-center text-white font-w700"><?php echo $common->getSalesLabels('sales_group_1', 'label'); ?></th>
                                                <td>
                                                    <!-- Pie Chart Container -->
                                                    <div class="js-pie-chart pie-chart" data-percent="<?php echo $sales1; ?>" data-line-width="10" data-size="100" data-bar-color="<?php echo $common->getDonutBarColor($sales1); ?>" data-track-color="#eeeeee">
                                                        <span><?php echo $sales1; ?>%</span>
                                                    </div>
                                                    <div class="push-10-t"><?php echo $common->getSalesLabels('sales1', 'label'); ?></div>
                                                </td>
                                                <td>
                                                    <!-- Pie Chart Container -->
                                                    <div class="js-pie-chart pie-chart" data-percent="<?php echo $sales2; ?>" data-line-width="10" data-size="100" data-bar-color="<?php echo $common->getDonutBarColor($sales2); ?>" data-track-color="#eeeeee">
                                                        <span><?php echo $sales2; ?>%</span>
                                                    </div>
                                                    <div class="push-10-t"><?php echo $common->getSalesLabels('sales2', 'label'); ?></div>
                                                </td>
                                                <td>
                                                    <!-- Pie Chart Container -->
                                                    <div class="js-pie-chart pie-chart" data-percent="<?php echo $sales3; ?>" data-line-width="10" data-size="100" data-bar-color="<?php echo $common->getDonutBarColor($sales3); ?>" data-track-color="#eeeeee">
                                                        <span><?php echo $sales3; ?>%</span>
                                                    </div>
                                                    <div class="push-10-t"><?php echo $common->getSalesLabels('sales3', 'label'); ?></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="bg-modern text-center text-white font-w700"><?php echo $common->getSalesLabels('sales_group_2', 'label'); ?></th>
                                                <td>
                                                    <!-- Pie Chart Container -->
                                                    <div class="js-pie-chart pie-chart" data-percent="<?php echo $sales4; ?>" data-line-width="10" data-size="100" data-bar-color="<?php echo $common->getDonutBarColor($sales4); ?>" data-track-color="#eeeeee">
                                                        <span><?php echo $sales4; ?>%</span>
                                                    </div>
                                                    <div class="push-10-t"><?php echo $common->getSalesLabels('sales4', 'label'); ?></div>
                                                </td>
                                                <td>
                                                    <!-- Pie Chart Container -->
                                                    <div class="js-pie-chart pie-chart" data-percent="<?php echo $sales5; ?>" data-line-width="10" data-size="100" data-bar-color="<?php echo $common->getDonutBarColor($sales5); ?>" data-track-color="#eeeeee">
                                                        <span><?php echo $sales5; ?>%</span>
                                                    </div>
                                                    <div class="push-10-t"><?php echo $common->getSalesLabels('sales5', 'label'); ?></div>
                                                </td>
                                                <td>
                                                    <!-- Pie Chart Container -->
                                                    <div class="js-pie-chart pie-chart" data-percent="<?php echo $sales6; ?>" data-line-width="10" data-size="100" data-bar-color="<?php echo $common->getDonutBarColor($sales6); ?>" data-track-color="#eeeeee">
                                                        <span><?php echo $sales6; ?>%</span>
                                                    </div>
                                                    <div class="push-10-t"><?php echo $common->getSalesLabels('sales6', 'label'); ?></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="bg-modern text-center text-white font-w700"><?php echo $common->getSalesLabels('sales_group_3', 'label'); ?></th>
                                                <td>
                                                    <!-- Pie Chart Container -->
                                                    <div class="js-pie-chart pie-chart" data-percent="<?php echo $sales7; ?>" data-line-width="10" data-size="100" data-bar-color="<?php echo $common->getDonutBarColor($sales7); ?>" data-track-color="#eeeeee">
                                                        <span><?php echo $sales7; ?>%</span>
                                                    </div>
                                                    <div class="push-10-t"><?php echo $common->getSalesLabels('sales7', 'label'); ?></div>
                                                </td>
                                                <td>
                                                    <!-- Pie Chart Container -->
                                                    <div class="js-pie-chart pie-chart" data-percent="<?php echo $sales8; ?>" data-line-width="10" data-size="100" data-bar-color="<?php echo $common->getDonutBarColor($sales8); ?>" data-track-color="#eeeeee">
                                                        <span><?php echo $sales8; ?>%</span>
                                                    </div>
                                                    <div class="push-10-t"><?php echo $common->getSalesLabels('sales8', 'label'); ?></div>
                                                </td>
                                                <td>
                                                    <!-- Pie Chart Container -->
                                                    <div class="js-pie-chart pie-chart" data-percent="<?php echo $sales9; ?>" data-line-width="10" data-size="100" data-bar-color="<?php echo $common->getDonutBarColor($sales9); ?>" data-track-color="#eeeeee">
                                                        <span><?php echo $sales9; ?>%</span>
                                                    </div>
                                                    <div class="push-10-t"><?php echo $common->getSalesLabels('sales9', 'label'); ?></div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- END Pie Chart Simple -->
                        </div>
                    </div>
                </div>

                <div class="block-header">
                    <div class="text-left push-10-t font-w700">
                        <span class="text-primary fa-pull-left push-10-r"><i class="fa fa-info-circle"></i> Legend : </span>
                        <span class="text-danger cursor-pointer" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $common->getLegendColor('foundation'); ?> <?php echo $common->getLegendScoreRange('foundation'); ?>"><?php echo $common->getLegendLabel('foundation'); ?>, </span>
                        <span class="text-warning cursor-pointer" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $common->getLegendColor('deep'); ?> <?php echo $common->getLegendScoreRange('deep'); ?>"><?php echo $common->getLegendLabel('deep'); ?>, </span>
                        <span class="text-success cursor-pointer" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $common->getLegendColor('advanced'); ?> <?php echo $common->getLegendScoreRange('advanced'); ?>"><?php echo $common->getLegendLabel('advanced'); ?></span>
                    </div>
                    <div class="text-right push-m30-t">
                        <button class="btn btn-default btn-back" type="button">Back</button>
                    </div>
                </div>
            </div>
            <!-- END Block Tabs Alternative Style -->
        </div>
    </div>
    <!-- END Block Tabs -->
</div>
<!-- END Page Content -->

<?php require '../../inc/views/modal_legend.php'; ?>
<?php require '../../inc/views/modal_confirm_export.php'; ?>

<?php require '../../inc/views/base_footer.php'; ?>
<?php require '../../inc/views/template_footer_start.php'; ?>

<!-- Global JS -->
<?php require '../../inc/views/global.js.php'; ?>

<!-- Page JS Plugins -->
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/sparkline/jquery.sparkline.min.js"></script>
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/easy-pie-chart/jquery.easypiechart.min.js"></script>
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/chartjs/Chart.min.js"></script>
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/chartjsv2/Chart.min.js"></script>

<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/jspdf/jspdf.min.js"></script>
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/html2canvas/html2canvas.js"></script>

<!-- Page JS Code -->
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/pages/learner_performance_charts.js"></script>
<script>
    $(document).ready(function() {

        var $level              = '<?php echo $level; ?>';
        var $player_name        = '<?php echo $player_name; ?>';
        var $time_period_label  = '<?php echo $time_period_label; ?>';

        // Init page helpers (Easy Pie Chart plugin)
        App.initHelpers('easy-pie-chart');

        $("button.btn-back").click(function() {
            window.location.href = "element_score.php?param=<?php echo $_SESSION['param']; ?>&rubric=<?php echo $_SESSION['rubric']; ?>";
        });

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

            html2canvas($("div.tab-content .tab-pane.active div.chart-content"), {
                onrendered: function(canvas) {
                    
                    var orientation = '';
                    var chart_type  = '';
                    var page_title  = 'In-Game Learner Performance';

                    if ($("div.tab-content .tab-pane.active").data("id") == "performance-bar-tab") {
                        orientation = 'p';
                        chart_type  = 'Bar';
                    } else {
                        orientation = 'l';
                        chart_type  = 'Donut';
                    }

                    var pdf = new jsPDF(orientation, 'pt', 'a4');
                    
                    var pageWidth       = pdf.internal.pageSize.getWidth();
                    var pageHeight      = pdf.internal.pageSize.getHeight();
                    var canvasWidth     = canvas.width;
                    var canvasHeight    = canvas.height;
                    
                    var chartImage      = canvas.toDataURL("image/png", canvasWidth, canvasHeight);

                    var fileName        = getFileNameForExport('GLP', 'pdf');

                    var imgHeight       =  pageHeight - 200;
                    var imgWidth        = imgHeight * (canvasWidth / canvasHeight);

                    if (imgWidth >= pageWidth) {
                        imgWidth = pageWidth - 20;
                    }

                    var imgLeft         = (pageWidth - imgWidth) / 2;
                    var imgTop          = 180;

                    pdf.setFont("helvetica", "bold");
                    pdf.setFontSize(24);
                    pdf.text(page_title, pageWidth/2, 50, 'center');

                    pdf.setFontSize(12);
                    pdf.setFontStyle("bolditalic");

                    pdf.setTextColor("#46c37b");
                    pdf.text('Player Name: ' + $player_name + ' , Level: ' + $level, 50, 80);

                    pdf.setTextColor("#5c90d2");
                    pdf.text('Time Period:  ' + $time_period_label, 50, 100);

                    pdf.setTextColor("#d26a5c");
                    pdf.text('Chart Type:    ' + chart_type, 50, 120);

                    pdf.setTextColor("#000000");
                    pdf.text('*Legend: ', 50, 140);

                    pdf.setTextColor("#d26a5c");
                    pdf.text('<?php echo $common->getLegend("foundation"); ?>', 130, 140);
                    pdf.setTextColor("#f3b760");
                    pdf.text('<?php echo $common->getLegend("deep"); ?>', 130, 155);
                    pdf.setTextColor("#46c37b");
                    pdf.text('<?php echo $common->getLegend("advanced"); ?>', 130, 170);

                    pdf.addImage(chartImage, 'JPEG', imgLeft, imgTop, imgWidth, imgHeight);
                    
                    pdf.save(fileName);
                }
            });

        });

    });
</script>

<?php require '../../inc/views/template_footer_end.php'; ?>