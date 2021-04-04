<?php
/**
 * template_head_end.php
 *
 * Author: Anna
 *
 * (continue) The first block of code used in every page of the template
 *
 * The reason we separated template_head_start.php and template_head_end.php
 * is for enabling us to include between them extra plugin CSS files needed only in
 * specific pages
 *
 */
?>

    <!-- Bootstrap and iSAIL_UI CSS framework -->
    <link rel="stylesheet" href="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/css/bootstrap.min.css">
    <link rel="stylesheet" id="css-main" href="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/css/isail_ui.css">

    <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
    <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/flat.min.css"> -->
    <?php if ($iSAIL_UI->theme) { ?>
    <link rel="stylesheet" id="css-theme" href="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/css/themes/<?php echo $iSAIL_UI->theme; ?>.min.css">
    <?php } ?>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $common->basePath . $iSAIL_UI->assets_folder; ?>/css/custom.css">
    <!-- END Stylesheets -->
</head>
<body <?php if ($iSAIL_UI->body_bg) { echo 'class="bg-image" style="background-image: url(\'' . $iSAIL_UI->body_bg . '\');"'; } ?>>