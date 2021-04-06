<?php 
    require '../../inc/config.php';

    $common->authorizePage("coaching_report");

    $iSAIL_UI->title = $common->getPrefixProjectName() . ' ' . 'Coaching Report';

    if (!isset($_POST) || empty($_POST)) {
        header("Location: criteria.php");
        exit;
    }

    $player_name        = $_POST['instance-username'];
    $level              = $_POST['instance-level'];
    $instance_label     = $_POST['instance-label'];

    $level_ID           = $_POST['level-instance'];

    $query = $common->generateQueryForGetConvs($level_ID);

    $result = mysqli_query($common->db_connect, $query);

    $conversations = array();
    while ($row = mysqli_fetch_array($result)) {
        $conversations[] = $row;
    }
?>
<?php require '../../inc/views/template_head_start.php'; ?>
<?php require '../../inc/views/template_head_end.php'; ?>
<?php require '../../inc/views/base_head.php'; ?>

<!-- jquery go top plugin css -->
<link rel="stylesheet" href="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/jquery-go-top/iconfont.css">

<div id="go-top" class="iconfont icon-cc-top"></div>

<!-- Page Header -->
<div class="content bg-gray-lighter">
    <div class="row items-push">
        <div class="col-sm-12">
            <h1 class="page-heading font-w700 text-primary">
                <span class="item item-circle bg-primary-lighter">
                    <i class="fa fa-sitemap text-primary"></i>
                </span>
                Detailed Coaching Report
            </h1>
        </div>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content content-boxed">
    
    <div class="block">
        <div class="block-content block-content-full block-content-narrow">
            <div class="row item-push">
                <div class="col-sm-8 text-left">
                    <h2 class="h4 font-w600 push">
                        <table>
                            <tr>
                                <td class="text-right padding-10-r">Player:</td>
                                <td><small class="text-player"><?php echo $player_name; ?></small></td>
                            </tr>
                            <tr>
                                <td class="text-right padding-10-r">Level:</td>
                                <td><small class="text-level"><?php echo $level; ?></small></td>
                            </tr>
                            <tr>
                                <td class="text-right padding-10-r">Instance:</td>
                                <td><small class="text-instance"><em><?php echo $instance_label; ?></em></small></td>
                            </tr>
                        </table>
                    </h2>
                </div>
                <div class="col-sm-4 text-right">
                    <button type="button" class="btn btn-default btn-back">Back</button>
                    <button type="button" class="btn btn-primary" id="view-all" value="0">Show All</button>
                </div>
            </div>

            <!-- Answers -->
            <div id="answers" class="panel-group">
                <?php
                    foreach ($conversations as $conv) {
                        $conv_ID = $conv['conversation_ID'];
                        $npc_number = $conv['npc_number'];

                        $fields = array(
                                        array('key' => 'level', 'value' => $level,      'operator' => '='),
                                        array('key' => 'actor', 'value' => $npc_number, 'operator' => '=')
                                    );
                        
                        $query = $common->generateQueryForGetDialogue($fields);

                        $result = mysqli_query($common->db_connect, $query);

                        while ($row = mysqli_fetch_array($result)) {
                            $node_ID = $row['node_ID'];
                            
                            //Get correct response
                            $fields = array(
                                            array('key' => 'level',         'value' => $level,      'operator' => '='),
                                            array('key' => 'conversant',    'value' => $npc_number, 'operator' => '='),
                                            array('key' => 'node_ID',       'value' => $node_ID,    'operator' => 'like'),
                                            array('key' => 'description',   'value' => 'Best',      'operator' => '=')
                                        );

                            $query = $common->generateQueryForGetDialogue($fields);

                            $correct_result = mysqli_query($common->db_connect, $query);
                            if (mysqli_num_rows($correct_result) == 1) {
                                $correct_response = mysqli_fetch_array($correct_result)['correct_response'];
                            } else {
                                $correct_response = '';
                            }
                            $correct_response_class = 'alert-success';

                            //Get all responses like Default, Better, Best
                            $fields = array(
                                            array('key' => 'level',         'value' => $level,      'operator' => '='),
                                            array('key' => 'conversant',    'value' => $npc_number, 'operator' => '='),
                                            array('key' => 'node_ID',       'value' => $node_ID,    'operator' => 'like')
                                        );

                            $query = $common->generateQueryForGetDialogue($fields, 'node_ID');

                            $dialog_result = mysqli_query($common->db_connect, $query);

                            while ($dialog = mysqli_fetch_array($dialog_result)) {
                                if ($dialog['description'] == "Best") {
                                    $learner_response_class = 'alert-success';
                                } else {
                                    $learner_response_class = 'alert-danger';
                                }

                                //Get Model params
                                $learner_params = explode(',', $dialog['param']);
                                $learner_param_names = array();
                                foreach ($learner_params as $param) {
                                    switch ($param) {
                                        case "1":
                                            array_push($learner_param_names, $common->getSalesLabels('sales1', 'label'));
                                            break;
                                        case "2":
                                            array_push($learner_param_names, $common->getSalesLabels('sales2', 'label'));
                                            break;
                                        case "3":
                                            array_push($learner_param_names, $common->getSalesLabels('sales3', 'label'));
                                            break;
                                        case "4":
                                            array_push($learner_param_names, $common->getSalesLabels('sales4', 'label'));
                                            break;
                                        case "5":
                                            array_push($learner_param_names, $common->getSalesLabels('sales5', 'label'));
                                            break;
                                        case "6":
                                            array_push($learner_param_names, $common->getSalesLabels('sales6', 'label'));
                                            break;
                                        case "7":
                                            array_push($learner_param_names, $common->getSalesLabels('sales7', 'label'));
                                            break;
                                        case "8":
                                            array_push($learner_param_names, $common->getSalesLabels('sales8', 'label'));
                                            break;
                                        case "9":
                                            array_push($learner_param_names, $common->getSalesLabels('sales9', 'label'));
                                            break;
                                    }
                                }

                ?>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#answers" href="#conversation_<?php echo $conv_ID; ?>_<?php echo $dialog['id']; ?>">
                                Interaction with <span class="font-w600 text-uppercase"><em><?php echo $conv['character_name']; ?></em></span> [CONV_ID: <?php echo $conv_ID?>, Title: <?php echo $dialog['node_ID']; ?>]
                            </a>
                        </h3>
                    </div>
                    <div id="conversation_<?php echo $conv_ID; ?>_<?php echo $dialog['id']; ?>" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="col-sm-7">
                                <div class="alert border">
                                    <p><?php echo $row['dialogue']; ?></p>
                                </div>
                                <div class="alert <?php echo $learner_response_class; ?>">
                                    <h4>Learner Response:</h4>
                                    <p><?php echo $dialog['dialogue']; ?></p>
                                </div>
                                <div class="alert <?php echo $correct_response_class; ?>">
                                    <h4>Correct Response:</h4>
                                    <p><?php echo $correct_response; ?></p>
                                </div>
                                <div class="alert border">
                                    <h4>Coaching Feedback:</h4>
                                    <p><?php echo $dialog['feedback']; ?></p>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="block block-themed border">
                                    <div class="block-header bg-primary">
                                        <h3 class="block-title font-w700">Rubric Level</h3>
                                    </div>
                                    <div class="block-content">
                                        <?php if (!empty($learner_param_names)) { ?>
                                            <ul class="fa-ul">
                                                <li>
                                                    <i class="fa fa-check fa-li"></i>
                                                    <span class="font-w600"><?php echo $common->getModelsShortLabel(); ?> Elements:</span>
                                                    <ul class="fa-ul">
                                                        <?php foreach ($learner_param_names as $param_name) { ?>
                                                            <li>
                                                                <i class="fa fa-angle-right fa-li"></i>
                                                                <?php echo $param_name; ?>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                </li>
                                            </ul>
                                        <?php } ?>
                                        <?php if ($dialog['rubric'] != "") { ?>
                                            <ul class="fa-ul">
                                                <li>
                                                    <i class="fa fa-check fa-li"></i>
                                                    <span class="font-w600">Learner Rubric Level:</span>
                                                    <ul class="fa-ul">
                                                        <li>
                                                            <i class="fa fa-angle-right fa-li"></i>
                                                            <?php echo $dialog['rubric']; ?>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </div>
            <!-- END Answers -->

        </div>
    </div>
    
</div>
<!-- END Page Content -->

<?php require '../../inc/views/base_footer.php'; ?>
<?php require '../../inc/views/template_footer_start.php'; ?>

<!-- Page Plugin JS -->
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/jquery-go-top/jquery.goTop.js"></script>

<script>
    $(document).ready(function() {
        $("#view-all").click(function() {
            var option = $(this).val();
            var method = (option == '0') ? 'show' : 'hide';
            var value = (option == '0') ? '1' : '0';
            var label = (option == '0') ? 'Hide All' : 'Show All';

            if (method == 'show') {
                $('#answers .accordion-toggle.collapsed').removeClass('collapsed');
                $('#answers .panel-collapse').removeAttr("style");
                $('#answers .panel-collapse:not(".in")').addClass('in');
            } else if (method == 'hide') {
                $('#answers .accordion-toggle.collapse').removeClass('collapse');
                $('#answers .panel-collapse').removeAttr("style");
                $('#answers .panel-collapse.in').removeClass('in');
            }

            $(this).val(value);
            $(this).html(label);
            
        });

        $("button.btn-back").click(function() {
            window.location.href = "criteria.php";
        });

        $('#go-top').goTop({
            scrollTop:      100,
            scrollSpeed:    1000,
            fadeInSpeed:    1000,
            fadeOutSpeed:   500
        });
    });
</script>
<?php require '../../inc/views/template_footer_end.php'; ?>