<?php 
    require '../../inc/config.php';

    $common->authorizePage("user_management");

    $iSAIL_UI->title = $common->getPrefixProjectName() . ' ' . 'Edit User';

    if (!isset($_REQUEST['id'])) {
        header('Location: management.php');
        exit;
    }
    
    //Get user data
    $id = $_REQUEST['id'];

    $query = $common->generateQueryForGetUsers($id);
    $result = mysqli_query($common->db_connect, $query);

    if ($result === FALSE) {
        header('Location: management.php');
        exit;
    }

    $user = mysqli_fetch_array($result);

    if ($user == NULL) {
        header('Location: management.php');
        exit;
    }

    //Get users for direct manager
    $query = $common->generateQueryForGetUsersForDM();
    $result = mysqli_query($common->db_connect, $query);

    $dms = array();
    while ($row = mysqli_fetch_array($result)) {
        $dms[] = $row;
    }

    //Get classes for class name
    $query = $common->generateQueryForGetClasses();
    $result = mysqli_query($common->db_connect, $query);

    $classes = array();
    while ($row = mysqli_fetch_array($result)) {
        $classes[] = $row;
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
                    <i class="si si-users text-primary"></i>
                </span>
                Edit User
            </h1>
        </div>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content">
    <div class="block">
        <div class="block-header">
            <h3 class="block-title text-primary pull-left">User Information</h3>
            <div class="text-right">
                <button class="btn btn-sm btn-default btn-back pull-right" type="button">Look up Users</button>
            </div>
        </div>
        <div class="block-content">
            <form class="form-horizontal push-10-t" id="userForm" method="post">
                <input type="hidden" name="action_name" value="update_user" />
                <input type="hidden" name="id" value="<?php echo $id; ?>" />

                <div class="form-group has-success">
                    <div class="col-sm-6">
                        <div class="form-material">
                            <input class="form-control" type="text" name="userid" placeholder="UNIX_ID" value="<?php echo $user['userid']; ?>" disabled/>
                            <label class="text-uppercase">UNIX_ID <span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <div class="form-material form-material-primary">
                            <input class="form-control" type="text" name="first_name" placeholder="First Name" value="<?php echo $user['first_name']; ?>" required />
                            <label class="text-uppercase">First Name <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-material form-material-primary">
                            <input class="form-control" type="text" name="last_name" placeholder="Last Name" value="<?php echo $user['last_name']; ?>" required />
                            <label class="text-uppercase">Last Name <span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <div class="form-material form-material-primary">
                            <input class="form-control" type="email" name="email" placeholder="example@domain.com" value="<?php echo $user['email']; ?>" />
                            <label class="text-uppercase">Email</label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-material form-material-primary">
                            <input class="form-control" type="text" name="password" placeholder="Password" value="<?php echo $user['password']; ?>" required />
                            <label class="text-uppercase">Password <span class="text-danger">*</span> - <a class="alert-link" href="https://passwordsgenerator.net/" target="_blank">[<em>Online Tool</em>]</a></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <div class="form-material form-material-primary">
                            <input class="form-control" type="number" name="role" placeholder="Role" value="<?php echo $user['role']; ?>" min="1" max="4" required />
                            <label class="text-uppercase">Role <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-material form-material-primary">
                            <input class="form-control" type="text" name="org_ID" placeholder="Organization" value="<?php echo $user['org_ID']; ?>" />
                            <label class="text-uppercase">Organization</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <div class="form-material form-material-primary">
                            <input class="form-control" type="text" name="franchise" placeholder="Franchise" value="<?php echo $user['franchise']; ?>" />
                            <label class="text-uppercase">Franchise</label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-material form-material-primary">
                            <input class="form-control" type="text" name="product" placeholder="Product" value="<?php echo $user['product']; ?>" />
                            <label class="text-uppercase">Product</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <div class="form-material form-material-primary">
                            <input class="form-control" type="text" name="indication" placeholder="Indication" value="<?php echo $user['indication']; ?>" />
                            <label class="text-uppercase">Indication</label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-material form-material-primary">
                            <input class="form-control" type="text" name="gkn" placeholder="Game Key Number : MaxLength is 24" value="<?php echo $user['gkn']; ?>" maxlength="24" />
                            <label class="text-uppercase">Game Key Number - <a class="alert-link" href="https://passwordsgenerator.net/" target="_blank">[<em>Online Tool</em>]</a></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <div class="form-material form-material-primary">
                            <select class="form-control" name="who_dm" size="1">
                                <option value=""></option>
                                <?php foreach ($dms as $dm) { ?>
                                <option value="<?php echo $dm['userid']; ?>" <?php if ($user['who_dm'] == $dm['userid']) { ?>selected<?php } ?>><?php echo $dm['username']; ?></option>
                                <?php } ?>
                            </select>
                            <label class="text-uppercase">Direct Manager</label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-material form-material-primary">
                            <select class="form-control" name="class_ID" size="1">
                                <option value="0"></option>
                                <?php foreach ($classes as $class) { ?>
                                <option value="<?php echo $class['class_ID']; ?>" <?php if ($user['class_ID'] == $class['class_ID']) { ?>selected<?php } ?>><?php echo $class['name']; ?></option>
                                <?php } ?>
                            </select>
                            <label class="text-uppercase">Class Name</label>
                        </div>
                    </div>
                </div>

                <div class="row push-20 remove-margin-l remove-margin-r">
                    <button class="btn btn-sm btn-primary pull-right" type="submit">Update User</button>
                    <button class="btn btn-sm btn-default btn-back pull-right push-5-r" type="button">Look up Users</button>
                </div>
            </form>            
        </div>
    </div>

</div>
<!-- END Page Content -->

<!-- Confirm Update Modal -->
<div class="modal fade" id="confirm-update-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout">
        <div class="modal-content">
            <div class="block block-themed block-transparent remove-margin-b">
                <div class="block-header bg-primary">
                    <ul class="block-options">
                        <li>
                            <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Confirm</h3>
                </div>
                <div class="block-content">
                    <div class="text-center text-default">
                        <p>
                            <i class="fa fa-question-circle"></i> 
                            Would you update this user information really?
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-sm btn-primary" type="button" data-dismiss="modal" id="update-yes-button"><i class="fa fa-check"></i> Yes</button>
            </div>
        </div>
    </div>
</div>
<!-- END Confirm Update Modal -->

<?php require '../../inc/views/base_footer.php'; ?>
<?php require '../../inc/views/template_footer_start.php'; ?>

<!-- Page JS Plugins -->
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>

<script>

    $(document).ready(function() {
        
        $("#userForm").submit(function(e) {
            e.preventDefault();
            
            $("#confirm-update-modal").modal({backdrop: 'static'});
        });

        $("button.btn-back").click(function() {
            window.location.href = "management.php";
        });

        $("button#update-yes-button").click(function() {
            
            $.ajax({
				url:        "/apis/users.php",
				dataType:   "json",
				type:       "post",
				data:       {
                                action_name: $("input[name='action_name']").val(),
                                id:          $("input[name='id']").val(),
                                first_name:  $("input[name='first_name']").val(),
                                last_name:   $("input[name='last_name']").val(),
                                email:       $("input[name='email']").val(),
                                password:    $("input[name='password']").val(),
                                role:        $("input[name='role']").val(),
                                org_ID:      $("input[name='org_ID']").val(),
                                franchise:   $("input[name='franchise']").val(),
                                product:     $("input[name='product']").val(),
                                indication:  $("input[name='indication']").val(),
                                gkn:         $("input[name='gkn']").val(),
                                who_dm:      $("select[name='who_dm']").val(),
                                class_ID:    $("select[name='class_ID']").val()
                            },
				success:    function( data ) {
                                if (!data.status) {
                                    $.notify({
                                        icon:               'fa fa-times',
                                        message:            data.msg + "<br/>" + data.error,
                                        url:                ''
                                    },
                                    {
                                        element:            'body',
                                        type:               'danger',
                                        allow_dismiss:      true,
                                        newest_on_top:      true,
                                        showProgressbar:    false,
                                        placement:          {
                                                                from:   'top',
                                                                align:  'center'
                                                            },
                                        offset:             20,
                                        spacing:            10,
                                        z_index:            1033,
                                        delay:              5000,
                                        timer:              1000,
                                        animate:            {
                                                                enter:  'animated fadeIn',
                                                                exit:   'animated fadeOutDown'
                                                            }
                                    });
                                } else {
                                    $.notify({
                                        icon:               'fa fa-check',
                                        message:            data.msg,
                                        url:                'management.php'
                                    },
                                    {
                                        element:            'body',
                                        type:               'success',
                                        allow_dismiss:      true,
                                        newest_on_top:      true,
                                        showProgressbar:    false,
                                        placement:          {
                                                                from:   'top',
                                                                align:  'center'
                                                            },
                                        offset:             20,
                                        spacing:            10,
                                        z_index:            1033,
                                        delay:              3000,
                                        timer:              1000,
                                        animate:            {
                                                                enter:  'animated fadeIn',
                                                                exit:   'animated fadeOutDown'
                                                            },
                                        onClosed:           function() { 
                                                                window.location.href = "management.php"; 
                                                            }
                                    });
                                }
                            }
			});
        });
    });
</script>

<?php require '../../inc/views/template_footer_end.php'; ?>