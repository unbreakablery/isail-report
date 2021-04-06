<?php
    require '../../inc/config.php';

    $common->authorizePage("comparison_model_report");

    $iSAIL_UI->title = $common->getPrefixProjectName() . ' ' . $common->getModelsShortLabel() . ' ' . 'Comparison Report';

    if (empty($_POST)) {
        header('Location: criteria.php');
        exit;
    }

    $data = $_POST;
    
    if (isset($data['export_csv']) || isset($data['export_pdf'])) {
        $bu_ids = explode(",", $data['bu_ids']);
        $model_parent_items = isset($data['model_parent_items']) ? explode(",", $data['model_parent_items']) : array();
        $model_sub_items = isset($data['model_sub_items']) ? explode(",", $data['model_sub_items']) : array();
    } else {
        $bu_ids = isset($data['bu_ids']) && is_array($data['bu_ids']) ? $data['bu_ids'] : array();
        $model_parent_items = isset($data['model_parent_items']) && is_array($data['model_parent_items']) ? $data['model_parent_items'] : array();
        $model_sub_items = isset($data['model_sub_items']) && is_array($data['model_sub_items']) ? $data['model_sub_items'] : array();
    }
    
    $level = isset($data['level']) ? $data['level'] : '1';

    $model_items = $model_parent_items + $model_sub_items;

    //check if role = 4
    if ($common->role == 4) {
        $data['unix_id'] = $_SESSION['userid'];
    }

    $data['time_period'] = 'specific-date';

    $query = $common->generateISAILQuery($data);

    $result = mysqli_query($common->db_connect, $query);

    while ($row = mysqli_fetch_array($result)) {
        foreach ($bu_ids as $bu_id) {
            if ($row['organization'] == $bu_id) {
                $isails[$bu_id][] = $row;
            }
        }
    }

    $bu_label = "";

    foreach ($bu_ids as $bu_id) {
        $bu_label .= $bu_id . " vs ";
        foreach ($model_items as $item) {
            $player[$bu_id][$item]['red'] = 0;
            $player[$bu_id][$item]['yellow'] = 0;
            $player[$bu_id][$item]['green'] = 0;
        }
    }

    foreach ($bu_ids as $bu_id) {
        for ($i = 0; isset($isails[$bu_id]) && $i < count($isails[$bu_id]); $i++) {
            foreach ($model_items as $item) {
                switch ($item) {
                    case "sales_group_1":
                        $average = round(
                            (
                                $common->getScoreByParam($level, 'sales1', $isails[$bu_id][$i]['sales1']) + 
                                $common->getScoreByParam($level, 'sales2', $isails[$bu_id][$i]['sales2']) + 
                                $common->getScoreByParam($level, 'sales3', $isails[$bu_id][$i]['sales3'])
                            ) / 3);
                        if ($average <= 67 ) {
                            $player[$bu_id][$item]['red']++;
                        }
                        if ($average > 67 && $average < 90) {
                            $player[$bu_id][$item]['yellow']++;
                        }
                        if ($average >= 90 ) {
                            $player[$bu_id][$item]['green']++;
                        }
                        break;
                    case "sales_group_2":
                        $average = round(
                            (
                                $common->getScoreByParam($level, 'sales4', $isails[$bu_id][$i]['sales4']) + 
                                $common->getScoreByParam($level, 'sales5', $isails[$bu_id][$i]['sales5']) + 
                                $common->getScoreByParam($level, 'sales6', $isails[$bu_id][$i]['sales6'])
                            ) / 3);
                        if ($average <= 67 ) {
                            $player[$bu_id][$item]['red']++;
                        }
                        if ($average > 67 && $average < 90) {
                            $player[$bu_id][$item]['yellow']++;
                        }
                        if ($average >= 90 ) {
                            $player[$bu_id][$item]['green']++;
                        }
                        break;
                    case "sales_group_3":
                        $average = round(
                            (
                                $common->getScoreByParam($level, 'sales7', $isails[$bu_id][$i]['sales7']) + 
                                $common->getScoreByParam($level, 'sales8', $isails[$bu_id][$i]['sales8']) + 
                                $common->getScoreByParam($level, 'sales9', $isails[$bu_id][$i]['sales9'])
                            ) / 3);
                        if ($average <= 67 ) {
                            $player[$bu_id][$item]['red']++;
                        }
                        if ($average > 67 && $average < 90) {
                            $player[$bu_id][$item]['yellow']++;
                        }
                        if ($average >= 90 ) {
                            $player[$bu_id][$item]['green']++;
                        }
                        break;
                    case "sales1":
                        $score = $common->getScoreByParam($level, 'sales1', $isails[$bu_id][$i]['sales1']);
                        if ( $score <= 67 ) {
                            $player[$bu_id][$item]['red']++;
                        }
                        if ($score > 67 && $score < 90) {
                            $player[$bu_id][$item]['yellow']++;
                        }
                        if ($score >= 90 ) {
                            $player[$bu_id][$item]['green']++;
                        }
                        break;
                    case "sales2":
                        $score = $common->getScoreByParam($level, 'sales2', $isails[$bu_id][$i]['sales2']);
                        if ( $score <= 67 ) {
                            $player[$bu_id][$item]['red']++;
                        }
                        if ($score > 67 && $score < 90) {
                            $player[$bu_id][$item]['yellow']++;
                        }
                        if ($score >= 90 ) {
                            $player[$bu_id][$item]['green']++;
                        }
                        break;
                    case "sales3":
                        $score = $common->getScoreByParam($level, 'sales3', $isails[$bu_id][$i]['sales3']);
                        if ( $score <= 67 ) {
                            $player[$bu_id][$item]['red']++;
                        }
                        if ($score > 67 && $score < 90) {
                            $player[$bu_id][$item]['yellow']++;
                        }
                        if ($score >= 90 ) {
                            $player[$bu_id][$item]['green']++;
                        }
                        break;
                    case "sales4":
                        $score = $common->getScoreByParam($level, 'sales4', $isails[$bu_id][$i]['sales4']);
                        if ( $score <= 67 ) {
                            $player[$bu_id][$item]['red']++;
                        }
                        if ($score > 67 && $score < 90) {
                            $player[$bu_id][$item]['yellow']++;
                        }
                        if ($score >= 90 ) {
                            $player[$bu_id][$item]['green']++;
                        }
                        break;
                    case "sales5":
                        $score = $common->getScoreByParam($level, 'sales5', $isails[$bu_id][$i]['sales5']);
                        if ( $score <= 67 ) {
                            $player[$bu_id][$item]['red']++;
                        }
                        if ($score > 67 && $score < 90) {
                            $player[$bu_id][$item]['yellow']++;
                        }
                        if ($score >= 90 ) {
                            $player[$bu_id][$item]['green']++;
                        }
                        break;
                    case "sales6":
                        $score = $common->getScoreByParam($level, 'sales6', $isails[$bu_id][$i]['sales6']);
                        if ( $score <= 67 ) {
                            $player[$bu_id][$item]['red']++;
                        }
                        if ($score > 67 && $score < 90) {
                            $player[$bu_id][$item]['yellow']++;
                        }
                        if ($score >= 90 ) {
                            $player[$bu_id][$item]['green']++;
                        }
                        break;
                    case "sales7":
                        $score = $common->getScoreByParam($level, 'sales7', $isails[$bu_id][$i]['sales7']);
                        if ( $score <= 67 ) {
                            $player[$bu_id][$item]['red']++;
                        }
                        if ($score > 67 && $score < 90) {
                            $player[$bu_id][$item]['yellow']++;
                        }
                        if ($score >= 90 ) {
                            $player[$bu_id][$item]['green']++;
                        }
                        break;
                    case "sales8":
                        $score = $common->getScoreByParam($level, 'sales8', $isails[$bu_id][$i]['sales8']);
                        if ( $score <= 67 ) {
                            $player[$bu_id][$item]['red']++;
                        }
                        if ($score > 67 && $score < 90) {
                            $player[$bu_id][$item]['yellow']++;
                        }
                        if ($score >= 90 ) {
                            $player[$bu_id][$item]['green']++;
                        }
                        break;
                    case "sales9":
                        $score = $common->getScoreByParam($level, 'sales9', $isails[$bu_id][$i]['sales9']);
                        if ( $score <= 67 ) {
                            $player[$bu_id][$item]['red']++;
                        }
                        if ($score > 67 && $score < 90) {
                            $player[$bu_id][$item]['yellow']++;
                        }
                        if ($score >= 90 ) {
                            $player[$bu_id][$item]['green']++;
                        }
                        break;
                }
            }
        }
    }

    $bu_label = rtrim($bu_label, " vs ");

    $sales_group_1_rowspan = 0;
    $sales_group_2_rowspan = 0;
    $sales_group_3_rowspan = 0;
    foreach ($model_items as $item) {
        if ($item == "sales1") $sales_group_1_rowspan++;
        if ($item == "sales2") $sales_group_1_rowspan++;
        if ($item == "sales3") $sales_group_1_rowspan++;
        if ($item == "sales4") $sales_group_2_rowspan++;
        if ($item == "sales5") $sales_group_2_rowspan++;
        if ($item == "sales6") $sales_group_2_rowspan++;
        if ($item == "sales7") $sales_group_3_rowspan++;
        if ($item == "sales8") $sales_group_3_rowspan++;
        if ($item == "sales9") $sales_group_3_rowspan++;
    }

    if (isset($data['specific_date']) && $data['specific_date'] != null) {
        $time_period_label = $data['specific_date'];

        if (isset($data['specific_time']) && $data['specific_time'] != null) {
            $time_period_label .= ' ' . $data['specific_time'];
        }
    } else {
        $time_period_label = 'ALL';
    }
    
    if (isset($_POST['export_csv'])) {
        header('Content-Description: File Transfer');
        header('Content-Encoding: UTF-8');
        header('Content-Type: application/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $pdf->getFileNameForExport($common->getModelsShortLabel() . '_CR', 'csv') . '"');
        header('Expires: 0');
    
        ob_start();

        echo "\xEF\xBB\xBF"; // UTF-8 BOM

        echo $common->getModelsShortLabel() . " Comparison Report" . "\r\n";

        echo "\r\n";

        echo "Level: " . $level . "\r\n";

        echo "vs BUs: " . $bu_label . "\r\n";

        echo "Time Period: " . $time_period_label . "\r\n";

        echo "\r\n";

        if (in_array("sales_group_1", $model_items) || in_array("sales_group_2", $model_items) || in_array("sales_group_3", $model_items)) {
            echo ",BUs,Foundation Knowledge,Deep Knowledge,Advanced Knowledge" . "\r\n";
        } else {
            echo ",,BUs,Foundation Knowledge,Deep Knowledge,Advanced Knowledge" . "\r\n";
        }

        if (in_array("sales_group_1", $model_items)) {
            for ($i = 0; $i < count($bu_ids); $i++) {
                $line = "";
                if ($i == 0) {
                    $line = strtoupper($common->getSalesLabels('sales_group_1', 'label')) . "," . $bu_ids[$i] . ",";
                } else {
                    $line = "," . $bu_ids[$i] . ",";
                }
                $line .= $player[$bu_ids[$i]]['sales_group_1']['red'] . ",";
                $line .= $player[$bu_ids[$i]]['sales_group_1']['yellow'] . ",";
                $line .= $player[$bu_ids[$i]]['sales_group_1']['green'];
                $line .= "\r\n";
                echo $line;
            }
        }
        if (in_array("sales_group_2", $model_items)) {
            for ($i = 0; $i < count($bu_ids); $i++) {
                $line = "";
                if ($i == 0) {
                    $line = strtoupper($common->getSalesLabels('sales_group_2', 'label')) . "," . $bu_ids[$i] . ",";
                } else {
                    $line = "," . $bu_ids[$i] . ",";
                }
                $line .= $player[$bu_ids[$i]]['sales_group_2']['red'] . ",";
                $line .= $player[$bu_ids[$i]]['sales_group_2']['yellow'] . ",";
                $line .= $player[$bu_ids[$i]]['sales_group_2']['green'];
                $line .= "\r\n";
                echo $line;
            }
        }
        if (in_array("sales_group_3", $model_items)) {
            for ($i = 0; $i < count($bu_ids); $i++) {
                $line = "";
                if ($i == 0) {
                    $line = strtoupper($common->getSalesLabels('sales_group_3', 'label')) . "," . $bu_ids[$i] . ",";
                } else {
                    $line = "," . $bu_ids[$i] . ",";
                }
                $line .= $player[$bu_ids[$i]]['sales_group_3']['red'] . ",";
                $line .= $player[$bu_ids[$i]]['sales_group_3']['yellow'] . ",";
                $line .= $player[$bu_ids[$i]]['sales_group_3']['green'];
                $line .= "\r\n";
                echo $line;
            }
        }

        if (in_array("sales1", $model_items)) {
            for ($i = 0; $i < count($bu_ids); $i++) {
                $line = "";
                if ($i == 0) {
                    $line = strtoupper($common->getSalesLabels('sales_group_1', 'label')) . "," . $common->getSalesLabels('sales1', 'label') . "," . $bu_ids[$i] . ",";
                } else {
                    $line = ",," . $bu_ids[$i] . ",";
                }
                $line .= $player[$bu_ids[$i]]['sales1']['red'] . ",";
                $line .= $player[$bu_ids[$i]]['sales1']['yellow'] . ",";
                $line .= $player[$bu_ids[$i]]['sales1']['green'];
                $line .= "\r\n";
                echo $line;
            }
        }
        if (in_array("sales2", $model_items)) {
            for ($i = 0; $i < count($bu_ids); $i++) {
                $line = "";
                if ($i == 0) {
                    if (!in_array("sales1", $model_items)) {
                        $line = strtoupper($common->getSalesLabels('sales_group_1', 'label')) . "," . $common->getSalesLabels('sales2', 'label') . "," . $bu_ids[$i] . ",";
                    } else {
                        $line = "," . $common->getSalesLabels('sales2', 'label') . "," . $bu_ids[$i] . ",";
                    }
                } else {
                    $line = ",," . $bu_ids[$i] . ",";
                }
                $line .= $player[$bu_ids[$i]]['sales2']['red'] . ",";
                $line .= $player[$bu_ids[$i]]['sales2']['yellow'] . ",";
                $line .= $player[$bu_ids[$i]]['sales2']['green'];
                $line .= "\r\n";
                echo $line;
            }
        }
        if (in_array("sales3", $model_items)) {
            for ($i = 0; $i < count($bu_ids); $i++) {
                $line = "";
                if ($i == 0) {
                    if (!in_array("sales1", $model_items) && !in_array("sales2", $model_items)) {
                        $line = strtoupper($common->getSalesLabels('sales_group_1', 'label')) . "," . $common->getSalesLabels('sales3', 'label') . "," . $bu_ids[$i] . ",";
                    } else {
                        $line = "," . $common->getSalesLabels('sales3', 'label') . "," . $bu_ids[$i] . ",";
                    }
                } else {
                    $line = ",," . $bu_ids[$i] . ",";
                }
                $line .= $player[$bu_ids[$i]]['sales3']['red'] . ",";
                $line .= $player[$bu_ids[$i]]['sales3']['yellow'] . ",";
                $line .= $player[$bu_ids[$i]]['sales3']['green'];
                $line .= "\r\n";
                echo $line;
            }
        }

        if (in_array("sales4", $model_items)) {
            for ($i = 0; $i < count($bu_ids); $i++) {
                $line = "";
                if ($i == 0) {
                    $line = strtoupper($common->getSalesLabels('sales_group_2', 'label')) . "," . $common->getSalesLabels('sales4', 'label') . "," . $bu_ids[$i] . ",";
                } else {
                    $line = ",," . $bu_ids[$i] . ",";
                }
                $line .= $player[$bu_ids[$i]]['sales4']['red'] . ",";
                $line .= $player[$bu_ids[$i]]['sales4']['yellow'] . ",";
                $line .= $player[$bu_ids[$i]]['sales4']['green'];
                $line .= "\r\n";
                echo $line;
            }
        }
        if (in_array("sales5", $model_items)) {
            for ($i = 0; $i < count($bu_ids); $i++) {
                $line = "";
                if ($i == 0) {
                    if (!in_array("sales4", $model_items)) {
                        $line = strtoupper($common->getSalesLabels('sales_group_2', 'label')) . "," . $common->getSalesLabels('sales5', 'label') . "," . $bu_ids[$i] . ",";
                    } else {
                        $line = "," . $common->getSalesLabels('sales5', 'label') . "," . $bu_ids[$i] . ",";
                    }
                } else {
                    $line = ",," . $bu_ids[$i] . ",";
                }
                $line .= $player[$bu_ids[$i]]['sales5']['red'] . ",";
                $line .= $player[$bu_ids[$i]]['sales5']['yellow'] . ",";
                $line .= $player[$bu_ids[$i]]['sales5']['green'];
                $line .= "\r\n";
                echo $line;
            }
        }
        if (in_array("sales6", $model_items)) {
            for ($i = 0; $i < count($bu_ids); $i++) {
                $line = "";
                if ($i == 0) {
                    if (!in_array("sales4", $model_items) && !in_array("sales5", $model_items)) {
                        $line = strtoupper($common->getSalesLabels('sales_group_2', 'label')) . "," . $common->getSalesLabels('sales6', 'label') . "," . $bu_ids[$i] . ",";
                    } else {
                        $line = "," . $common->getSalesLabels('sales6', 'label') . "," . $bu_ids[$i] . ",";
                    }
                } else {
                    $line = ",," . $bu_ids[$i] . ",";
                }
                $line .= $player[$bu_ids[$i]]['sales6']['red'] . ",";
                $line .= $player[$bu_ids[$i]]['sales6']['yellow'] . ",";
                $line .= $player[$bu_ids[$i]]['sales6']['green'];
                $line .= "\r\n";
                echo $line;
            }
        }

        if (in_array("sales7", $model_items)) {
            for ($i = 0; $i < count($bu_ids); $i++) {
                $line = "";
                if ($i == 0) {
                    $line = strtoupper($common->getSalesLabels('sales_group_3', 'label')) . "," . $common->getSalesLabels('sales7', 'label') . "," . $bu_ids[$i] . ",";
                } else {
                    $line = ",," . $bu_ids[$i] . ",";
                }
                $line .= $player[$bu_ids[$i]]['sales7']['red'] . ",";
                $line .= $player[$bu_ids[$i]]['sales7']['yellow'] . ",";
                $line .= $player[$bu_ids[$i]]['sales7']['green'];
                $line .= "\r\n";
                echo $line;
            }
        }
        if (in_array("sales8", $model_items)) {
            for ($i = 0; $i < count($bu_ids); $i++) {
                $line = "";
                if ($i == 0) {
                    if (!in_array("sales7", $model_items)) {
                        $line = strtoupper($common->getSalesLabels('sales_group_3', 'label')) . "," . $common->getSalesLabels('sales8', 'label') . "," . $bu_ids[$i] . ",";
                    } else {
                        $line = "," . $common->getSalesLabels('sales8', 'label') . "," . $bu_ids[$i] . ",";
                    }
                } else {
                    $line = ",," . $bu_ids[$i] . ",";
                }
                $line .= $player[$bu_ids[$i]]['sales8']['red'] . ",";
                $line .= $player[$bu_ids[$i]]['sales8']['yellow'] . ",";
                $line .= $player[$bu_ids[$i]]['sales8']['green'];
                $line .= "\r\n";
                echo $line;
            }
        }
        if (in_array("sales9", $model_items)) {
            for ($i = 0; $i < count($bu_ids); $i++) {
                $line = "";
                if ($i == 0) {
                    if (!in_array("sales7", $model_items) && !in_array("sales8", $model_items)) {
                        $line = strtoupper($common->getSalesLabels('sales_group_3', 'label')) . "," . $common->getSalesLabels('sales9', 'label') . "," . $bu_ids[$i] . ",";
                    } else {
                        $line = "," . $common->getSalesLabels('sales9', 'label') . "," . $bu_ids[$i] . ",";
                    }
                } else {
                    $line = ",," . $bu_ids[$i] . ",";
                }
                $line .= $player[$bu_ids[$i]]['sales9']['red'] . ",";
                $line .= $player[$bu_ids[$i]]['sales9']['yellow'] . ",";
                $line .= $player[$bu_ids[$i]]['sales9']['green'];
                $line .= "\r\n";
                echo $line;
            }
        }

        $csv = ob_get_contents();
        ob_end_clean();
        header("Content-Length: " . strlen($csv));
        echo $csv;
    
        exit;
    } else if (isset($_POST['export_pdf'])) {
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
        <?php include '../../' . $iSAIL_UI->assets_folder. '/css/pdfs/comparison_model_report.css'; ?>
        <?php echo '</style>'; ?>

        <h1 class="text-center"><?php echo $common->getModelsShortLabel(); ?> Comparison Report</h1>
        <h4 class="text-left remove-margin-t push-10-b">
            <table>
                <tr>
                    <td class="text-right padding-10-r remove-border">Level:</td>
                    <td class="text-primary remove-border"><em><?php echo $level; ?></em></td>
                </tr>
                <tr>
                    <td class="text-right padding-10-r remove-border">vs BUs:</td>
                    <td class="text-primary remove-border"><em><?php echo $bu_label; ?></em></td>
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
            <?php
                if (in_array("sales_group_1", $model_items)) {
            ?>
                <tr>
                    <td class="element-text text-center bg-modern text-white text-uppercase w-pt-20" rowspan="1"><?php echo $common->getSalesLabels('sales_group_1', 'label'); ?></td>
                    <td class="w-pt-80">
                        <?php
                        foreach ($bu_ids as $bu_id) {
                            if (isset($player[$bu_id]['sales_group_1'])) {
                                echo '<table class="full-width"><tbody><tr>';
                                echo '<td class="w-pt-10"><span>' . $bu_id . '</span></td>';
                                echo '<td class="w-pt-90">';
                                echo $common->generateProgressBar($player[$bu_id]['sales_group_1']['red'], $player[$bu_id]['sales_group_1']['yellow'], $player[$bu_id]['sales_group_1']['green']);
                                echo '</td>';
                                echo '</tr></tbody></table>';
                            }
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
            <?php
                if (in_array("sales_group_2", $model_items)) {
            ?>
                <tr>
                    <td class="element-text text-center bg-modern text-white text-uppercase w-pt-20" rowspan="1"><?php echo $common->getSalesLabels('sales_group_2', 'label'); ?></td>
                    <td class="w-pt-80">
                        <?php
                        foreach ($bu_ids as $bu_id) {
                            if (isset($player[$bu_id]['sales_group_2'])) {
                                echo '<table class="full-width"><tbody><tr>';
                                echo '<td class="w-pt-10"><span>' . $bu_id . '</span></td>';
                                echo '<td class="w-pt-90">';
                                echo $common->generateProgressBar($player[$bu_id]['sales_group_2']['red'], $player[$bu_id]['sales_group_2']['yellow'], $player[$bu_id]['sales_group_2']['green']);
                                echo '</td>';
                                echo '</tr></tbody></table>';
                            }
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
            <?php
                if (in_array("sales_group_3", $model_items)) {
            ?>
                <tr>
                    <td class="element-text text-center bg-modern text-white text-uppercase w-pt-20" rowspan="1"><?php echo $common->getSalesLabels('sales_group_3', 'label'); ?></td>
                    <td class="w-pt-80">
                        <?php
                        foreach ($bu_ids as $bu_id) {
                            if (isset($player[$bu_id]['sales_group_3'])) {
                                echo '<table class="full-width"><tbody><tr>';
                                echo '<td class="w-pt-10"><span>' . $bu_id . '</span></td>';
                                echo '<td class="w-pt-90">';
                                echo $common->generateProgressBar($player[$bu_id]['sales_group_3']['red'], $player[$bu_id]['sales_group_3']['yellow'], $player[$bu_id]['sales_group_3']['green']);
                                echo '</td>';
                                echo '</tr></tbody></table>';
                            }
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
            <?php
                if (in_array("sales1", $model_items)) {
            ?>
                <tr>
                    <td class="element-text text-center bg-modern text-white text-uppercase v-center padding-2-5 w-pt-10" rowspan="<?php echo $sales_group_1_rowspan; ?>"><?php echo $common->getSalesLabels('sales_group_1', 'label'); ?></td>
                    <td class="element-text bg-default text-right text-white v-center w-pt-25"><?php echo $common->getSalesLabels('sales1', 'label'); ?></td>
                    <td class="w-pt-65">
                        <?php
                        foreach ($bu_ids as $bu_id) {
                            if (isset($player[$bu_id]['sales1'])) {
                                echo '<table class="full-width"><tbody><tr>';
                                echo '<td class="w-pt-10"><span>' . $bu_id . '</span></td>';
                                echo '<td class="w-pt-90">';
                                echo $common->generateProgressBar($player[$bu_id]['sales1']['red'], $player[$bu_id]['sales1']['yellow'], $player[$bu_id]['sales1']['green']);
                                echo '</td>';
                                echo '</tr></tbody></table>';
                            }
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
            <?php
                if (in_array("sales2", $model_items)) {
            ?>
                <tr>
                    <?php if (!in_array("sales1", $model_items)) { ?>
                        <td class="element-text text-center bg-modern text-white text-uppercase v-center padding-2-5 w-pt-10" rowspan="<?php echo $sales_group_1_rowspan; ?>"><?php echo $common->getSalesLabels('sales_group_1', 'label'); ?></td>
                    <?php } ?>
                    <td class="element-text bg-default text-right text-white v-center w-pt-25"><?php echo $common->getSalesLabels('sales2', 'label'); ?></td>
                    <td class="w-pt-65">
                        <?php
                        foreach ($bu_ids as $bu_id) {
                            if (isset($player[$bu_id]['sales2'])) {
                                echo '<table class="full-width"><tbody><tr>';
                                echo '<td class="w-pt-10"><span>' . $bu_id . '</span></td>';
                                echo '<td class="w-pt-90">';
                                echo $common->generateProgressBar($player[$bu_id]['sales2']['red'], $player[$bu_id]['sales2']['yellow'], $player[$bu_id]['sales2']['green']);
                                echo '</td>';
                                echo '</tr></tbody></table>';
                            }
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
            <?php
                if (in_array("sales3", $model_items)) {
            ?>
                <tr>
                    <?php if (!in_array("sales1", $model_items) && !in_array("sales2", $model_items)) { ?>
                        <td class="element-text text-center bg-modern text-white text-uppercase v-center padding-2-5 w-pt-10" rowspan="<?php echo $sales_group_1_rowspan; ?>"><?php echo $common->getSalesLabels('sales_group_1', 'label'); ?></td>
                    <?php } ?>
                    <td class="element-text bg-default text-right text-white v-center w-pt-25"><?php echo $common->getSalesLabels('sales3', 'label'); ?></td>
                    <td class="w-pt-65">
                        <?php
                        foreach ($bu_ids as $bu_id) {
                            if (isset($player[$bu_id]['sales3'])) {
                                echo '<table class="full-width"><tbody><tr>';
                                echo '<td class="w-pt-10"><span>' . $bu_id . '</span></td>';
                                echo '<td class="w-pt-90">';
                                echo $common->generateProgressBar($player[$bu_id]['sales3']['red'], $player[$bu_id]['sales3']['yellow'], $player[$bu_id]['sales3']['green']);
                                echo '</td>';
                                echo '</tr></tbody></table>';
                            }
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
            <?php
                if (in_array("sales4", $model_items)) {
            ?>
                <tr>
                    <td class="element-text text-center bg-modern text-white text-uppercase v-center padding-2-5 w-pt-10" rowspan="<?php echo $sales_group_2_rowspan; ?>"><?php echo $common->getSalesLabels('sales_group_2', 'label'); ?></td>
                    <td class="element-text bg-default text-right text-white v-center w-pt-25"><?php echo $common->getSalesLabels('sales4', 'label'); ?></td>
                    <td class="w-pt-65">
                        <?php
                        foreach ($bu_ids as $bu_id) {
                            if (isset($player[$bu_id]['sales4'])) {
                                echo '<table class="full-width"><tbody><tr>';
                                echo '<td class="w-pt-10"><span>' . $bu_id . '</span></td>';
                                echo '<td class="w-pt-90">';
                                echo $common->generateProgressBar($player[$bu_id]['sales4']['red'], $player[$bu_id]['sales4']['yellow'], $player[$bu_id]['sales4']['green']);
                                echo '</td>';
                                echo '</tr></tbody></table>';
                            }
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
            <?php
                if (in_array("sales5", $model_items)) {
            ?>
                <tr>
                    <?php if (!in_array("sales4", $model_items)) { ?>
                        <td class="element-text text-center bg-modern text-white text-uppercase v-center padding-2-5 w-pt-10" rowspan="<?php echo $sales_group_2_rowspan; ?>"><?php echo $common->getSalesLabels('sales_group_2', 'label'); ?></td>
                    <?php } ?>
                    <td class="element-text bg-default text-right text-white v-center w-pt-25"><?php echo $common->getSalesLabels('sales5', 'label'); ?></td>
                    <td class="w-pt-65">
                        <?php
                        foreach ($bu_ids as $bu_id) {
                            if (isset($player[$bu_id]['sales5'])) {
                                echo '<table class="full-width"><tbody><tr>';
                                echo '<td class="w-pt-10"><span>' . $bu_id . '</span></td>';
                                echo '<td class="w-pt-90">';
                                echo $common->generateProgressBar($player[$bu_id]['sales5']['red'], $player[$bu_id]['sales5']['yellow'], $player[$bu_id]['sales5']['green']);
                                echo '</td>';
                                echo '</tr></tbody></table>';
                            }
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
            <?php
                if (in_array("sales6", $model_items)) {
            ?>
                <tr>
                    <?php if (!in_array("sales4", $model_items) && !in_array("sales5", $model_items)) { ?>
                        <td class="element-text text-center bg-modern text-white text-uppercase v-center padding-2-5 w-pt-10" rowspan="<?php echo $sales_group_2_rowspan; ?>"><?php echo $common->getSalesLabels('sales_group_2', 'label'); ?></td>
                    <?php } ?>
                    <td class="element-text bg-default text-right text-white v-center w-pt-25"><?php echo $common->getSalesLabels('sales6', 'label'); ?></td>
                    <td class="w-pt-65">
                        <?php
                        foreach ($bu_ids as $bu_id) {
                            if (isset($player[$bu_id]['sales6'])) {
                                echo '<table class="full-width"><tbody><tr>';
                                echo '<td class="w-pt-10"><span>' . $bu_id . '</span></td>';
                                echo '<td class="w-pt-90">';
                                echo $common->generateProgressBar($player[$bu_id]['sales6']['red'], $player[$bu_id]['sales6']['yellow'], $player[$bu_id]['sales6']['green']);
                                echo '</td>';
                                echo '</tr></tbody></table>';
                            }
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
            <?php
                if (in_array("sales7", $model_items)) {
            ?>
                <tr>
                    <td class="element-text text-center bg-modern text-white text-uppercase v-center padding-2-5 w-pt-10" rowspan="<?php echo $sales_group_3_rowspan; ?>"><?php echo $common->getSalesLabels('sales_group_3', 'label'); ?></td>
                    <td class="element-text bg-default text-right text-white v-center w-pt-25"><?php echo $common->getSalesLabels('sales7', 'label'); ?></td>
                    <td class="w-pt-65">
                        <?php
                        foreach ($bu_ids as $bu_id) {
                            if (isset($player[$bu_id]['sales7'])) {
                                echo '<table class="full-width"><tbody><tr>';
                                echo '<td class="w-pt-10"><span>' . $bu_id . '</span></td>';
                                echo '<td class="w-pt-90">';
                                echo $common->generateProgressBar($player[$bu_id]['sales7']['red'], $player[$bu_id]['sales7']['yellow'], $player[$bu_id]['sales7']['green']);
                                echo '</td>';
                                echo '</tr></tbody></table>';
                            }
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
            <?php
                if (in_array("sales8", $model_items)) {
            ?>
                <tr>
                    <?php if (!in_array("sales7", $model_items)) { ?>
                        <td class="element-text text-center bg-modern text-white text-uppercase v-center padding-2-5 w-pt-10" rowspan="<?php echo $sales_group_3_rowspan; ?>"><?php echo $common->getSalesLabels('sales_group_3', 'label'); ?></td>
                    <?php } ?>
                    <td class="element-text bg-default text-right text-white v-center w-pt-25"><?php echo $common->getSalesLabels('sales8', 'label'); ?></td>
                    <td class="w-pt-65">
                        <?php
                        foreach ($bu_ids as $bu_id) {
                            if (isset($player[$bu_id]['sales8'])) {
                                echo '<table class="full-width"><tbody><tr>';
                                echo '<td class="w-pt-10"><span>' . $bu_id . '</span></td>';
                                echo '<td class="w-pt-90">';
                                echo $common->generateProgressBar($player[$bu_id]['sales8']['red'], $player[$bu_id]['sales8']['yellow'], $player[$bu_id]['sales8']['green']);
                                echo '</td>';
                                echo '</tr></tbody></table>';
                            }
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
            <?php
                if (in_array("sales9", $model_items)) {
            ?>
                <tr>
                    <?php if (!in_array("sales7", $model_items) && !in_array("sales8", $model_items)) { ?>
                        <td class="element-text text-center bg-modern text-white text-uppercase v-center padding-2-5 w-pt-10" rowspan="<?php echo $sales_group_3_rowspan; ?>"><?php echo $common->getSalesLabels('sales_group_3', 'label'); ?></td>
                    <?php } ?>
                    <td class="element-text bg-default text-right text-white v-center w-pt-25"><?php echo $common->getSalesLabels('sales9', 'label'); ?></td>
                    <td class="w-pt-65">
                        <?php
                        foreach ($bu_ids as $bu_id) {
                            if (isset($player[$bu_id]['sales9'])) {
                                echo '<table class="full-width"><tbody><tr>';
                                echo '<td class="w-pt-10"><span>' . $bu_id . '</span></td>';
                                echo '<td class="w-pt-90">';
                                echo $common->generateProgressBar($player[$bu_id]['sales9']['red'], $player[$bu_id]['sales9']['yellow'], $player[$bu_id]['sales9']['green']);
                                echo '</td>';
                                echo '</tr></tbody></table>';
                            }
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
           </tbody>
        </table>
        <?php
        $content = ob_get_contents();
        ob_end_clean();
        $pdf->generatePDF($content, 'L', $common->getModelsShortLabel() . '_CR');
        
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
                    <i class="si si-bar-chart text-primary"></i>
                </span>
                <?php echo $common->getModelsShortLabel(); ?> Comparison Report
            </h1>
        </div>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content">
    <div class="block">
        <div class="table-responsive">
            <table class="table text-center table-vcenter remove-margin-b">
                <thead>
                    <tr>
                        <th colspan="2" class="w-pt-30">
                            <h3 class="block-title pull-left">
                                <table>
                                    <tr>
                                        <td class="text-right padding-10-r w-px-150">Level:</td>
                                        <td class="text-normal w-px-250"><small><?php echo $level; ?></small></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right padding-10-r text-normal">vs BUs:</td>
                                        <td class="text-normal"><small><?php echo $bu_label; ?></small></td>
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
                                <button class="btn btn-default btn-back">Back</button>
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
                    <?php
                        if (in_array("sales_group_1", $model_items)) {
                    ?>
                    <tr>
                        <td class="font-w600 text-center bg-modern text-white text-uppercase padding-2-5" rowspan="1"><?php echo $common->getSalesLabels('sales_group_1', 'label'); ?></td>
                        <td colspan="2">
                            <?php
                            foreach ($bu_ids as $bu_id) {
                                if (isset($player[$bu_id]['sales_group_1'])) {
                            
                            ?>
                            <span><?php echo $bu_id; ?></span>
                            <div class="progress remove-margin-b">
                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales_group_1']['red'] + $player[$bu_id]['sales_group_1']['yellow'] + $player[$bu_id]['sales_group_1']['green'] == 0) ? 0 : ($player[$bu_id]['sales_group_1']['red'] / ($player[$bu_id]['sales_group_1']['red'] + $player[$bu_id]['sales_group_1']['yellow'] + $player[$bu_id]['sales_group_1']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales_group_1']['red']; ?></div>
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales_group_1']['red'] + $player[$bu_id]['sales_group_1']['yellow'] + $player[$bu_id]['sales_group_1']['green'] == 0) ? 0 : ($player[$bu_id]['sales_group_1']['yellow'] / ($player[$bu_id]['sales_group_1']['red'] + $player[$bu_id]['sales_group_1']['yellow'] + $player[$bu_id]['sales_group_1']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales_group_1']['yellow']; ?></div>
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales_group_1']['red'] + $player[$bu_id]['sales_group_1']['yellow'] + $player[$bu_id]['sales_group_1']['green'] == 0) ? 0 : ($player[$bu_id]['sales_group_1']['green'] / ($player[$bu_id]['sales_group_1']['red'] + $player[$bu_id]['sales_group_1']['yellow'] + $player[$bu_id]['sales_group_1']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales_group_1']['green']; ?></div>
                            </div>
                            <?php
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php
                        if (in_array("sales_group_2", $model_items)) {
                    ?>
                    <tr>
                        <td class="font-w600 text-center bg-modern text-white text-uppercase padding-2-5" rowspan="1"><?php echo $common->getSalesLabels('sales_group_2', 'label'); ?></td>
                        <td colspan="2">
                            <?php
                            foreach ($bu_ids as $bu_id) {
                                if (isset($player[$bu_id]['sales_group_2'])) {
                            
                            ?>
                            <span><?php echo $bu_id; ?></span>
                            <div class="progress remove-margin-b">
                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales_group_2']['red'] + $player[$bu_id]['sales_group_2']['yellow'] + $player[$bu_id]['sales_group_2']['green'] == 0) ? 0 : ($player[$bu_id]['sales_group_2']['red'] / ($player[$bu_id]['sales_group_2']['red'] + $player[$bu_id]['sales_group_2']['yellow'] + $player[$bu_id]['sales_group_2']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales_group_2']['red']; ?></div>
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales_group_2']['red'] + $player[$bu_id]['sales_group_2']['yellow'] + $player[$bu_id]['sales_group_2']['green'] == 0) ? 0 : ($player[$bu_id]['sales_group_2']['yellow'] / ($player[$bu_id]['sales_group_2']['red'] + $player[$bu_id]['sales_group_2']['yellow'] + $player[$bu_id]['sales_group_2']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales_group_2']['yellow']; ?></div>
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales_group_2']['red'] + $player[$bu_id]['sales_group_2']['yellow'] + $player[$bu_id]['sales_group_2']['green'] == 0) ? 0 : ($player[$bu_id]['sales_group_2']['green'] / ($player[$bu_id]['sales_group_2']['red'] + $player[$bu_id]['sales_group_2']['yellow'] + $player[$bu_id]['sales_group_2']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales_group_2']['green']; ?></div>
                            </div>
                            <?php
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php
                        if (in_array("sales_group_3", $model_items)) {
                    ?>
                    <tr>
                        <td class="font-w600 text-center bg-modern text-white text-uppercase padding-2-5" rowspan="1"><?php echo $common->getSalesLabels('sales_group_3', 'label'); ?></td>
                        <td colspan="2">
                            <?php
                            foreach ($bu_ids as $bu_id) {
                                if (isset($player[$bu_id]['sales_group_3'])) {
                            
                            ?>
                            <span><?php echo $bu_id; ?></span>
                            <div class="progress remove-margin-b">
                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales_group_3']['red'] + $player[$bu_id]['sales_group_3']['yellow'] + $player[$bu_id]['sales_group_3']['green'] == 0) ? 0 : ($player[$bu_id]['sales_group_3']['red'] / ($player[$bu_id]['sales_group_3']['red'] + $player[$bu_id]['sales_group_3']['yellow'] + $player[$bu_id]['sales_group_3']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales_group_3']['red']; ?></div>
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales_group_3']['red'] + $player[$bu_id]['sales_group_3']['yellow'] + $player[$bu_id]['sales_group_3']['green'] == 0) ? 0 : ($player[$bu_id]['sales_group_3']['yellow'] / ($player[$bu_id]['sales_group_3']['red'] + $player[$bu_id]['sales_group_3']['yellow'] + $player[$bu_id]['sales_group_3']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales_group_3']['yellow']; ?></div>
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales_group_3']['red'] + $player[$bu_id]['sales_group_3']['yellow'] + $player[$bu_id]['sales_group_3']['green'] == 0) ? 0 : ($player[$bu_id]['sales_group_3']['green'] / ($player[$bu_id]['sales_group_3']['red'] + $player[$bu_id]['sales_group_3']['yellow'] + $player[$bu_id]['sales_group_3']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales_group_3']['green']; ?></div>
                            </div>
                            <?php
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <?php } ?>

                    <?php
                        if (in_array("sales1", $model_items)) {
                    ?>
                    <tr>
                        <td class="font-w600 text-center bg-modern text-white text-uppercase padding-2-5" rowspan="<?php echo $sales_group_1_rowspan; ?>"><?php echo $common->getSalesLabels('sales_group_1', 'label'); ?></td>
                        <td class="bg-default text-right text-white"><?php echo $common->getSalesLabels('sales1', 'label'); ?></td>
                        <td>
                            <?php
                            foreach ($bu_ids as $bu_id) {
                                if (isset($player[$bu_id]['sales1'])) {
                            
                            ?>
                            <span><?php echo $bu_id; ?></span>
                            <div class="progress remove-margin-b">
                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales1']['red'] + $player[$bu_id]['sales1']['yellow'] + $player[$bu_id]['sales1']['green'] == 0) ? 0 : ($player[$bu_id]['sales1']['red'] / ($player[$bu_id]['sales1']['red'] + $player[$bu_id]['sales1']['yellow'] + $player[$bu_id]['sales1']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales1']['red']; ?></div>
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales1']['red'] + $player[$bu_id]['sales1']['yellow'] + $player[$bu_id]['sales1']['green'] == 0) ? 0 : ($player[$bu_id]['sales1']['yellow'] / ($player[$bu_id]['sales1']['red'] + $player[$bu_id]['sales1']['yellow'] + $player[$bu_id]['sales1']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales1']['yellow']; ?></div>
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales1']['red'] + $player[$bu_id]['sales1']['yellow'] + $player[$bu_id]['sales1']['green'] == 0) ? 0 : ($player[$bu_id]['sales1']['green'] / ($player[$bu_id]['sales1']['red'] + $player[$bu_id]['sales1']['yellow'] + $player[$bu_id]['sales1']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales1']['green']; ?></div>
                            </div>
                            <?php
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php
                        if (in_array("sales2", $model_items)) {
                    ?>
                    <tr>
                        <?php if (!in_array("sales1", $model_items)) { ?>
                        <td class="font-w600 text-center bg-modern text-white text-uppercase padding-2-5" rowspan="<?php echo $sales_group_1_rowspan; ?>"><?php echo $common->getSalesLabels('sales_group_1', 'label'); ?></td>
                        <?php } ?>
                        <td class="bg-default text-right text-white"><?php echo $common->getSalesLabels('sales2', 'label'); ?></td>
                        <td>
                            <?php
                            foreach ($bu_ids as $bu_id) {
                                if (isset($player[$bu_id]['sales2'])) {
                            
                            ?>
                            <span><?php echo $bu_id; ?></span>
                            <div class="progress remove-margin-b">
                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales2']['red'] + $player[$bu_id]['sales2']['yellow'] + $player[$bu_id]['sales2']['green'] == 0) ? 0 : ($player[$bu_id]['sales2']['red'] / ($player[$bu_id]['sales2']['red'] + $player[$bu_id]['sales2']['yellow'] + $player[$bu_id]['sales2']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales2']['red']; ?></div>
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales2']['red'] + $player[$bu_id]['sales2']['yellow'] + $player[$bu_id]['sales2']['green'] == 0) ? 0 : ($player[$bu_id]['sales2']['yellow'] / ($player[$bu_id]['sales2']['red'] + $player[$bu_id]['sales2']['yellow'] + $player[$bu_id]['sales2']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales2']['yellow']; ?></div>
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales2']['red'] + $player[$bu_id]['sales2']['yellow'] + $player[$bu_id]['sales2']['green'] == 0) ? 0 : ($player[$bu_id]['sales2']['green'] / ($player[$bu_id]['sales2']['red'] + $player[$bu_id]['sales2']['yellow'] + $player[$bu_id]['sales2']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales2']['green']; ?></div>
                            </div>
                            <?php
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php
                        if (in_array("sales3", $model_items)) {
                    ?>
                    <tr>
                        <?php if (!in_array("sales1", $model_items) && !in_array("sales2", $model_items)) { ?>
                        <td class="font-w600 text-center bg-modern text-white text-uppercase padding-2-5" rowspan="<?php echo $sales_group_1_rowspan; ?>"><?php echo $common->getSalesLabels('sales_group_1', 'label'); ?></td>
                        <?php } ?>
                        <td class="bg-default text-right text-white"><?php echo $common->getSalesLabels('sales3', 'label'); ?></td>
                        <td>
                            <?php
                            foreach ($bu_ids as $bu_id) {
                                if (isset($player[$bu_id]['sales3'])) {
                            
                            ?>
                            <span><?php echo $bu_id; ?></span>
                            <div class="progress remove-margin-b">
                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales3']['red'] + $player[$bu_id]['sales3']['yellow'] + $player[$bu_id]['sales3']['green'] == 0) ? 0 : ($player[$bu_id]['sales3']['red'] / ($player[$bu_id]['sales3']['red'] + $player[$bu_id]['sales3']['yellow'] + $player[$bu_id]['sales3']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales3']['red']; ?></div>
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales3']['red'] + $player[$bu_id]['sales3']['yellow'] + $player[$bu_id]['sales3']['green'] == 0) ? 0 : ($player[$bu_id]['sales3']['yellow'] / ($player[$bu_id]['sales3']['red'] + $player[$bu_id]['sales3']['yellow'] + $player[$bu_id]['sales3']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales3']['yellow']; ?></div>
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales3']['red'] + $player[$bu_id]['sales3']['yellow'] + $player[$bu_id]['sales3']['green'] == 0) ? 0 : ($player[$bu_id]['sales3']['green'] / ($player[$bu_id]['sales3']['red'] + $player[$bu_id]['sales3']['yellow'] + $player[$bu_id]['sales3']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales3']['green']; ?></div>
                            </div>
                            <?php
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php
                        if (in_array("sales4", $model_items)) {
                    ?>
                    <tr>
                        <td class="font-w600 text-center bg-modern text-white text-uppercase padding-2-5" rowspan="<?php echo $sales_group_2_rowspan; ?>"><?php echo $common->getSalesLabels('sales_group_2', 'label'); ?></td>
                        <td class="bg-default text-right text-white"><?php echo $common->getSalesLabels('sales4', 'label'); ?></td>
                        <td>
                            <?php
                            foreach ($bu_ids as $bu_id) {
                                if (isset($player[$bu_id]['sales4'])) {
                            
                            ?>
                            <span><?php echo $bu_id; ?></span>
                            <div class="progress remove-margin-b">
                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales4']['red'] + $player[$bu_id]['sales4']['yellow'] + $player[$bu_id]['sales4']['green'] == 0) ? 0 : ($player[$bu_id]['sales4']['red'] / ($player[$bu_id]['sales4']['red'] + $player[$bu_id]['sales4']['yellow'] + $player[$bu_id]['sales4']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales4']['red']; ?></div>
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales4']['red'] + $player[$bu_id]['sales4']['yellow'] + $player[$bu_id]['sales4']['green'] == 0) ? 0 : ($player[$bu_id]['sales4']['yellow'] / ($player[$bu_id]['sales4']['red'] + $player[$bu_id]['sales4']['yellow'] + $player[$bu_id]['sales4']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales4']['yellow']; ?></div>
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales4']['red'] + $player[$bu_id]['sales4']['yellow'] + $player[$bu_id]['sales4']['green'] == 0) ? 0 : ($player[$bu_id]['sales4']['green'] / ($player[$bu_id]['sales4']['red'] + $player[$bu_id]['sales4']['yellow'] + $player[$bu_id]['sales4']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales4']['green']; ?></div>
                            </div>
                            <?php
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php
                        if (in_array("sales5", $model_items)) {
                    ?>
                    <tr>
                        <?php if (!in_array("sales4", $model_items)) { ?>
                        <td class="font-w600 text-center bg-modern text-white text-uppercase padding-2-5" rowspan="<?php echo $sales_group_2_rowspan; ?>"><?php echo $common->getSalesLabels('sales_group_2', 'label'); ?></td>
                        <?php } ?>
                        <td class="bg-default text-right text-white"><?php echo $common->getSalesLabels('sales5', 'label'); ?></td>
                        <td>
                            <?php
                            foreach ($bu_ids as $bu_id) {
                                if (isset($player[$bu_id]['sales5'])) {
                            
                            ?>
                            <span><?php echo $bu_id; ?></span>
                            <div class="progress remove-margin-b">
                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales5']['red'] + $player[$bu_id]['sales5']['yellow'] + $player[$bu_id]['sales5']['green'] == 0) ? 0 : ($player[$bu_id]['sales5']['red'] / ($player[$bu_id]['sales5']['red'] + $player[$bu_id]['sales5']['yellow'] + $player[$bu_id]['sales5']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales5']['red']; ?></div>
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales5']['red'] + $player[$bu_id]['sales5']['yellow'] + $player[$bu_id]['sales5']['green'] == 0) ? 0 : ($player[$bu_id]['sales5']['yellow'] / ($player[$bu_id]['sales5']['red'] + $player[$bu_id]['sales5']['yellow'] + $player[$bu_id]['sales5']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales5']['yellow']; ?></div>
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales5']['red'] + $player[$bu_id]['sales5']['yellow'] + $player[$bu_id]['sales5']['green'] == 0) ? 0 : ($player[$bu_id]['sales5']['green'] / ($player[$bu_id]['sales5']['red'] + $player[$bu_id]['sales5']['yellow'] + $player[$bu_id]['sales5']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales5']['green']; ?></div>
                            </div>
                            <?php
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php
                        if (in_array("sales6", $model_items)) {
                    ?>
                    <tr>
                        <?php if (!in_array("sales4", $model_items) && !in_array("sales5", $model_items)) { ?>
                        <td class="font-w600 text-center bg-modern text-white text-uppercase padding-2-5" rowspan="<?php echo $sales_group_2_rowspan; ?>"><?php echo $common->getSalesLabels('sales_group_2', 'label'); ?></td>
                        <?php } ?>
                        <td class="bg-default text-right text-white"><?php echo $common->getSalesLabels('sales6', 'label'); ?></td>
                        <td>
                            <?php
                            foreach ($bu_ids as $bu_id) {
                                if (isset($player[$bu_id]['sales6'])) {
                            
                            ?>
                            <span><?php echo $bu_id; ?></span>
                            <div class="progress remove-margin-b">
                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales6']['red'] + $player[$bu_id]['sales6']['yellow'] + $player[$bu_id]['sales6']['green'] == 0) ? 0 : ($player[$bu_id]['sales6']['red'] / ($player[$bu_id]['sales6']['red'] + $player[$bu_id]['sales6']['yellow'] + $player[$bu_id]['sales6']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales6']['red']; ?></div>
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales6']['red'] + $player[$bu_id]['sales6']['yellow'] + $player[$bu_id]['sales6']['green'] == 0) ? 0 : ($player[$bu_id]['sales6']['yellow'] / ($player[$bu_id]['sales6']['red'] + $player[$bu_id]['sales6']['yellow'] + $player[$bu_id]['sales6']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales6']['yellow']; ?></div>
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales6']['red'] + $player[$bu_id]['sales6']['yellow'] + $player[$bu_id]['sales6']['green'] == 0) ? 0 : ($player[$bu_id]['sales6']['green'] / ($player[$bu_id]['sales6']['red'] + $player[$bu_id]['sales6']['yellow'] + $player[$bu_id]['sales6']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales6']['green']; ?></div>
                            </div>
                            <?php
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php
                        if (in_array("sales7", $model_items)) {
                    ?>
                    <tr>
                        <td class="font-w600 text-center bg-modern text-white text-uppercase padding-2-5" rowspan="<?php echo $sales_group_3_rowspan; ?>"><?php echo $common->getSalesLabels('sales_group_3', 'label'); ?></td>
                        <td class="bg-default text-right text-white"><?php echo $common->getSalesLabels('sales7', 'label'); ?></td>
                        <td>
                            <?php
                            foreach ($bu_ids as $bu_id) {
                                if (isset($player[$bu_id]['sales7'])) {
                            
                            ?>
                            <span><?php echo $bu_id; ?></span>
                            <div class="progress remove-margin-b">
                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales7']['red'] + $player[$bu_id]['sales7']['yellow'] + $player[$bu_id]['sales7']['green'] == 0) ? 0 : ($player[$bu_id]['sales7']['red'] / ($player[$bu_id]['sales7']['red'] + $player[$bu_id]['sales7']['yellow'] + $player[$bu_id]['sales7']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales7']['red']; ?></div>
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales7']['red'] + $player[$bu_id]['sales7']['yellow'] + $player[$bu_id]['sales7']['green'] == 0) ? 0 : ($player[$bu_id]['sales7']['yellow'] / ($player[$bu_id]['sales7']['red'] + $player[$bu_id]['sales7']['yellow'] + $player[$bu_id]['sales7']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales7']['yellow']; ?></div>
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales7']['red'] + $player[$bu_id]['sales7']['yellow'] + $player[$bu_id]['sales7']['green'] == 0) ? 0 : ($player[$bu_id]['sales7']['green'] / ($player[$bu_id]['sales7']['red'] + $player[$bu_id]['sales7']['yellow'] + $player[$bu_id]['sales7']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales7']['green']; ?></div>
                            </div>
                            <?php
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php
                        if (in_array("sales8", $model_items)) {
                    ?>
                    <tr>
                        <?php if (!in_array("sales7", $model_items)) { ?>
                        <td class="font-w600 text-center bg-modern text-white text-uppercase padding-2-5" rowspan="<?php echo $sales_group_3_rowspan; ?>"><?php echo $common->getSalesLabels('sales_group_3', 'label'); ?></td>
                        <?php } ?>
                        <td class="bg-default text-right text-white"><?php echo $common->getSalesLabels('sales8', 'label'); ?></td>
                        <td>
                            <?php
                            foreach ($bu_ids as $bu_id) {
                                if (isset($player[$bu_id]['sales8'])) {
                            
                            ?>
                            <span><?php echo $bu_id; ?></span>
                            <div class="progress remove-margin-b">
                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales8']['red'] + $player[$bu_id]['sales8']['yellow'] + $player[$bu_id]['sales8']['green'] == 0) ? 0 : ($player[$bu_id]['sales8']['red'] / ($player[$bu_id]['sales8']['red'] + $player[$bu_id]['sales8']['yellow'] + $player[$bu_id]['sales8']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales8']['red']; ?></div>
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales8']['red'] + $player[$bu_id]['sales8']['yellow'] + $player[$bu_id]['sales8']['green'] == 0) ? 0 : ($player[$bu_id]['sales8']['yellow'] / ($player[$bu_id]['sales8']['red'] + $player[$bu_id]['sales8']['yellow'] + $player[$bu_id]['sales8']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales8']['yellow']; ?></div>
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales8']['red'] + $player[$bu_id]['sales8']['yellow'] + $player[$bu_id]['sales8']['green'] == 0) ? 0 : ($player[$bu_id]['sales8']['green'] / ($player[$bu_id]['sales8']['red'] + $player[$bu_id]['sales8']['yellow'] + $player[$bu_id]['sales8']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales8']['green']; ?></div>
                            </div>
                            <?php
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php
                        if (in_array("sales9", $model_items)) {
                    ?>
                    <tr>
                        <?php if (!in_array("sales7", $model_items) && !in_array("sales8", $model_items)) { ?>
                        <td class="font-w600 text-center bg-modern text-white text-uppercase padding-2-5" rowspan="<?php echo $sales_group_3_rowspan; ?>"><?php echo $common->getSalesLabels('sales_group_3', 'label'); ?></td>
                        <?php } ?>
                        <td class="bg-default text-right text-white"><?php echo $common->getSalesLabels('sales9', 'label'); ?></td>
                        <td>
                            <?php
                            foreach ($bu_ids as $bu_id) {
                                if (isset($player[$bu_id]['sales9'])) {
                            
                            ?>
                            <span><?php echo $bu_id; ?></span>
                            <div class="progress remove-margin-b">
                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales9']['red'] + $player[$bu_id]['sales9']['yellow'] + $player[$bu_id]['sales9']['green'] == 0) ? 0 : ($player[$bu_id]['sales9']['red'] / ($player[$bu_id]['sales9']['red'] + $player[$bu_id]['sales9']['yellow'] + $player[$bu_id]['sales9']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales9']['red']; ?></div>
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales9']['red'] + $player[$bu_id]['sales9']['yellow'] + $player[$bu_id]['sales9']['green'] == 0) ? 0 : ($player[$bu_id]['sales9']['yellow'] / ($player[$bu_id]['sales9']['red'] + $player[$bu_id]['sales9']['yellow'] + $player[$bu_id]['sales9']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales9']['yellow']; ?></div>
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($player[$bu_id]['sales9']['red'] + $player[$bu_id]['sales9']['yellow'] + $player[$bu_id]['sales9']['green'] == 0) ? 0 : ($player[$bu_id]['sales9']['green'] / ($player[$bu_id]['sales9']['red'] + $player[$bu_id]['sales9']['yellow'] + $player[$bu_id]['sales9']['green'])) * 100; ?>%"><?php echo $player[$bu_id]['sales9']['green']; ?></div>
                            </div>
                            <?php
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- END Page Content -->

<?php require '../../inc/views/modal_legend.php'; ?>

<?php require '../../inc/views/modal_confirm_export.php'; ?>

<?php require '../../inc/views/base_footer.php'; ?>
<?php require '../../inc/views/template_footer_start.php'; ?>

<!-- Global JS -->
<?php require '../../inc/views/global.js.php'; ?>

<!-- Page JS Code -->
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
            exportForm.push("<input type='hidden' name='export_pdf' value='export_pdf' />");
            exportForm.push('</form>');
            $(exportForm.join('')).prependTo('body').submit();
        });

        $("button.btn-back").click(function() {
            window.location.href = "criteria.php";
        });
    });
</script>

<?php require '../../inc/views/template_footer_end.php'; ?>