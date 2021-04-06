<?php 
    require '../../inc/config.php';

    $common->authorizePage("class_create");

    $iSAIL_UI->title = $common->getPrefixProjectName() . ' ' . 'Create Class - Criteria';

    $organization = '';
    $franchise = '';
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
                    <i class="fa fa-users text-primary"></i>
                </span>
                Create Class
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
                    <div class="row">
                        <!-- Info Alert -->
                        <div class="col-sm-12">
                            <div class="alert alert-info alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <p><a class="alert-link" href="javascript:void(0)"><i class="fa fa-info-circle"></i> For each search criteria, please leave Blank to select <em>'ALL'</em>.</a></p>
                            </div>
                        </div>
                        <!-- END Info Alert -->
                    </div>
                    <form class="form-horizontal push-10-t" action="create_criteria_result.php" method="post" id="criteriaForm">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="form-material floating">
                                    <select class="form-control" id="organization" name="organization" size="1">
                                        <option <?php if ($organization == "") { ?>selected<?php } ?>></option><!-- Empty value for demostrating material select box -->
                                        <?php
                                            $fields = array(
                                                            array('key' => 'org_ID', 'value' => NULL,   'operator' => 'IS NOT'),
                                                            array('key' => 'org_ID', 'value' => '',     'operator' => '!=')
                                                        );
                                            $query = $common->generateQueryForGetItemFromUsers('org_ID', 'organization', $fields);
                                            $result = mysqli_query($common->db_connect, $query);
                                            
                                            while ($row = mysqli_fetch_array($result)) {
                                        ?>
                                        <option value="<?php echo $row['organization']; ?>" <?php if ($organization == $row['organization']) { ?>selected<?php } ?>><?php echo $row['organization']; ?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                    <label for="organization">Please Select BU</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-material floating">
                                    <select class="form-control" id="franchise" name="franchise" size="1">
                                        <option <?php if ($franchise == "") { ?>selected<?php } ?>></option><!-- Empty value for demostrating material select box -->
                                        <?php
                                            $fields = array(
                                                        array('key' => 'franchise', 'value' => NULL,    'operator' => 'IS NOT'),
                                                        array('key' => 'franchise', 'value' => '',      'operator' => '!=')
                                                    );
                                            $query = $common->generateQueryForGetItemFromUsers('franchise', 'franchise', $fields);
                                            $result = mysqli_query($common->db_connect, $query);
                                            
                                            while ($row = mysqli_fetch_array($result)) {
                                        ?>
                                        <option value="<?php echo $row['franchise']; ?>" <?php if ($franchise == $row['franchise']) { ?>selected<?php } ?>><?php echo $row['franchise']; ?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                    <label for="franchise">Please Select Franchise</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="form-material">
                                    <label for="manager_first_name">Manager</label>
                                    <input type="text" name="manager_first_name" class="form-control" placeholder="First Name" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-material">
                                    <input type="text" name="manager_last_name" class="form-control" placeholder="Last Name" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="form-material">
                                    <label for="customer_first_name">Customer Specialist</label>
                                    <input type="text" name="customer_first_name" class="form-control" placeholder="First Name" />                                    
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-material">
                                    <input type="text" name="customer_last_name" class="form-control" placeholder="Last Name" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="form-material">
                                    <label>UNIX_ID</label>
                                    <input type="text" name="unix_id" class="form-control" placeholder="multiple separated by a comma" />                                    
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="css-input switch switch-sm switch-primary">
                                    <input type="checkbox" id="criteria-remember" name="criteria-remember" value="1"><span></span> Remember Criteria?
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button class="btn btn-sm btn-primary" type="submit" name="submit">Search</button>
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

<?php require '../../inc/views/base_footer.php'; ?>
<?php require '../../inc/views/template_footer_start.php'; ?>
<script>
    $(document).ready(function() {
        $("#clear-criteria-button").click(function() {
            $("#organization").val("");
            $("#organization").trigger('change');
            $("#franchise").val("");
            $("#franchise").trigger('change');

            $('[name="manager_first_name"]').val('');
            $('[name="manager_last_name"]').val('');

            $('[name="customer_first_name"]').val('');
            $('[name="customer_last_name"]').val('');


            $('[name="unix_id"]').val('');

            $("#criteria-remember").prop('checked', false);
        });

        // save form
        // try to load values
        var session = window.localStorage.getItem('class_create_form');
        session = session ? JSON.parse(session) : [];
        for (let i = 0; i < session.length; i += 1) {
            var input = $('[name="' + session[i].name + '"]');
            if (input.attr('type') == 'checkbox') {
                input.prop('checked', true);
            } else {
                input.val(session[i].value);
                input.trigger('change');
            }
        }

        $('#criteriaForm').submit(function () {
            if ($('#criteria-remember').is(':checked')) {
                window.localStorage.setItem('class_create_form', JSON.stringify($(this).serializeArray()));
            } else {
                window.localStorage.removeItem('class_create_form');
            }
        });
    });
</script>
<?php require '../../inc/views/template_footer_end.php'; ?>