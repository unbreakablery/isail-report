<?php 
    require '../../inc/config.php';
    
    $common->authorizePage("comparison_report");

    $iSAIL_UI->title = $common->getPrefixProjectName() . ' ' . 'Comparison Report';
    
    $show_time = isset($_POST['show_time']) ? $_POST['show_time'] : 0;

    if ($show_time == 1) {
        $start_index = 0;
    } else {
        $start_index = 1;
    }
    
    if (empty($_POST)) {
        header('Location: criteria.php');
        exit;
    }

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
    
    //make players data
    $players = array();
    $userList = array();
    foreach ($levels as $level) {
        $_POST['level'] = $level;

        $query = $common->generateISAILQueryForComparison($_POST);

        $result = mysqli_query($common->db_connect, $query);

        while ($row = mysqli_fetch_array($result)) {
            if (in_array($row['userid'], $userList)) {
                for ($i = 0; $i < count($players); $i++) {
                    if ($players[$i]['userid'] == $row['userid']) {
                        $players[$i]['time_' . $level] = $row['time'];
                        $players[$i]['score_' . $level] = $row['score'];
                        for ($j = 1; $j <= 9; $j++) {
                            $players[$i]['sales' . $j . '_' . $level] = $row['sales' . $j];
                        }
                        break;
                    }
                }
            } else {
                array_push($userList, $row['userid']);

                $temp = $common->createPlayer();
                $temp['userid']             = $row['userid'];
                $temp['username']           = $row['first_name'] . ' ' . $row['last_name'];
                $temp['time_' . $level]     = $row['time'];
                $temp['score_' . $level]    = $row['score'];
                
                for ($i = 1; $i <= 9; $i++) {
                    $temp['sales' . $i . '_' . $level] = $row['sales' . $i];
                }
                
                array_push($players, $temp);
            }
        }
    }

    $time_period_label = $common->getTimePeriodLabel($_POST['time_period']);

    if ($_POST['time_period'] == 'specific-date') {
        if (isset($_POST['specific_date'])) {
            $time_period_label .= ' ' . $_POST['specific_date'];
        }
        if (isset($_POST['specific_time'])) {
            $time_period_label .= ' ' . substr($_POST['specific_time'], 0 , 8);
        }
    }

    if (isset($_POST['game_key_number']) && !empty($_POST['game_key_number'])) {
        $time_period_label .= ' [' . $_POST['game_key_number'] . ']';
    }

    if (isset($_POST['export_csv'])) {
        header('Content-Description: File Transfer');
        header('Content-Encoding: UTF-8');
        header('Content-Type: application/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $pdf->getFileNameForExport('CR', 'csv') . '"');
        header('Expires: 0');
    
        ob_start();

        echo "\xEF\xBB\xBF"; // UTF-8 BOM

        echo "Player Comparison Report" . "\r\n";

        echo "\r\n";

        echo "vs Levels: " . $vs_levels . "\r\n";

        echo "Time Period: " . $time_period_label . "\r\n";

        echo "\r\n";

        $level_blank_str = '';
        for ($i = 0; $i < $level_count - 1; $i += 1) {
            $level_blank_str .= ",";
        }

        // first row
        echo "№,Player";
        if ($show_time == 1) {
            echo ",TIMES$level_blank_str";
        }
        
        echo "," . strtoupper($common->getSalesLabels('sales_group_1', 'label')) . "$level_blank_str,$level_blank_str,$level_blank_str";
        echo "," . strtoupper($common->getSalesLabels('sales_group_2', 'label')) . "$level_blank_str,$level_blank_str,$level_blank_str";
        echo "," . strtoupper($common->getSalesLabels('sales_group_3', 'label')) . "$level_blank_str,$level_blank_str,$level_blank_str";
        echo "\r\n";

        // second row
        if ($show_time == 1) {
            echo ",,$level_blank_str";
            
        } else {
            echo ",";
        }
        echo "," . $common->getSalesLabels('sales1', 'short_label') . $level_blank_str;
        echo "," . $common->getSalesLabels('sales2', 'short_label') . $level_blank_str;
        echo "," . $common->getSalesLabels('sales3', 'short_label') . $level_blank_str;
        echo "," . $common->getSalesLabels('sales4', 'short_label') . $level_blank_str;
        echo "," . $common->getSalesLabels('sales5', 'short_label') . $level_blank_str;
        echo "," . $common->getSalesLabels('sales6', 'short_label') . $level_blank_str;
        echo "," . $common->getSalesLabels('sales7', 'short_label') . $level_blank_str;
        echo "," . $common->getSalesLabels('sales8', 'short_label') . $level_blank_str;
        echo "," . $common->getSalesLabels('sales9', 'short_label') . $level_blank_str;
        echo "\r\n";

        echo ",,";

        for ($i = $start_index; $i < 10; $i += 1) {
            foreach ($levels as $level) {
                echo "$level,";
            }
        }
        echo "\r\n";

        foreach ($players as $i => $player) {
            echo ($i + 1) . ",";
            echo $player['username'] . ",";

            if ($show_time == 1) {
                foreach ($levels as $level) {
                    echo $players[$i]['time_' . $level] . ",";
                }
            }
                        
            for ($j = 1; $j <= 9; $j += 1) {
                foreach ($levels as $index => $level) {
                    $progress = $common->getScoreByParam($level, 'sales' . $j, $players[$i]['sales'. $j . '_' . $level]);
                    echo $progress . "%" . ",";
                }
            }
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
        <?php include '../../' . $iSAIL_UI->assets_folder. '/css/pdfs/comparison_report.css'; ?>
        <?php echo '</style>'; ?>
        
        <page>
            <h1 class="text-center">Player Comparison Report</h1>
            <h4 class="text-left remove-margin-t push-10-b">
                <table>
                    <tr>
                        <td class="text-right padding-10-r remove-border">vs Levels:</td>
                        <td class="text-primary remove-border"><em><?php echo $vs_levels; ?></em></td>
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
            <table border="1" class="full-width">
                <col class="w-pt-3">
                <col class="w-pt-10">
                <?php
                $percent = 87 / ((10 - $start_index) * $level_count);
                for ($i = 0; $i < 10; $i += 1) {
                    for ($j = 0; $j < $level_count; $j += 1) {
                        echo '<col style="width:' . $percent . '%">';
                    }
                }
                ?>
                <thead>
                    <tr>
                        <th rowspan="3" class="default-label">No</th>
                        <th rowspan="3" class="default-label">Player</th>
                        <?php if ($show_time == 1) { ?>
                        <th rowspan="2" colspan="<?php echo $level_count; ?>" class="default-label">Times</th>
                        <?php } ?>
                        <th colspan="<?php echo ($level_count * 3); ?>" class="sales-group-label"><?php echo strtoupper($common->getSalesLabels('sales_group_1', 'label')); ?></th>
                        <th colspan="<?php echo ($level_count * 3); ?>" class="sales-group-label"><?php echo strtoupper($common->getSalesLabels('sales_group_2', 'label')); ?></th>
                        <th colspan="<?php echo ($level_count * 3); ?>" class="sales-group-label"><?php echo strtoupper($common->getSalesLabels('sales_group_3', 'label')); ?></th>
                    </tr>                       
                    <tr>
                        <th colspan="<?php echo $level_count; ?>" class="sales-label"><?php echo $common->getSalesLabels('sales1', 'short_label'); ?></th>
                        <th colspan="<?php echo $level_count; ?>" class="sales-label"><?php echo $common->getSalesLabels('sales2', 'short_label'); ?></th>
                        <th colspan="<?php echo $level_count; ?>" class="sales-label"><?php echo $common->getSalesLabels('sales3', 'short_label'); ?></th>
                        <th colspan="<?php echo $level_count; ?>" class="sales-label"><?php echo $common->getSalesLabels('sales4', 'short_label'); ?></th>
                        <th colspan="<?php echo $level_count; ?>" class="sales-label"><?php echo $common->getSalesLabels('sales5', 'short_label'); ?></th>
                        <th colspan="<?php echo $level_count; ?>" class="sales-label"><?php echo $common->getSalesLabels('sales6', 'short_label'); ?></th>
                        <th colspan="<?php echo $level_count; ?>" class="sales-label"><?php echo $common->getSalesLabels('sales7', 'short_label'); ?></th>
                        <th colspan="<?php echo $level_count; ?>" class="sales-label"><?php echo $common->getSalesLabels('sales8', 'short_label'); ?></th>
                        <th colspan="<?php echo $level_count; ?>" class="sales-label"><?php echo $common->getSalesLabels('sales9', 'short_label'); ?></th>
                    </tr>
                    <tr>
                        <?php 
                            for ($i = $start_index; $i < 10; $i += 1) {
                                for ($j = 0; $j < $level_count; $j += 1) {
                                    ?>
                                    <th class="level-label <?php if ($j == 0) echo 'border-3-l'; ?>">
                                        <?php echo $levels[$j]; ?>
                                    </th>
                                    <?php
                                }
                            }
                        ?>
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
                        <!-- time -->
                        <?php
                            if ($show_time == 1) {
                                foreach ($levels as $level) {
                                    echo '<td class="text-center">';
                                    echo $players[$i]['time_' . $level];
                                    echo '</td>';
                                }
                            }
                        ?>
                        <?php
                        for ($j = 1; $j <= 9; $j += 1) {
                            foreach ($levels as $index => $level) {
                                $progress = $common->getScoreByParam($level, 'sales' . $j, $players[$i]['sales'. $j . '_' . $level]);
                                ?>
                                <td class="text-center text-white <?php echo $common->getProgressClassName($progress) ?> <?php if ($index == 0) echo 'border-3-l'; ?>">
                                    <?php echo $progress; ?>%
                                </td>
                                <?php
                            }
                        }
                        ?>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </page>
        <?php
        $content = ob_get_contents();
        ob_end_clean();
        $pdf->generatePDF($content, 'L', 'CR');
        
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
                    <i class="fa fa-dashboard text-primary"></i>
                </span>
                Player Comparison Report
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
                        <td class="text-right padding-10-r text-normal">vs LEVELs:</td>
                        <td><small><?php echo $vs_levels; ?></small></td>
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
                            <th rowspan="3" class="text-center">&#8470;</th>
                            <th rowspan="3" class="text-center">Player</th>
                            <?php if ($show_time == 1) { ?>
                            <th rowspan="2" class="text-center" colspan="<?php echo $level_count; ?>" data-toggle="tooltip" data-placement="top" title="Time in Level, Seconds">Times</th>
                            <?php } ?>
                            <th colspan="<?php echo ($level_count * 3); ?>" class="text-center sales-group-label"><?php echo strtoupper($common->getSalesLabels('sales_group_1', 'label')); ?></th>
                            <th colspan="<?php echo ($level_count * 3); ?>" class="text-center sales-group-label"><?php echo strtoupper($common->getSalesLabels('sales_group_2', 'label')); ?></th>
                            <th colspan="<?php echo ($level_count * 3); ?>" class="text-center sales-group-label"><?php echo strtoupper($common->getSalesLabels('sales_group_3', 'label')); ?></th>
                        </tr>                       
                        <tr>
                            <th class="text-center sales-label" colspan="<?php echo $level_count?>" data-toggle="tooltip" data-placement="top" title="<?php echo $common->getSalesLabels('sales1', 'label'); ?>"><?php echo $common->getSalesLabels('sales1', 'short_label'); ?></th>
                            <th class="text-center sales-label" colspan="<?php echo $level_count?>" data-toggle="tooltip" data-placement="top" title="<?php echo $common->getSalesLabels('sales2', 'label'); ?>"><?php echo $common->getSalesLabels('sales2', 'short_label'); ?></th>
                            <th class="text-center sales-label" colspan="<?php echo $level_count?>" data-toggle="tooltip" data-placement="top" title="<?php echo $common->getSalesLabels('sales3', 'label'); ?>"><?php echo $common->getSalesLabels('sales3', 'short_label'); ?></th>
                            <th class="text-center sales-label" colspan="<?php echo $level_count?>" data-toggle="tooltip" data-placement="top" title="<?php echo $common->getSalesLabels('sales4', 'label'); ?>"><?php echo $common->getSalesLabels('sales4', 'short_label'); ?></th>
                            <th class="text-center sales-label" colspan="<?php echo $level_count?>" data-toggle="tooltip" data-placement="top" title="<?php echo $common->getSalesLabels('sales5', 'label'); ?>"><?php echo $common->getSalesLabels('sales5', 'short_label'); ?></th>
                            <th class="text-center sales-label" colspan="<?php echo $level_count?>" data-toggle="tooltip" data-placement="top" title="<?php echo $common->getSalesLabels('sales6', 'label'); ?>"><?php echo $common->getSalesLabels('sales6', 'short_label'); ?></th>
                            <th class="text-center sales-label" colspan="<?php echo $level_count?>" data-toggle="tooltip" data-placement="top" title="<?php echo $common->getSalesLabels('sales7', 'label'); ?>"><?php echo $common->getSalesLabels('sales7', 'short_label'); ?></th>
                            <th class="text-center sales-label" colspan="<?php echo $level_count?>" data-toggle="tooltip" data-placement="top" title="<?php echo $common->getSalesLabels('sales8', 'label'); ?>"><?php echo $common->getSalesLabels('sales8', 'short_label'); ?></th>
                            <th class="text-center sales-label" colspan="<?php echo $level_count?>" data-toggle="tooltip" data-placement="top" title="<?php echo $common->getSalesLabels('sales9', 'label'); ?>"><?php echo $common->getSalesLabels('sales9', 'short_label'); ?></th>
                        </tr>
                        <tr>
                            <?php 
                            for ($i = $start_index; $i < 10; $i += 1) {
                                for ($j = 0; $j < $level_count; $j += 1) {
                                    ?>
                                    <th class="text-center level-label <?php if ($j == 0) echo 'border-3-l'; ?>" data-toggle="tooltip" data-placement="top" title="Level - <?php echo $levels[$j]; ?>">
                                        <?php echo $levels[$j]; ?>
                                    </th>
                                    <?php
                                }
                            }
                            ?>
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
                            <!-- time -->
                            <?php
                                if ($show_time == 1) {
                                    foreach ($levels as $level) {
                                        echo '<td class="text-center">';
                                        echo $players[$i]['time_' . $level];
                                        echo '</td>';
                                    }
                                }
                            ?>
                            <?php
                            for ($j = 1; $j <= 9; $j += 1) {
                                foreach ($levels as $index => $level) {
                                    $progress = $common->getScoreByParam($level, 'sales' . $j, $players[$i]['sales'. $j . '_' . $level]);
                                    ?>
                                    <td class="text-center text-white <?php echo $common->getProgressClassName($progress); ?> <?php if ($index == 0) echo 'border-3-l'; ?>">
                                        <?php echo $progress; ?>%
                                    </td>
                                    <?php
                                }
                            }
                            ?>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="row push-10 remove-margin-l remove-margin-r">
                <div class="text-left push-10-t font-w700">
                    <span class="text-primary fa-pull-left push-10-r"><i class="fa fa-info-circle"></i> Legend : </span>
                    <span class="text-danger cursor-pointer" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $common->getLegendColor('foundation'); ?> <?php echo $common->getLegendScoreRange('foundation'); ?>"><?php echo $common->getLegendLabel('foundation'); ?>, </span>
                    <span class="text-warning cursor-pointer" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $common->getLegendColor('deep'); ?> <?php echo $common->getLegendScoreRange('deep'); ?>"><?php echo $common->getLegendLabel('deep'); ?>, </span>
                    <span class="text-success cursor-pointer" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $common->getLegendColor('advanced'); ?> <?php echo $common->getLegendScoreRange('advanced'); ?>"><?php echo $common->getLegendLabel('advanced'); ?></span>
                </div>
                <div class="text-right">
                    <button class="btn btn-default btn-back push-m40-t" type="button">Back</button>
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