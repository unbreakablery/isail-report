<?php 
    require '../../inc/config.php';

    $common->authorizePage("progression_report");

    $iSAIL_UI->title = $common->getPrefixProjectName() . ' ' . 'Progression Report';

    if (empty($_POST) || !isset($_POST['player']) || !isset($_POST['model-element'])) {
        header('Location: criteria.php');
        exit;
    }

    $player             = $_POST['player'];
    $player_name        = $_POST['player_name'];
    $model_element      = $_POST['model-element'];
    $model_element_name = $common->getSalesLabels($model_element, 'label');

    // checking that more than 2 levels are checked.
    $levels = array();
    for ($i = 1; $i <= 3; $i += 1) {
        if (isset($_POST["level_$i"])) {
            $levels[] = $i;
        }
    }
    $level_count = count($levels);
    if ($level_count < 2) {
        header('Location: criteria.php');
        exit;
    }
    $vs_levels = '';
    foreach ($levels as $index => $level) {
        if ($index > 0) $vs_levels .= " vs ";
        $vs_levels .= "Level $level";
    }

    //Get sessions the user played
    $fields = array(
                    array(
                        'key'       => 'username', 
                        'value'     => $player, 
                        'operator'  => '='
                    )
                );

    $query = $common->generateQueryForGetSessionData($fields);

    $session_result = mysqli_query($common->db_connect, $query);
    
    $index          = 0;
    $attempts       = array();
    $level_1_data   = array();
    $level_2_data   = array();
    $level_3_data   = array();

    while ($row = mysqli_fetch_array($session_result)) {

        $game_levels = array();
        
        $fields = array(
                        array(
                            'key'       => 'session_ID', 
                            'value'     => $row['session_ID'], 
                            'operator'  => '='
                        )
                    );

        $query = $common->generateQueryForGetLevelData($fields);

        $level_result = mysqli_query($common->db_connect, $query);

        while ($temp = mysqli_fetch_array($level_result)) {
            $game_levels[] = $temp;
        }

        $index++;
        $attempts[] = array($index, $index);

        foreach ($game_levels as $game_level_data) {
            if (in_array($game_level_data['type'], $levels)) {
                if ($model_element == 'sales_group_1') {
                    $score = round((
                            $common->getScoreByParam($game_level_data['type'], 'sales1', $game_level_data['sales1']) +
                            $common->getScoreByParam($game_level_data['type'], 'sales2', $game_level_data['sales2']) + 
                            $common->getScoreByParam($game_level_data['type'], 'sales3', $game_level_data['sales3']) 
                        ) / 3);    
                } else if ($model_element == 'sales_group_2') {
                    $score = round((
                            $common->getScoreByParam($game_level_data['type'], 'sales4', $game_level_data['sales4']) +
                            $common->getScoreByParam($game_level_data['type'], 'sales5', $game_level_data['sales5']) + 
                            $common->getScoreByParam($game_level_data['type'], 'sales6', $game_level_data['sales6']) 
                        ) / 3);    
                } else if ($model_element == 'sales_group_3') {
                    $score = round((
                            $common->getScoreByParam($game_level_data['type'], 'sales7', $game_level_data['sales7']) +
                            $common->getScoreByParam($game_level_data['type'], 'sales8', $game_level_data['sales8']) + 
                            $common->getScoreByParam($game_level_data['type'], 'sales9', $game_level_data['sales9']) 
                        ) / 3);    
                } else {
                    $score = $common->getScoreByParam($game_level_data['type'], $model_element, $game_level_data[$model_element]);
                }
                
                switch ($game_level_data['type']) {
                    case 1:
                        $level_1_data[] = array($index, $score);
                        break;
                    case 2:
                        $level_2_data[] = array($index, $score);
                        break;
                    case 3:
                        $level_3_data[] = array($index, $score);
                        break;
                }
            }
        }
    }
    // Demo data 1
    // $level_1_data    = [[1, 23], [2, 25], [3, 54], [4, 67], [5, 92], [6, 83], [7, 100], [8, 91], [9, 96], [10, 100]];
    // $level_2_data    = [[1, 11], [2, 32], [3, 59], [4, 78], [5, 66], [6, 79], [7, 53], [8, 100], [9, 85], [10, 94]];
    // $level_3_data    = [[1, 18], [2, 37], [3, 49], [4, 63], [5, 78], [6, 99], [7, 82], [8, 59], [9, 88], [10, 98]];
    // $attempts        = [[1, 1], [2, 2], [3, 3], [4, 4], [5, 5], [6, 6], [7, 7], [8, 8], [9, 9], [10, 10]];

    // Demo data 2
    // $level_1_data    = [[2, 45], [3, 67], [4, 82], [5, 91]];
    // $level_2_data    = [[1, 89], [3, 43], [4, 70]];
    // $level_3_data    = [[1, 20], [2, 54], [3, 87], [5, 99]];
    // $attempts        = [[1, 1], [2, 2], [3, 3], [4, 4], [5, 5]];

    if (isset($_POST['export_csv'])) {
        header('Content-Description: File Transfer');
        header('Content-Encoding: UTF-8');
        header('Content-Type: application/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $pdf->getFileNameForExport('PPR', 'csv') . '"');
        header('Expires: 0');

        ob_start();

        echo "\xEF\xBB\xBF"; // UTF-8 BOM

        echo "Player Progression Report" . "\r\n";

        echo "\r\n";

        echo "Player: " . $player_name . "\r\n";

        echo "vs Levels: " . $vs_levels . "\r\n";

        echo $common->getModelsShortLabel() . " Element: " . $model_element_name . "\r\n";

        echo "\r\n";
        
        echo "# OF ATTEMPTS";
        foreach ($attempts as $attempt) {
            echo "," . $attempt[1];
        }
        echo "\r\n";

        foreach ($levels as $level) {
            switch ($level) {
                case 1:
                    $line_data = $level_1_data;
                    break;
                case 2:
                    $line_data = $level_2_data;
                    break;
                case 3:
                    $line_data = $level_3_data;
                    break;
            }

            echo "Level " . $level;

            $print_data = "";
            foreach ($attempts as $inx=>$attempt) {
                $flag = false;
                for ($i = 0; $i < count($line_data); $i++) {
                    if ($line_data[$i][0] - 1 == $inx) {
                        $print_data .= ',' . $line_data[$i][1] . '%';
                        $flag = true;
                        break;
                    }
                }

                if ($flag === false) {
                    $print_data .= ',';
                }
            }
            echo $print_data;

            echo "\r\n";
        }
        
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
                    <i class="fa fa-line-chart text-primary"></i>
                </span>
                Player Progression Report
            </h1>
        </div>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content content-narrow">
    <div class="row item-push">
        <div class="col-sm-3">
            <h4 class="font-w600 push">Player: <span class="text-success"><em><?php echo $player_name; ?></em></span></h4>
        </div>
        <div class="col-sm-4">
            <h4 class="font-w600 push">vs Levels: <span class="text-primary"><em><?php echo $vs_levels; ?></em></span></h4>
        </div>
        <div class="col-sm-5">
            <h4 class="font-w600 push"><?php echo $common->getModelsShortLabel(); ?> Element: <span class="text-danger"><em><?php echo $model_element_name; ?></em></span></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <!-- Lines Chart -->
            <div class="block">
                <div class="block-header">
                    <h3 class="block-title pull-left"># OF ATTEMPTS VS. <?php echo $common->getModelsShortLabel(); ?> ELEMENT SCORE</h3>
                    <div class="text-right">
                        <button class="btn btn-default btn-back" type="button">Back</button>
                        <button class="btn btn-primary" id="btn_export_csv" type="button"><i class="fa fa-file-excel-o"></i> Export CSV</button>
                        <button class="btn btn-primary" id="btn_export_pdf" type="button"><i class="fa fa-file-pdf-o"></i> Export in PDF</button>
                    </div>
                </div>
                <div class="block-content block-content-full">
                    <!-- Lines Chart Container -->
                    <div id="progression-chart-container" class="js-flot-lines height-500"></div>
                </div>
            </div>
            <!-- END Lines Chart -->
        </div>
    </div>
    <!-- END Flot Charts -->
</div>
<!-- END Page Content -->

<?php require '../../inc/views/modal_confirm_export.php'; ?>

<?php require '../../inc/views/base_footer.php'; ?>
<?php require '../../inc/views/template_footer_start.php'; ?>

<!-- Global JS -->
<?php require '../../inc/views/global.js.php'; ?>

<!-- Page JS Plugins -->
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/flot/jquery.flot.min.js"></script>
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/flot/jquery.flot.resize.min.js"></script>
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/flot/jquery.flot.axislabels.js"></script>

<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/jspdf/jspdf.min.js"></script>
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/html2canvas/html2canvas.js"></script>

<!-- Page JS Code -->
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/pages/progression_report_charts.js"></script>
<script>
    $(document).ready(function() {
        var $level_1_data           = <?php echo json_encode($level_1_data); ?>;
        var $level_2_data           = <?php echo json_encode($level_2_data); ?>;
        var $level_3_data           = <?php echo json_encode($level_3_data); ?>;
        var $levels                 = <?php echo json_encode($levels); ?>;
        var $model_element          = '<?php echo $model_element_name; ?>';
        var $attempts               = <?php echo json_encode($attempts); ?>;
        var $player_name            = '<?php echo $player_name; ?>';
        var $vs_levels              = '<?php echo $vs_levels; ?>';
        var $elements_short_label   = '<?php echo $common->getModelsShortLabel(); ?>';

        // Initialize when page loads
        ProgressionRptChart.init(
                                    $levels, 
                                    $model_element, 
                                    $attempts, 
                                    $level_1_data, 
                                    $level_2_data, 
                                    $level_3_data, 
                                    $elements_short_label
                                );

        $("button.btn-back").click(function() {
            window.location.href = "criteria.php";
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

            $("div#progression-chart-container .axisLabels.yaxisLabel").css("display", "none");

            html2canvas($("div#progression-chart-container"), {
                onrendered: function(canvas) {
                    $("div#progression-chart-container .axisLabels.yaxisLabel").css("display", "block");

                    var pdf             = new jsPDF('l', 'pt', 'a4');
                    
                    var pageWidth       = pdf.internal.pageSize.getWidth();
                    var pageHeight      = pdf.internal.pageSize.getHeight();
                    var canvasWidth     = canvas.width;
                    var canvasHeight    = canvas.height;
                    
                    var chartImage      = canvas.toDataURL("image/png", canvasWidth, canvasHeight);

                    var fileName        = getFileNameForExport('PPR', 'pdf');

                    var imgHeight       =  pageHeight - 200;
                    var imgWidth        = imgHeight * (canvasWidth / canvasHeight);

                    if (imgWidth >= pageWidth) {
                        imgWidth = pageWidth - 20;
                    }

                    var imgLeft         = (pageWidth - imgWidth) / 2;
                    var imgTop          = 180;

                    pdf.setFont("helvetica", "bold");
                    pdf.setFontSize(24);
                    pdf.text('Player Progression Report', 250, 50);

                    pdf.setFontSize(16);
                    pdf.setFontStyle("bolditalic");

                    pdf.setTextColor("#46c37b");
                    pdf.text('Player: ' + $player_name, 50, 100);

                    pdf.setTextColor("#5c90d2");
                    pdf.text('vs Levels: ' + $vs_levels, 50, 130);

                    pdf.setTextColor("#d26a5c");
                    pdf.text('<?php echo $common->getModelsShortLabel(); ?> Element: ' + $model_element, 50, 160);

                    pdf.addImage(chartImage, 'JPEG', imgLeft, imgTop, imgWidth, imgHeight);
                    
                    pdf.save(fileName);
                }
            });
            
        });
    });
</script>

<?php require '../../inc/views/template_footer_end.php'; ?>