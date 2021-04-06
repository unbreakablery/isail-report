<?php 
    require '../../inc/config.php'; 
    
    $common->authorizePage("progression_report");

    $iSAIL_UI->title = $common->getPrefixProjectName() . ' ' . 'Progression Report - Criteria';

    //Get users who played the game session
    $query = $common->generateQueryForSessionPlayer();
    $result = mysqli_query($common->db_connect, $query);

    $users = array();
    if ($result === FALSE) {
        die(mysqli_error($common->db_connect));
    } else {
        while ($row = mysqli_fetch_array($result)) {
            $users[] = $row;
        }
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
    <!-- Material Design -->
    <div class="row">
        <div class="col-sm-12">
            <div class="block">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Criteria</h3>
                </div>

                <div class="block-content block-content-narrow">
                    <form class="form-horizontal push-10-t" action="report.php" method="post" id="reportForm">
                        <div class="form-group">
                            <div class="col-sm-6 push-8-t">
                                <div class="form-material floating">
                                    <input type="hidden" name="player_name" value="" />
                        
                                    <select class="form-control" id="player" name="player" size="1">
                                        <option value=""></option>
                                        <?php foreach ($users as $user) { ?>
                                        <option value="<?php echo $user['userid']; ?>"><?php echo $user['username']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <label for="player">Please Select Player for Report:</label>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <label class="col-sm-12">What Levels do You Want to Compare? </label>
                                <div class="col-sm-12">
                                    <label class="css-input css-checkbox css-checkbox-primary">
                                        <input type="checkbox" name="level_1" value="level_1"><span></span> Level 1
                                    </label>
                                </div>
                                <div class="col-sm-12">
                                    <label class="css-input css-checkbox css-checkbox-primary">
                                        <input type="checkbox" name="level_2" value="level_2"><span></span> Level 2
                                    </label>
                                </div>
                                <div class="col-sm-12">
                                    <label class="css-input css-checkbox css-checkbox-primary">
                                        <input type="checkbox" name="level_3" value="level_3"><span></span> Level 3
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12">What <?php echo $common->getModelsShortLabel(); ?> element do You Want to Report? </label>
                            <div class="col-sm-12">
                                <label class="css-input css-radio css-radio-primary push-10-r">
                                    <input type="radio" name="model-element" value="sales_group_1"><span></span> <?php echo strtoupper($common->getSalesLabels('sales_group_1', 'label')); ?> (Average of three scores below)
                                </label>
                            </div>
                            <div class="col-sm-12 push-25-l">
                                <label class="css-input css-radio css-radio-primary">
                                    <input type="radio" name="model-element" value="sales1"><span></span> <?php echo $common->getSalesLabels('sales1', 'short_label'); ?>: <?php echo $common->getSalesLabels('sales1', 'label'); ?> 
                                </label>
                            </div>
                            <div class="col-sm-12 push-25-l">
                                <label class="css-input css-radio css-radio-primary">
                                    <input type="radio" name="model-element" value="sales2"><span></span> <?php echo $common->getSalesLabels('sales2', 'short_label'); ?>: <?php echo $common->getSalesLabels('sales2', 'label'); ?> 
                                </label>
                            </div>
                            <div class="col-sm-12 push-25-l">
                                <label class="css-input css-radio css-radio-primary">
                                    <input type="radio" name="model-element" value="sales3"><span></span> <?php echo $common->getSalesLabels('sales3', 'short_label'); ?>: <?php echo $common->getSalesLabels('sales3', 'label'); ?> 
                                </label>
                            </div>
                            <div class="col-sm-12">
                                <label class="css-input css-radio css-radio-primary">
                                    <input type="radio" name="model-element" value="sales_group_2"><span></span> <?php echo strtoupper($common->getSalesLabels('sales_group_2', 'label')); ?> (Average of three scores below)
                                </label>
                            </div>
                            <div class="col-sm-12 push-25-l">
                                <label class="css-input css-radio css-radio-primary">
                                    <input type="radio" name="model-element" value="sales4"><span></span> <?php echo $common->getSalesLabels('sales4', 'short_label'); ?>: <?php echo $common->getSalesLabels('sales4', 'label'); ?> 
                                </label>
                            </div>
                            <div class="col-sm-12 push-25-l">
                                <label class="css-input css-radio css-radio-primary">
                                    <input type="radio" name="model-element" value="sales5"><span></span> <?php echo $common->getSalesLabels('sales5', 'short_label'); ?>: <?php echo $common->getSalesLabels('sales5', 'label'); ?> 
                                </label>
                            </div>
                            <div class="col-sm-12 push-25-l">
                                <label class="css-input css-radio css-radio-primary">
                                    <input type="radio" name="model-element" value="sales6"><span></span> <?php echo $common->getSalesLabels('sales6', 'short_label'); ?>: <?php echo $common->getSalesLabels('sales6', 'label'); ?> 
                                </label>
                            </div>
                            <div class="col-sm-12">
                                <label class="css-input css-radio css-radio-primary">
                                    <input type="radio" name="model-element" value="sales_group_3"><span></span> <?php echo strtoupper($common->getSalesLabels('sales_group_3', 'label')); ?> (Average of three scores below)
                                </label>
                            </div>
                            <div class="col-sm-12 push-25-l">
                                <label class="css-input css-radio css-radio-primary">
                                    <input type="radio" name="model-element" value="sales7"><span></span> <?php echo $common->getSalesLabels('sales7', 'short_label'); ?>: <?php echo $common->getSalesLabels('sales7', 'label'); ?> 
                                </label>
                            </div>
                            <div class="col-sm-12 push-25-l">
                                <label class="css-input css-radio css-radio-primary">
                                    <input type="radio" name="model-element" value="sales8"><span></span> <?php echo $common->getSalesLabels('sales8', 'short_label'); ?>: <?php echo $common->getSalesLabels('sales8', 'label'); ?> 
                                </label>
                            </div>
                            <div class="col-sm-12 push-25-l">
                                <label class="css-input css-radio css-radio-primary">
                                    <input type="radio" name="model-element" value="sales9"><span></span> <?php echo $common->getSalesLabels('sales9', 'short_label'); ?>: <?php echo $common->getSalesLabels('sales9', 'label'); ?> 
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <label class="css-input switch switch-sm switch-primary">
                                    <input type="checkbox" id="criteria-remember" name="criteria-remember" value="1"><span></span> Remember Criteria?
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button class="btn btn-sm btn-primary" type="submit">View Report</button>
                                <button class="btn btn-sm btn-default" type="button" id="clear-criteria-button">Clear Criteria</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END Material Design -->
</div>
<!-- END Page Content -->

<?php require '../../inc/views/modal_note.php'; ?>

<?php require '../../inc/views/base_footer.php'; ?>
<?php require '../../inc/views/template_footer_start.php'; ?>

<script>
    $(document).ready(function() {
        $("#clear-criteria-button").click(function() {
            $("#player").val('');
            for (i = 1; i <= 3; i++) {
                $('[name="level_' + i + '"]').prop('checked', false);
            }
            $("[name='model-element']").prop('checked', false);
            $("#criteria-remember").prop('checked', false);
        });

        $("#player").change(function() {
            $("input[name='player_name']").val($(this).find('option:selected').text());
        });

        $('#reportForm').submit(function() {
            var checkedLength = 0;
            
            if ($("#player").val() == '') {
                $("#note-modal-content").text("You must select a player to report.");
                $("#note-modal").modal({backdrop: 'static'});
                return false;
            }

            checkedLength += $('[name="level_1"]').is(':checked') ? 1 : 0;
            checkedLength += $('[name="level_2"]').is(':checked') ? 1 : 0;
            checkedLength += $('[name="level_3"]').is(':checked') ? 1 : 0;

            if (checkedLength < 2) {
                $("#note-modal-content").text("You must select 2 levels at least to compare.");
                $("#note-modal").modal({backdrop: 'static'});
                return false;
            }

            if (!$('[name="model-element"]').is(':checked')) {
                $("#note-modal-content").text("You must select an <?php echo $common->getModelsShortLabel(); ?> element to report.");
                $("#note-modal").modal({backdrop: 'static'});
                return false;
            }
            
            if ($('#criteria-remember').is(':checked')) {
                window.localStorage.setItem('progression_report_form', JSON.stringify($(this).serializeArray()));
            } else {
                window.localStorage.removeItem('progression_report_form');
            }
        });

        // save form
        // try to load values
        var session = window.localStorage.getItem('progression_report_form');
        session = session ? JSON.parse(session) : [];
        for (let i = 0; i < session.length; i += 1) {
            var input = $('[name="' + session[i].name + '"]');
            if (input.attr('type') == 'checkbox') {
                input.prop('checked', true);
            } else if (input.attr('type') == 'radio') {
                if (session[i].name == 'model-element') {
                    $('[name="model-element"]').each(function() {
                        if ($(this).val() == session[i].value) {
                            $(this).prop('checked', true);
                        }
                    });
                }
            } else {
                input.val(session[i].value);
                input.trigger('change');
            }
        }
    });
</script>
<?php require '../../inc/views/template_footer_end.php'; ?>