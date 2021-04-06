<?php 
    require '../../inc/config.php';

    $common->authorizePage("aggregate_report");

    $iSAIL_UI->title = $common->getPrefixProjectName() . ' ' . 'Aggregate Report';
    
    $model_element_name = '';
    
    $param = '';
    $rubric = '';
    $level = '';
    
    if (!isset($_GET['param']) || $_GET['param'] == '') {
        header('Location: criteria.php');
        exit;
    } else {
        $param = $_GET['param'];
        $_SESSION['param'] = $param;
    }
    if (!isset($_GET['rubric']) || $_GET['rubric'] == '') {
        header('Location: criteria.php');
        exit;
    } else {
        $rubric = $_GET['rubric'];
        $_SESSION['rubric'] = $rubric;

        if(!in_array($rubric, $common->getRubricSet())) {
            header('Location: criteria.php');
            exit;
        }
    }

    if (!isset($_SESSION['time_period'])) {
        header('Location: criteria.php');
        exit;
    }

    $level = isset($_SESSION['level']) ? $_SESSION['level'] : 1;

    $time_period = $_SESSION['time_period'];

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

    $query = $common->generateISAILQuery($_SESSION);

    $result = mysqli_query($common->db_connect, $query);

    $players = array();

    while ($row = mysqli_fetch_array($result)) {
        $temp = array();
        $temp['id']             = $row['level_ID'];
        $temp['userid']         = $row['userid'];
        $temp['organization']   = $row['organization'];
        $temp['franchise']      = $row['franchise'];
        $temp['product']        = $row['product'];
        $temp['username']       = $row['first_name'] . ' ' . $row['last_name'];

        $model_element_name = $common->getSalesLabels($param, 'label');
        $score = $common->getScoreByParam($level, $param, $row[$param]);
        
        $temp['score'] = $score;

        if ($rubric == 'foundation') {
            if ($temp['score'] <= 67) {
                array_push($players, $temp);
            }
        } else if ($rubric == 'deep') {
            if ($temp['score'] >= 68 && $temp['score'] < 90) {
                array_push($players, $temp);
            }
        } else if ($rubric == 'advanced') {
            if ($temp['score'] >= 90) {
                array_push($players, $temp);
            }
        }  
    }

    $scoreRange = $common->getLegendScoreRange($rubric);
    $rubricLevel = $common->getLegendLabel($rubric);
    $textClassName = $common->getTextClassNameByRubric($rubric);

    if (isset($_POST['export_csv'])) {
        header('Content-Description: File Transfer');
        header('Content-Encoding: UTF-8');
        header('Content-Type: application/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $pdf->getFileNameForExport('ES', 'csv') . '"');
        header('Expires: 0');
    
        ob_start();
        
        echo "\xEF\xBB\xBF"; // UTF-8 BOM
        
        echo $common->getModelsShortLabel() . " Element Score Report" . "\r\n";

        echo "\r\n";

        echo "Level: " . $level . "\r\n";

        echo "Time Period: " . $time_period_label . "\r\n";

        echo $common->getModelsShortLabel() . " Element: " . $model_element_name . "\r\n";

        echo "Learner Rubric Level: " . $rubricLevel . "\r\n";

        echo "Score Range: " . $scoreRange . "\r\n";

        echo "\r\n";

        echo "№,Player,BU,Franchise,Product,Score" . "\r\n";

        for ($i = 0; $i < count($players); $i++) {
            echo ($i + 1)                       . ",";
            echo $players[$i]['username']       . ",";
            echo $players[$i]['organization']   . ",";
            echo $players[$i]['franchise']      . ",";
            echo $players[$i]['product']        . ",";
            echo $players[$i]['score']          . "%";
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
        <?php include '../../' . $iSAIL_UI->assets_folder . '/css/pdfs/element_score.css'; ?>
        <?php echo '</style>'; ?>

        <page>
            <h1 class="text-center"><?php echo $common->getModelsShortLabel(); ?> Element Score Report</h1>      
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
                    <tr>
                        <td class="text-right padding-10-r remove-border"><?php echo $common->getModelsShortLabel(); ?> Element:</td>
                        <td class="text-primary remove-border"><em><?php echo $model_element_name; ?></em></td>
                    </tr>
                    <tr>
                        <td class="text-right padding-10-r remove-border">Learner Rubric Level:</td>
                        <td class="remove-border <?php echo $textClassName; ?>"><em><?php echo $rubricLevel; ?></em></td>
                    </tr>
                    <tr>
                        <td class="text-right padding-10-r remove-border">Score Range:</td>
                        <td class="remove-border <?php echo $textClassName; ?>"><em><?php echo $scoreRange; ?></em></td>
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
                        <th>No</th>
                        <th>Player</th>
                        <th>BU</th>
                        <th>Franchise</th>
                        <th>Product</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($players); $i++) { ?>
                    <tr>
                        <td class="w-pt-5">
                            <?php echo ($i + 1); ?>
                        </td>
                        <td class="w-pt-25">
                            <?php echo $players[$i]['username']; ?>
                        </td>
                        <td class="w-pt-20">
                            <?php echo $players[$i]['organization']; ?>
                        </td>
                        <td class="w-pt-20">
                            <?php echo $players[$i]['franchise']; ?>
                        </td>
                        <td class="w-pt-20">
                            <?php echo $players[$i]['product']; ?>
                        </td>
                        <td class="text-white w-pt-10 font-weight-bold <?php echo $common->getProgressClassName($players[$i]['score']); ?>">
                            <?php echo $players[$i]['score']; ?>%
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </page>
        <?php
        $content = ob_get_contents();
        ob_end_clean();
        $pdf->generatePDF($content, 'L', 'ES');

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
                    <i class="fa fa-tasks text-primary"></i>
                </span>
                <?php echo $common->getModelsShortLabel(); ?> Element Score Report
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
                    <tr>
                        <td class="text-right padding-10-r"><?php echo $common->getModelsShortLabel(); ?> Element:</td>
                        <td><small><?php echo $model_element_name; ?></small></td>
                    </tr>
                    <tr>
                        <td class="text-right padding-10-r">Rubric Level:</td>
                        <td><small><?php echo $rubricLevel; ?></small></td>
                    </tr>
                    <tr>
                        <td class="text-right padding-10-r">Score Range:</td>
                        <td><small><?php echo $scoreRange; ?></small></td>
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
            <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/element_score.js -->
            <table class="table table-bordered table-striped js-dataTable-full table-header-bg table-vcenter">
                <thead>
                    <tr>
                        <th class="text-center">&#8470;</th>
                        <th class="text-center">Player</th>
                        <th class="text-center hidden-xs">BU</th>
                        <th class="text-center hidden-xs w-pt-15">Score</th>
                        <th class="text-center w-pt-10">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($players); $i++) { ?>
                    <tr>
                        <td class="text-center"><?php echo ($i + 1); ?></td>
                        <td class="font-w600"><?php echo $players[$i]['username']; ?></td>
                        <td class="text-center hidden-xs"><?php echo $players[$i]['organization']; ?></td>
                        <td class="text-center hidden-xs font-w600">
                            <span class="h5 label text-white font-w600 <?php echo $common->getProgressClassName($players[$i]['score']); ?>">
                                <?php echo $players[$i]['score']; ?>%
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button class="btn btn-primary" type="button" onClick="javascript:goDetailPage('<?php echo $players[$i]["id"]?>');">
                                    <i class="fa fa-bar-chart"></i> Detailed View...
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <div class="row push-10 remove-margin-l remove-margin-r">
                <form action="report.php" method="post" id="criteriaForm">
                    <input type="hidden" id="from_back" name="from_back" value="1" />
                    <div class="text-left push-10-t font-w700">
                        <span class="text-primary fa-pull-left push-10-r"><i class="fa fa-info-circle"></i> Legend : </span>
                        <span class="text-danger cursor-pointer" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $common->getLegendColor('foundation'); ?> <?php echo $common->getLegendScoreRange('foundation'); ?>"><?php echo $common->getLegendLabel('foundation'); ?>, </span>
                        <span class="text-warning cursor-pointer" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $common->getLegendColor('deep'); ?> <?php echo $common->getLegendScoreRange('deep'); ?>"><?php echo $common->getLegendLabel('deep'); ?>, </span>
                        <span class="text-success cursor-pointer" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $common->getLegendColor('advanced'); ?> <?php echo $common->getLegendScoreRange('advanced'); ?>"><?php echo $common->getLegendLabel('advanced'); ?></span>
                    </div>
                    <div class="text-right push-m25-t">
                        <button class="btn btn-default btn-back" type="submit">Back</button>
                    </div>
                </form>
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

<!-- Page JS Plugins -->
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.js"></script>

<!-- Page JS Code -->
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/pages/element_score.js"></script>

<!-- Global JS -->
<?php require '../../inc/views/global.js.php'; ?>

<script>
    function goDetailPage(id) {
        window.location.href = "learner_performance.php?id=" + id;
    }

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
            $("#criteriaForm").trigger('submit');
        });
    });
</script>

<?php require '../../inc/views/template_footer_end.php'; ?>