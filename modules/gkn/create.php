<?php 
    require '../../inc/config.php';

    $common->authorizePage("gkn_create");

    $iSAIL_UI->title = $common->getPrefixProjectName() . ' ' . 'Create Game Key Number';

    $data = $_POST;
    
    if ($common->role == 3 || !isset($data['submit'])) {
        header('Location: /index.php');
        exit;
    }

    $fields = array();
    if (isset($data['customer_first_name']) && !empty($data['customer_first_name'])) {
        $fields[] = array('key' => 'first_name', 'value' => mysqli_real_escape_string($common->db_connect, $data['customer_first_name']), 'operator' => 'LIKE');
    }

    if (isset($data['customer_last_name']) && !empty($data['customer_last_name'])) {
        $fields[] = array('key' => 'last_name', 'value' => mysqli_real_escape_string($common->db_connect, $data['customer_last_name']), 'operator' => 'LIKE');
    }

    if (isset($data['unix_id']) && !empty($data['unix_id'])) {
        $ids = explode(',', $data['unix_id']);
        for ($i = 0; $i < count($ids); $i += 1) {
            $ids[$i] = "'" . mysqli_real_escape_string($common->db_connect, $ids[$i]) . "'";
        }
        $fields[] = array('key' => 'userid', 'value' => $ids, 'operator' => 'IN');
    }

    $query = $common->generateQueryForGetUsersByField($fields);

    $result = mysqli_query($common->db_connect, $query);

    $users = array();

    while ($row = mysqli_fetch_array($result)) {
        $users[] = $row;
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
                    <i class="fa fa-key text-primary"></i>
                </span>
                Create Game Key Number
            </h1>
        </div>
    </div>
</div>
<!-- END Page Header -->

<!-- Page Content -->
<div class="content">
    <!-- Dynamic Table Full -->
    <div class="block">
        <div class="block-content">
            <div class="row">
                <!-- Info Alert -->
                <div class="col-sm-12">
                    <div class="alert alert-info alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <p><a class="alert-link" href="javascript:void(0)"><i class="fa fa-info-circle"></i> Please select user to assign Game Key Number.</a></p>
                    </div>
                </div>
                <!-- END Info Alert -->
            </div>
            
            <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/gkn_pages.js -->
            <table class="table table-bordered table-striped js-dataTable-full table-header-bg table-vcenter display" id="userTable">
                <thead>
                    <tr>
                        <th class="text-center w-pt-25">Game Key Number</th>
                        <th class="text-center">UNIX_ID</th>
                        <th class="text-center">Name</th>
                        <th class="text-center w-pt-25">Email</th>
                        <th class="text-center">BU</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) {  ?>
                        <tr class="cursor-pointer">
                            <td class="text-center game-key-number"><?php echo htmlspecialchars($user['gkn']); ?></td>
                            <td class="text-center user-id"><?php echo $user['userid']; ?></td>
                            <td class="text-center"><?php echo $user['first_name'] . " " . $user['last_name']; ?></td>
                            <td class="text-center"><?php echo $user['email']; ?></td>
                            <td class="text-center"><?php echo $user['org_ID']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>           
            
            <div class="row push-10 push-5-t remove-margin-l remove-margin-r">
                <div class="text-right">
                    <button class="btn btn-default btn-back" type="button">Back</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END Dynamic Table Full -->

</div>
<!-- END Page Content -->

<!-- Game Key Number Modal -->
<div class="modal fade" id="gkn-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout">
        <div class="modal-content">
            <div class="block block-themed block-transparent remove-margin-b">
                <div class="block-header bg-primary">
                    <ul class="block-options">
                        <li>
                            <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title">Assign Game Key Number</h3>
                </div>
                <div class="block-content">
                    <form class="form-horizontal push-10-t" id="gknForm" method="post">
                        <!-- Info Alert -->
                        <div class="form-group">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="alert alert-info alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <p>
                                            You can use <a class="alert-link" href="https://passwordsgenerator.net/" target="_blank"><em>this online tool</em></a> to generate Game Key Number.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-1"></div>
                        </div>
                        <!-- END Info Alert -->

                        <!-- Duplicated GKN Alert -->
                        <div class="form-group d-none" id="duplicated-gkn-alert">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="alert alert-danger">
                                        <p>
                                            <a class="alert-link" href="javascript:void(0)"></a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-1"></div>
                        </div>
                        <!-- END Duplicated GKN Alert -->

                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                <div class="form-material">
                                    <input class="form-control" type="text" name="user_id" readonly />
                                    <label class="text-uppercase">UNIX_ID <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-sm-3"></div>
                        </div>                
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                <div class="form-material">
                                    <input class="form-control" type="text" name="user_name" readonly />
                                    <label class="text-uppercase">User Name <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-sm-3"></div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                <div class="form-material">
                                    <input class="form-control" type="text" name="gkn" maxlength="24" required/>
                                    <label class="text-uppercase">Game Key Number <span class="text-danger">*</span> <i class="fa fa-question-circle v-center" data-toggle="tooltip" data-placement="right" data-original-title="Alpanumeric, and Max length = 24"></i></label>
                                </div>
                            </div>
                            <div class="col-sm-3"></div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                <div class="form-material">
                                    <label class="css-input css-checkbox css-checkbox-sm css-checkbox-primary">
                                        <input type="checkbox" name="is_email" /><span></span> I want to send this GKN via email
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-3"></div>
                        </div>
                        <div class="form-group email-block d-none">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                <div class="form-material push-25-t">
                                    <input class="form-control" type="email" name="user_email" value="" />
                                    <label class="text-uppercase">User E-mail</label>
                                </div>
                            </div>
                            <div class="col-sm-3"></div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-default" type="button" id="cancel-gkn">Cancel</button>
                <button class="btn btn-sm btn-primary" type="button" id="save-gkn"><i class="fa fa-check"></i> Assign Game Key Number</button>
            </div>
        </div>
    </div>
</div>
<!-- END Game Key Number Modal -->

<?php require '../../inc/views/base_footer.php'; ?>
<?php require '../../inc/views/template_footer_start.php'; ?>

<!-- Page JS Plugins -->
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>

<!-- Page JS Code -->
<script src="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/js/pages/gkn_pages.js"></script>
<script>
    $(document).ready(function() {
        var userTable = $('#userTable').DataTable();

        var targetElement = null;

        $('#userTable tbody').on( 'click', 'tr', function () {
            if ( !$(this).hasClass('selected') ) {
                userTable.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
            
            var selectedUser = $(this).find("td.user-id").html();

            targetElement = this;

            $.ajax({
                url:        "/apis/gkn.php",
                dataType:   "json",
                type:       "post",
                data:       {
                                action_name:    "get_user_gkn",
                                user_id:        selectedUser
                            },
                success:    function( data ) {
                                if (!data.status) {
                                    targetElement = null;
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
                                    if (data.user.gkn == null || data.user.gkn == "NULL" || data.user.gkn == "") {
                                        $("input[name='user_id']").val(data.user.userid);
                                        $("input[name='user_name']").val(data.user.first_name + ' ' + data.user.last_name);
                                        $("input[name='gkn']").val("");
                                        $("input[name='user_email']").val(data.user.email);
                                        $("input[name='is_email']").prop("checked", false);
                                        if (!$(".email-block").hasClass('d-none')) {
                                            $(".email-block").addClass('d-none');
                                        };

                                        if (!$("#duplicated-gkn-alert").hasClass('d-none')) {
                                            $("#duplicated-gkn-alert").addClass('d-none');    
                                        }
                                        
                                        $("#gkn-modal").modal({backdrop: 'static'});
                                    }
                                    
                                }
                            }
            });
            
        } );

        $("#cancel-gkn").click(function() {
            targetElement = null;

            $("#gkn-modal").modal('hide');
        });

        $("#save-gkn").click(function() {
            var user_id     = $("input[name='user_id']").val();
            var user_name   = $("input[name='user_name']").val();
            var gkn         = $("input[name='gkn']").val();
            var user_email  = $("input[name='user_email']").val();
            var is_email    = $("input[name='is_email']").prop('checked') ? true : false;

            if (user_id == undefined || user_id == "") {
                alert("Please reload this page and re-try!");
            } else {
                if (gkn == undefined || gkn == "") {
                    alert("You must enter game key number!");
                    $("input[name='gkn']").focus();
                    return false;
                }

                if (is_email && (user_email == null || user_email == undefined || user_email == "")) {
                    alert("You must enter user email!");
                    $("input[name='user_email']").focus();
                    return false;
                }

                $.ajax({
                    url:        "/apis/gkn.php",
                    dataType:   "json",
                    type:       "post",
                    data:       {
                                    action_name:    "save_user_gkn",
                                    user_id:        user_id,
                                    user_name:      user_name,
                                    gkn:            gkn,
                                    is_email:       is_email ? 1 : 0,
                                    user_email:     user_email
                                },
                    success:    function( data ) {
                                    if (!data.status) {
                                        if ($("#duplicated-gkn-alert").hasClass('d-none')) {
                                            $("#duplicated-gkn-alert").removeClass('d-none');
                                            $("#duplicated-gkn-alert .alert-link").html(data.msg);
                                        } else {
                                            $("#duplicated-gkn-alert .alert-link").html(data.msg);
                                        }
                                    } else {
                                        $("input[name='user_id']").val("");
                                        $("input[name='user_name']").val("");
                                        $("input[name='gkn']").val("");
                                        $("input[name='user_email']").val("");

                                        if (!$("#duplicated-gkn-alert").hasClass('d-none')) {
                                            $("#duplicated-gkn-alert").addClass('d-none');
                                        }

                                        $("#gkn-modal").modal('hide');

                                        $(targetElement).find("td.game-key-number").text(data.gkn);

                                        targetElement = null;

                                        $.notify({
                                            icon:               'fa fa-check',
                                            message:            data.msg,
                                            url:                ''
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
                                            delay:              5000,
                                            timer:              1000,
                                            animate:            {
                                                                    enter:  'animated fadeIn',
                                                                    exit:   'animated fadeOutDown'
                                                                }
                                        });
                                    }
                                }
                });
            }
        });

        $("input[name='is_email']").click(function() {
            if ($(this).prop('checked')) {
                $(".email-block").removeClass('d-none');
            } else {
                $(".email-block").addClass('d-none');
            }
        });

        $("button.btn-back").click(function() {
            window.location = "create_criteria.php";
        });
    });
</script>

<?php require '../../inc/views/template_footer_end.php'; ?>