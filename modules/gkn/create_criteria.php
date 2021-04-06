<?php 
    require '../../inc/config.php';

    $common->authorizePage("gkn_create");

    $iSAIL_UI->title = $common->getPrefixProjectName() . ' ' . 'Create Game Key Number - Criteria';
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
                    <i class="fa fa-key text-primary"></i>
                </span>
                Create Game Key Number
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

                    <form class="form-horizontal push-10-t" action="create.php" method="post" id="reportForm">
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
                                    <label for="unix_id">UNIX_ID</label>
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
        // save form
        // try to load values
        var session = window.localStorage.getItem('gkn_create_form');
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
        
        $("#clear-criteria-button").click(function() {
            $('[name="customer_first_name"]').val('');
            $('[name="customer_last_name"]').val('');

            $('[name="unix_id"]').val('');

            $("#criteria-remember").prop('checked', false);
        });

        $('#reportForm').submit(function () {
            if ($('#criteria-remember').is(':checked')) {
                window.localStorage.setItem('gkn_create_form', JSON.stringify($(this).serializeArray()));
            } else {
                window.localStorage.removeItem('gkn_create_form');
            }
        });
    });
</script>
<?php require '../../inc/views/template_footer_end.php'; ?>